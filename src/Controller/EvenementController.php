<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('evenement/evenement.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
    
    #[Route('/evenementBack', name: 'app_evenement_back_index', methods: ['GET'])]
    public function backIndex(EvenementRepository $evenementRepository): Response
    {
        // Render the back-end template (evenementBack.html.twig)
        return $this->render('evenement/evenementBack.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/evenementBack/new', name: 'app_evenement_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $evenement->setDateEvent(new \DateTime());
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig',  [
            'evenement' => $evenement,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/evenementBack/{id}', name: 'app_evenement_back_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/evenementBack/{id}/edit', name: 'app_evenement_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/evenementBack/{id}', name: 'app_evenement_back_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'L\'événement a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }
    
        return $this->redirectToRoute('app_evenement_back_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/calendrier', name: 'calendrierEvenement')] 
    public function calendrier(): Response
    {
        return $this->render('evenement/calendrier.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
}