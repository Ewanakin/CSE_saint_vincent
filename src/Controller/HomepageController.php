<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'app_homepage')]
    public function index(EntityManagerInterface $manager): Response
    {
        $em = $manager->getRepository(User::class)->findAll();
        return $this->render('homepage/index.html.twig', [
            'users' => $em,
            'controller_name' => 'HomepageController',
        ]);
    }
}
