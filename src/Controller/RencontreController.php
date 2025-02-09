<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RencontreController extends AbstractController
{
    #[Route('/rencontre', name: 'app_rencontre')]
    public function index(): Response
    {
        return $this->render('rencontre/rencontre.html.twig', [
            'controller_name' => 'RencontreController',
        ]);
    }

    #[Route('/rencontreBack', name: 'rencontreBack')]
    public function index1(): Response
    {
        return $this->render('rencontre/rencontreBack.html.twig', [
            'controller_name' => 'RencontreController',
        ]);
    }
}
