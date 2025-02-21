<?php

namespace App\Controller;

use App\Entity\Matching;
use App\Entity\User;
use App\Form\MatchingType;
use App\Repository\MatchingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ValidatorInterface;

final class MatchingController extends AbstractController
{
    #[Route('/matching', name: 'app_matching')]
    public function index(EntityManagerInterface $entityManager, MatchingRepository $matchingRepository): Response
    {
        $matchings = $matchingRepository->findAll();
        $form = $this->createForm(MatchingType::class);

        return $this->render('matching/matching.html.twig', [
            'matchings' => $matchings,
            'form' => $form->createView(),
            'selectedMatchingId' => null, // Initialize with null or a default value
            'rencontres' => $matchings, // Pass the matchings as rencontres
        ]);
    }

    #[Route('/matchingBack', name: 'matchingBack')]
    public function index1(): Response
    {
        return $this->render('matching/matchingBack.html.twig', [
            'controller_name' => 'matchingController',
        ]);
    }

    #[Route('/matching/add', name: 'matching_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid JSON data']);
        }

        try {
            $matching = new Matching();
            $matching->setName($data['name']);
            $matching->setSujetRencontre($data['sujetRencontre']);
            $matching->setNbrPersonneMatchy($data['nbrPersonneMatchy']);
            $matching->setImage($data['image']);
            $matching->setNumTable($data['numTable']);

            // Set the user (assuming the current logged-in user)
            $user = $this->getUser();
            if ($user instanceof User) {
                $matching->setUser($user);
            }

            // Add assessors (assuming the current logged-in user as an assessor)
            if ($user instanceof User) {
                $matching->addAssessor($user);
            }

            $entityManager->persist($matching);
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'id' => $matching->getId()]);
        } catch (\Exception $e) {
            $logger->error('Error adding matching', ['error' => $e->getMessage()]);
            return new JsonResponse(['success' => false, 'error' => 'An error occurred while adding the matching.']);
        }
    }

    #[Route('/matching/{id}', name: 'matching_show')]
    public function show(Matching $matching): Response
    {
        return $this->render('matching/show.html.twig', [
            'matching' => $matching,
        ]);
    }

    #[Route('/matching/edit/{id}', name: 'edit_matching')]
    public function editMatching(int $id, EntityManagerInterface $entityManager): Response
    {
        $matching = $entityManager->getRepository(Matching::class)->find($id);

        if (!$matching) {
            throw $this->createNotFoundException('Matching not found');
        }

        return $this->render('matching/edit.html.twig', [
            'matching' => $matching,
        ]);
    }

    #[Route('/matching/{id}/delete', name: 'matching_delete')]
    public function delete(Request $request, Matching $matching, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $matching->getId(), $request->request->get('_token'))) {
            $entityManager->remove($matching);
            $entityManager->flush();
        }

        return $this->redirectToRoute('matching_index');
    }
}
