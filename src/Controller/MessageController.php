<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;

use App\Repository\MatchingRepository;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'message_index', methods: ['GET'])]

    public function index(
        MessageRepository $messageRepository,
        UserRepository $userRepository,
        MatchingRepository $matchingRepository // Add this line
    ): Response {
        $user = $userRepository->find(1); // Fetch user with ID = 1
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
        $matching = $matchingRepository->findOneBy([]); // Get first matching entry
        if (!$matching) {
            throw $this->createNotFoundException('No matching found.');
        }

        return $this->render('matching/matching.html.twig', [
            'matchings' => $matchingRepository->findAll(),
            'current_user' => $user,
            'active_matching' => $matching,
            'messages' => $messageRepository->findBy(['matching' => $matching], ['createdAt' => 'ASC']),
        ]);
    }

    #[Route('/new/{matchingId}', name: 'message_new', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MatchingRepository $matchingRepository,
        UserRepository $userRepository,
        int $matchingId
    ): Response {
        $matching = $matchingRepository->find($matchingId);
        if (!$matching) {
            throw $this->createNotFoundException('Matching not found.');
        }
        $matchings = $matchingRepository->findAll();
        if (!$matchings) {
            throw $this->createNotFoundException('Matching not found.');
        }
        $user = $userRepository->find(1); // Replace with the actual authenticated user if needed
        if (!$user) {
            throw $this->createNotFoundException('Test user not found.');
        }

        $message = new Message();
        $message->setMatching($matching);
        $message->setUser($user);
        $message->setContent($request->request->get('contenu')); // Get content from form
        $message->setCreatedAt(new \DateTime()); // ✅ Set the current timestamp

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->redirectToRoute('matching', ['id' => $matchingId]); // Redirect to the correct matching
    }

    #[Route('/{id}', name: 'message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('matching/matching.html.twig', [
            'message' => $message,
        ]);
    }

    // src/Controller/MessageController.php
    #[Route('/message/edit/{id}', name: 'message_edit', methods: ['GET', 'POST'])]
    public function editMessage(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        // Ensure the user is authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Ensure the message belongs to the current user
        if ($message->getUser() !== $this->getUser()) {
            throw new AccessDeniedException('You can only edit your own messages.');
        }

        // Create the form
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the updated message
            $entityManager->flush();

            $this->addFlash('success', 'Message updated successfully.');
            return $this->redirectToRoute('matching', ['id' => $message->getMatching()->getId()]);
        }

        return $this->render('message/form.html.twig', [
            'form' => $form->createView(),
            'action' => 'Edit', // Pass the action (Add or Edit) to the template
        ]);
    }


    #[Route('/message/delete/{id}', name: 'message_delete', methods: ['POST'])]
    public function deleteMessage(Request $request, Message $message, EntityManagerInterface $entityManager)
    {
        // Ensure the user can only delete their own messages
        $user = $this->getUser();
        if ($message->getUser() !== $user) {
            throw $this->createAccessDeniedException('You can only delete your own messages.');
        }

        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
            $this->addFlash('success', 'Message deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('matching', ['id' => $message->getMatching()->getId()]);
    }
}