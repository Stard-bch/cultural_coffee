<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $post->setImage($newFilename);
            } else {
                $this->addFlash('error', 'Image cannot be null');
                return $this->redirectToRoute('app_forum');
            }

            if (!$post->getDatePost()) {
                $post->setDatePost(new \DateTime());
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum');
        }

        return $this->render('forum/forum.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forumback', name: 'forumback')]
    public function index1(): Response
    {
        return $this->render('forum/forumback.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
