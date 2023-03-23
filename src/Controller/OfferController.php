<?php

namespace App\Controller;

use App\Entity\LimitedOffer;
use App\Entity\PermanentOffer;
use App\Form\PermanentOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    #[Route('/offer', name: 'show_offer')]
    public function show(EntityManagerInterface $manager): Response
    {
        $permanentOffers = $manager->getRepository(PermanentOffer::class)->findAll();
        $limitedOffers = $manager->getRepository(LimitedOffer::class)->findAll();
        return $this->render('offer/index.html.twig', [
            'limitedOffer' => $limitedOffers,
            'permanentOffer' => $permanentOffers,
        ]);
    }

    #[Route('/offer/create', name: 'create_offer')]
    public function createOffer(Request $request, EntityManagerInterface $manager): Response
    {
        $offer = new PermanentOffer();
        $form = $this->createForm(PermanentOfferType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $offer = $form->getData();
                $manager->persist($offer);
                $manager->flush();
                $this->addFlash('success', 'l\'offre a été créée');
            } else {
                $this->addFlash('error', 'Une erreur est survenue');
            }
        }

        return $this->render('offer/createOffer.html.twig', [
            'permanentOfferType' => $form->createView(),
        ]);
    }
}