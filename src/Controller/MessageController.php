<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Entity\Matching;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'message_index')]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    #[Route('/message/new', name: 'message_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid JSON data']);
        }

        try {
            $message = new Message();
            $message->setContenuMessage($data['contenu_message']);
            $message->setCreatedAt(new \DateTime());
            $message->setUpdatedMessage($data['updated_message']);
            $message->setMatching($entityManager->getRepository(Matching::class)->find($data['matching_id']));
            $message->setUser($entityManager->getRepository(User::class)->find($data['user_id']));

            $entityManager->persist($message);
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'id' => $message->getId(), 'date' => $message->getCreatedAt()->format('Y-m-d H:i:s'), 'user' => (string) $message->getUser(), 'contenu' => $message->getContenuMessage(), 'updated' => $message->isUpdatedMessage()]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'error' => 'An error occurred while adding the message.']);
        }
    }

    #[Route('/message/edit/{id}', name: 'message_edit', methods: ['POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid JSON data']);
        }

        try {
            $message->setContenuMessage($data['contenu_message']);
            $message->setUpdatedMessage($data['updated_message']);
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'id' => $message->getId(), 'date' => $message->getCreatedAt()->format('Y-m-d H:i:s'), 'user' => (string) $message->getUser(), 'contenu' => $message->getContenuMessage(), 'updated' => $message->isUpdatedMessage()]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'error' => 'An error occurred while editing the message.']);
        }
    }

    #[Route('/message/{id}', name: 'message_show')]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/message/{id}/delete', name: 'message_delete')]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }

    #[Route('/message/messages/{matchingId}', name: 'message_by_matching', methods: ['GET'])]
    public function getMessagesByMatching(int $matchingId, MessageRepository $messageRepository): JsonResponse
    {
        $messages = $messageRepository->findBy(['matching' => $matchingId]);

        $data = [];
        foreach ($messages as $message) {
            $data[] = [
                'id' => $message->getId(),
                'contenu' => $message->getContenuMessage(),
                'date' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                'user' => (string) $message->getUser(),
                'updated' => $message->isUpdatedMessage(),
                'envoye' => $message->getUser() === $this->getUser(),
            ];
        }

        return new JsonResponse($data);
    }
}
