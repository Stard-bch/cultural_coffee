<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserBackController extends AbstractController
{
    // Route to handle user registration
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
    
        // Handle form submission
        if ($request->isMethod('POST')) {
            $user->setNomUser($request->request->get('nom_user'));
            $user->setPrenomUser($request->request->get('prenom_user'));
            $user->setEmailUser($request->request->get('email_user'));
            $user->setAdresse($request->request->get('adresse'));
            $user->setTelephoneUser((int)$request->request->get('telephone_user'));
            $user->setDateNaissanceUser(new \DateTime($request->request->get('dateNaissance_user')));

            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($hashedPassword);

            // Set the role to 'ROLE_ADMIN'
            $user->setRoleUser('ROLE_ADMIN');

            // Handle profile picture upload
            $profilePictureFile = $request->files->get('photo_user');
            if ($profilePictureFile) {
                $newFilename = uniqid().'.'.$profilePictureFile->guessExtension();
                try {
                    $profilePictureFile->move(
                        $this->getParameter('upload_directory'), // Use the correct parameter name
                        $newFilename
                    );
                    $user->setPhotoUser($newFilename);
                } catch (FileException $e) {
                    return new Response("File upload failed: " . $e->getMessage());
                }
            }

            // Save the user to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to the login page after successful registration
            return $this->redirectToRoute('loginback');
        }

        // Render the registration form template
        return $this->render('user_back/register.html.twig');
    }

    // Route for the login page
    #[Route('/loginback', name: 'loginback', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email_user');
            $password = $request->request->get('password');

            $user = $entityManager->getRepository(User::class)->findOneBy(['email_user' => $email]);

            if (!$user) {
                $this->addFlash('error', 'Email incorrect.');
                return $this->redirectToRoute('loginback');
            }

            if (!$passwordHasher->isPasswordValid($user, $password)) {
                $this->addFlash('error', 'Mot de passe incorrect.');
                return $this->redirectToRoute('loginback');
            }

            // Set the user in the session after successful login
            $session = $request->getSession();
            $session->set('user', $user);

            // Redirect to the user profile page after successful login
            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('user_back/loginback.html.twig');
    }

    // Route to display the user profile (name and email)
    #[Route('/user/profile', name: 'app_user_profile')]
    public function userProfile(Request $request): Response
    {
        // Get the user from the session
        $user = $request->getSession()->get('user');

        if (!$user) {
            // If no user is found in the session, redirect to the login page
            return $this->redirectToRoute('loginback');
        }

        // Render the user profile template
        return $this->render('user_back/profile.html.twig', [
            'user' => $user
        ]);
    }
}
