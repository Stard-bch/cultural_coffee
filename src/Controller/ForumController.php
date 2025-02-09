<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(): Response
    {
        return $this->render('forum/forum.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }


    #[Route('/forumback', name: 'forumback')]
    public function index1(): Response
    {
        return $this->render('forum/forumback.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
