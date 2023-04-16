<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SideBarController extends AbstractController
{
    #[Route('/sidebar', name: 'app_side_bar')]
    public function index(): Response
    {
        return $this->render('side_bar/index.html.twig', [
            'controller_name' => 'SideBarController',
        ]);
    }
}
