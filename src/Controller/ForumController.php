<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use App\Form\CommentaireType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use App\Repository\UserRepository;

final class ForumController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/forum', name: 'app_forum')]
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Temporarily set the user to a fixed value for testing purposes
            $user = $this->entityManager->getRepository(User::class)->find(1);
            if (!$user) {
                throw $this->createNotFoundException('User not found.');
            }

            $post->setDatePost(new \DateTime());
            $post->setNbrLikes(0);
            $post->setUser($user);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_forum');
        }

        $posts = $postRepository->findAll();

         // 🔥 Ajout de la génération des formulaires de commentaires
     $commentForms = [];
    foreach ($posts as $post) {
        $commentaire = new Commentaire();
        $formComment = $this->createForm(CommentaireType::class, $commentaire);
        $commentForms[$post->getId()] = $formComment->createView();
    }

     return $this->render('forum/forum.html.twig', [
        'posts' => $posts,
        'form' => $form->createView(),
        'commentForms' => $commentForms, // Ajout des formulaires de commentaires
      ]);
     }

   
     #[Route('/forumback', name: 'forumback')]
    public function index1(): Response
    {
        return $this->render('forum/forumback.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }

    #[Route('/forum/edit/{id}', name: 'forum_edit')]
    public function edit(Post $post, Request $request): Response
    {

        $user = $this->entityManager->getRepository(User::class)->find(1);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_forum');
        }

        return $this->render('forum/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/forum/delete/{id}', name: 'forum_delete')]
    public function delete(Post $post): Response
      {
        $user = $this->entityManager->getRepository(User::class)->find(1);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_forum');
      } 

       #[Route('/forum/{id}', name: 'post_show')]
      public function show(Post $post): Response
      {
       return $this->render('forum/show.html.twig', [
        'post' => $post,
      ]);
        }

     //commentaire
    
     #[Route('/forum/{id}/comment', name: 'forum_add_comment', methods: ['POST'])]
     public function addComment(int $id, Request $request, PostRepository $postRepository, UserRepository $userRepository): Response
     {
         $post = $postRepository->find($id);
         if (!$post) {
             throw $this->createNotFoundException('Post not found.');
         }
     
         $commentaire = new Commentaire();
         $form = $this->createForm(CommentaireType::class, $commentaire);
         $form->handleRequest($request);
     
         if ($form->isSubmitted() && $form->isValid()) {
             // Récupérer l'utilisateur connecté
             $user = $this->getUser();
             if (!$user) {
                 throw $this->createAccessDeniedException('Vous devez être connecté pour commenter.');
             }
     
             $commentaire->setPost($post);
             $commentaire->setUser($user);
             $commentaire->setDateCommentaire(new \DateTime());
     
             $this->entityManager->persist($commentaire);
             $this->entityManager->flush();
     
             return $this->redirectToRoute('app_forum');
         }
     
         return $this->redirectToRoute('app_forum');
     }
     
     
 
 
     #[Route('/forum/comment/{id}/edit', name: 'forum_edit_comment')]
     public function editComment(Commentaire $commentaire, Request $request, CommentaireRepository $commentaireRepository): Response
     {
         $form = $this->createForm(CommentaireType::class, $commentaire);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
            
             $this->entityManager->flush();
 
             return $this->redirectToRoute('app_forum');
         }
 
         return $this->render('forum/edit_comment.html.twig', [
             'form' => $form->createView(),
             'commentaire' => $commentaire,
         ]);
 
         
      }
 
      #[Route('/forum/{id}/comment/delete', name: 'forum_delete_comment')]
public function deleteComment(Commentaire $commentaire): Response
{
   

   
    $user = $this->entityManager->getRepository(User::class)->find(1);
    if (!$user) {
        throw $this->createNotFoundException('User not found.');
    }

    $postId = $commentaire->getPost()->getId();
    
    $this->entityManager->remove($commentaire);
    $this->entityManager->flush();

    return $this->redirectToRoute('post_show', ['id' => $postId]);
}

 
 
}
