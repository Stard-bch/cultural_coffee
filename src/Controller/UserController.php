<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/login', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/loginback', name: 'loginback')]
    public function index1(): Response
    {
        return $this->render('user/loginback.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
