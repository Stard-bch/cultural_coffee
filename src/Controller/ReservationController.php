<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/reservation.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/paymentPage', name: 'PaymentReservation')]
    public function index1(): Response
    {
        return $this->render('reservation/paymentPage.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/reservationBack', name: 'resarvationBack')]
    public function index2(): Response
    {
        return $this->render('reservation/reservationBack.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
