<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;

final class PostController extends AbstractController
{
    #[Route('/forum', name: 'app_post')]
    public function index(EntityManagerInterface $entityManager, Request $request, Security $security): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();

        $post = new Post();
        $user = $security->getUser();
        $form = $this->createForm(PostType::class, $post, ['user_id' => $user->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setDatePost(new \DateTime());
            $post->setUser($user);

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }
                $post->setImage($newFilename);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post');
        }

        return $this->render('forum/forum.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/add', name: 'post_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $post = new Post();
        $user = $security->getUser();
        $form = $this->createForm(PostType::class, $post, ['user_id' => $user->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setDatePost(new \DateTime());
            $post->setUser($user);

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }
                $post->setImage($newFilename);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post');
        }

        $posts = $entityManager->getRepository(Post::class)->findAll();

        return $this->render('forum/forum.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/like/{id}', name: 'post_like', methods: ['POST'])]
    public function like(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        // Supprimer la gestion du like
        // $post->setLiked(!$post->isLiked());
        // $entityManager->persist($post);
        // $entityManager->flush();

        return new JsonResponse(['liked' => false]);
    }
}