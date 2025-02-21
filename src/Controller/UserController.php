<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

final class UserController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email_user');
            $password = $request->request->get('password');

            $user = $entityManager->getRepository(User::class)->findOneBy(['email_user' => $email]);

            if (!$user) {
                $this->addFlash('error', 'Email incorrect.');
                return $this->redirectToRoute('app_login');
            }

            if (!$passwordHasher->isPasswordValid($user, $password)) {
                $this->addFlash('error', 'Mot de passe incorrect.');
                return $this->redirectToRoute('app_login');
            }

            $session = $request->getSession();
            $session->set('user', $user);

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('user/login.html.twig');
    }

    #[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setNomUser($request->request->get('nom_user'));
            $user->setPrenomUser($request->request->get('prenom_user'));
            $user->setEmailUser($request->request->get('email_user'));
            $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('password')));
            $user->setAdresse($request->request->get('adresse'));
            $user->setTelephoneUser($request->request->get('telephone_user'));
            $user->setDateNaissanceUser(new \DateTime($request->request->get('dateNaissance_user')));
            $user->setRoleUser('USER'); // Default role

            // Handle File Upload
            $photoFile = $request->files->get('photo_user');
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move($this->getParameter('upload_directory'), $newFilename); // Fixed parameter name
                    $user->setPhotoUser($newFilename);
                } catch (FileException $e) {
                    return new Response("File upload failed: " . $e->getMessage());
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/signup.html.twig');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Fetch the current user from the session
        $session = $request->getSession();
        $user = $session->get('user');
        
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirect to login if not authenticated
        }
        
        // Fetch the user from the database to ensure we're working with the latest instance
        $user = $entityManager->getRepository(User::class)->find($user->getId());
        
        if (!$user) {
            return $this->redirectToRoute('app_login'); // User not found
        }
        
        // Create the form for editing the user details, excluding password
        $form = $this->createFormBuilder($user)
            ->add('nomUser', TextType::class, ['label' => 'Nom'])
            ->add('prenomUser', TextType::class, ['label' => 'Prénom'])
            ->add('emailUser', EmailType::class, ['label' => 'Email'])
            ->add('telephoneUser', TelType::class, ['label' => 'Téléphone'])
            ->add('adresse', TextType::class, ['label' => 'Adresse'])
            ->add('dateNaissanceUser', DateType::class, ['widget' => 'single_text', 'label' => 'Date de naissance'])
            ->add('photoUser', FileType::class, ['label' => 'Photo de profil', 'required' => false, 'mapped' => false])
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload if there's a new photo
            $file = $form->get('photoUser')->getData();
            if ($file) {
                $newFilename = uniqid() . '.' . $file->guessExtension();
                
                try {
                    $file->move($this->getParameter('upload_directory'), $newFilename); // Fixed parameter name
                    $user->setPhotoUser($newFilename);
                } catch (FileException $e) {
                    return new Response("File upload failed: " . $e->getMessage());
                }
            }
            
            // Handle email update (check if it's already in use)
            $newEmail = $form->get('emailUser')->getData();
            if ($newEmail !== $user->getEmailUser()) {
                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['emailUser' => $newEmail]);
                if ($existingUser) {
                    $this->addFlash('error', 'Cet email est déjà utilisé.');
                    return $this->redirectToRoute('app_dashboard');
                }
                $user->setEmailUser($newEmail);
            }
        
            // Persist the updated user entity
            $entityManager->flush();
        
            // Update the session with the new user data
            $session->set('user', $user);
        
            return $this->redirectToRoute('app_dashboard');
        }
        
        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('user');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/delete-profile', name: 'app_delete_profile')]
    public function deleteProfile(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $userId = $session->get('user')->getId();
        $user = $entityManager->getRepository(User::class)->find($userId);

        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
            $session->remove('user');
        }

        return $this->redirectToRoute('app_login');
    }
}