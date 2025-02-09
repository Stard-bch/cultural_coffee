<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{
    #[Route('/cart', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/cart.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    #[Route('/commandeBack', name: 'commandeBack')]
    public function index1(): Response
    {
        return $this->render('commande/commandeBack.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }


    #[Route('/paymentCommande', name: 'paymentCommande')]
    public function index2(): Response
    {
        return $this->render('commande/paymentCommande.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
