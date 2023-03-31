<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/add', name: 'newsletter_add')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $newsletter = new Newsletter();
        $newsletterForm = $this->createForm(NewsletterType::class, $newsletter);
        $newsletterForm->handleRequest($request);
        if($newsletterForm->isSubmitted() && $newsletterForm->isValid())
        {
            $newsletter = $newsletterForm->getData();
            $em->persist($newsletter);
            $em->flush();
            return $this->redirectToRoute('newsletter_add');
        }
        return $this->render('newsletter/index.html.twig', [
            'newsletterForm' => $newsletterForm,
        ]);
    }
}
