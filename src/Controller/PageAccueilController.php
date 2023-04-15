<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageAccueilController extends AbstractController
{
    #[Route('/page/accueil', name: 'app_page_accueil')]
    public function index(): Response
    {
        return $this->render('page_accueil/index.html.twig', [
            'controller_name' => 'PageAccueilController',
        ]);
    }
}
