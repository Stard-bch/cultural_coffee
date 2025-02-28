<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Matching;
use App\Form\MatchingType;
use App\Repository\MatchingRepository;
use App\Repository\MessageRepository;  // ✅ Import MessageRepository
use App\Repository\UserRepository; // ✅ Import UserRepository

#[Route('/matching')]
class MatchingController extends AbstractController
{
    #[Route('/', name: 'matching_redirect', methods: ['GET'])]
    public function redirectToFirstMatching(MatchingRepository $matchingRepository): Response
    {
        // Fetch the first Matching from the database
        $firstMatching = $matchingRepository->findOneBy([], ['id' => 'ASC']);

        if (!$firstMatching) {
            throw $this->createNotFoundException('No matching found in the database.');
        }

        // Redirect to the first Matching's page
        return $this->redirectToRoute('matching', ['id' => $firstMatching->getId()]);
    }

    #[Route('/{id}', name: 'matching', methods: ['GET', 'POST'])]
    public function index(
        int $id,
        Request $request,
        MatchingRepository $matchingRepository,
        MessageRepository $messageRepository,
        UserRepository $userRepository, // Add this line
        EntityManagerInterface $entityManager
    ): Response {
        $matching = $matchingRepository->find($id);
        $currentUser = $userRepository->find(1);
        $activeMatching = $matching; // ✅ Ensure this variable exists

        if (!$matching) {
            throw $this->createNotFoundException('No matching found for id ' . $id);
        }
        $matchings = $matchingRepository->findAll();
        $messages = $messageRepository->findBy(['matching' => $matching]); // Fetch messages only for this Matching
        $allMatchings = $matchingRepository->findAll(); // Fetch all matchings

        return $this->render('matching/matching.html.twig', [
            'current_user' => $currentUser,
            'active_matching' => $activeMatching,
            'messages' => $messages,
            'matchings' => $matchings, // Ensure this variable exists
        ]);
    }


    public function showMatching(int $id, MatchingRepository $matchingRepository): Response
    {
        $matching = $matchingRepository->find($id);

        if (!$matching) {
            throw $this->createNotFoundException('No matching found for id ' . $id);
        }

        return $this->render('matching/matching.html.twig', [
            'matchings' => $matching, // Ensure this variable is being passed
        ]);
    }


    #[Route('/new', name: 'matching_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $matching = new Matching();
        $matching->setName('Default Name'); // ✅ Ensure it has a value

        $form = $this->createForm(MatchingType::class, $matching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matching); // ✅ Persist the entity
            $entityManager->flush();
            return $this->redirectToRoute('matching'); // ✅ Redirect after save
        }

        return $this->render('matching/matching.html.twig', [
            'matching' => $matching, // ✅ Ensure the entity exists
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}/edit', name: 'matching_edit', methods: ['POST'])]
    public function edit(Request $request, Matching $matching, MessageRepository $messageRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatchingType::class, $matching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('matching'); // Reload the page
        }
        $messages = $messageRepository->findBy(['matching' => $matching]); // Fetch messages only for this Matching
        return $this->render('matching/matching.html.twig', [
            'matchings' => $entityManager->getRepository(Matching::class)->findAll(),
            'matching' => $matching,
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'matching_delete', methods: ['POST'])]
    public function delete(Request $request, Matching $matching, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $matching->getId(), $request->request->get('_token'))) {
            $entityManager->remove($matching);
            $entityManager->flush();
        }

        return $this->redirectToRoute('matching'); // Reload the page
    }
}