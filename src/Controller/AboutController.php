<?php

namespace App\Controller;

use App\Entity\AboutUs;
use App\Form\AboutType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(EntityManagerInterface $em, AboutUs $aboutUs = null): Response
    {
        if ($aboutUs == null){
            $aboutUs = new AboutUs();
        }
        $aboutUs = $em->getRepository(AboutUs::class)->findOneBy(array(), null, '1');
        return $this->render('about/index.html.twig', [
            'about' => $aboutUs,
        ]);
    }

    #[Route('/admin/about/create', name: 'create_about')]
    #[Route('/admin/about/edit/{aboutUs}', name: 'edit_about')]
    public function createABout(Request $request, EntityManagerInterface $manager, AboutUs $aboutUs = null): Response
    {
        if ($aboutUs == null) {
            $aboutUs = new AboutUs();
        }

        $form = $this->createForm(AboutType::class, $aboutUs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aboutUs = $form->getData();
            $manager->persist($aboutUs);
            $manager->flush();
            $this->addFlash('success', 'la page à propos a été modifiée');
            $this->redirectToRoute('app_about');
        }

        return $this->render('about/backoffice/create.html.twig', [
            'aboutUs' => $aboutUs,
            'aboutUsType' => $form->createView(),
        ]);
    }


    #[Route('/admin/about/list', name: 'list_about')]
    public function listAboutUs(EntityManagerInterface $em): Response
    {
        $aboutUs = $em->getRepository(AboutUs::class)->findOneBy(array(), null, '1');

        return $this->render('about/backoffice/list.html.twig', [
            'aboutUs' => $aboutUs,
        ]);
    }
}
