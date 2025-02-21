<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Form\UserType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        // Create a new User entity
        $user = new User();
    
        // Create the form
        $form = $this->createForm(UserType::class, $user);
    
        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the user to the database
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Add a success message and redirect
            $this->addFlash('success', 'Vos informations ont été enregistrées avec succès.');
            return $this->redirectToRoute('app_reservation_index');
        }
    
        // Fetch all reservations
        $reservations = $reservationRepository->findAll();
    
        // Render the template with the form and reservations
        return $this->render('reservation/reservation.html.twig', [
            'reservations' => $reservations,
            'form' => $form->createView(), // Pass the form to the template
        ]);
    }
    #[Route('/reservationBack', name: 'reservationBack')]
    public function reservationPage(Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
    {
        // Create a new Reservation entity
        $reservation = new Reservation();
    
        // Create the form
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
    
        // Handle form submission (if needed)
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            // Redirect or do something else after successful submission
            return $this->redirectToRoute('reservationBack');
        }
    
        // Fetch all reservations
        $reservations = $reservationRepository->findAll();
    
        // Render the template with the form and reservations
        return $this->render('reservation/reservationBack.html.twig', [
            'controller_name' => 'ReservationController',
            'form' => $form->createView(), // Pass the form to the template
            'reservations' => $reservations, // Pass the reservations to the template
        ]);
    }

    #[Route('reservation/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('reservation/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('reservation/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('reservation/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}