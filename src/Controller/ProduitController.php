<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index1(): Response
    {
        return $this->render('produit/produit.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/detailProduit', name: 'detailProduit')]
    public function index2(): Response
    {
        return $this->render('produit/detailProduit.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    
    #[Route('/produitBack', name: 'produitBack')]
    public function index3(): Response
    {
        return $this->render('produit/produitBack.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
}
