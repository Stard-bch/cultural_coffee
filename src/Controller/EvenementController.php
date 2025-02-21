<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/evenement.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

    #[Route('/evenementBack', name: 'evenementBack')]
    public function index1(): Response
    {
        return $this->render('evenement/evenementBack.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
}
