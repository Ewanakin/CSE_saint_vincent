<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\OfferPicture;
use App\Entity\PermanentOffer;
use App\Form\PermanentOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    #[Route('/offer', name: 'show_offer')]
    public function show(EntityManagerInterface $manager): Response
    {
        $offers = $manager->getRepository(Offer::class)->findAll();
        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/admin/offer/create', name: 'create_offer')]
    #[Route('/admin/offer/edit/{offer}', name: 'edit_offer')]
    public function createOffer(Request $request, EntityManagerInterface $manager, Offer $offer = null): Response
    {
        if ($offer == null) {
            $offer = new PermanentOffer();
        }

        $form = $this->createForm(PermanentOfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $offer = $form->getData();
                $manager->persist($offer);
                $manager->flush();
                $this->redirectToRoute('list_offer');
                $this->addFlash('success', 'l\'offre a été créée');
                return $this->redirectToRoute('list_offer');
            } else {
                $this->addFlash('error', 'Une erreur est survenue');
            }
        }

        return $this->render('offer/backoffice/create.html.twig', [
            'offer' => $offer,
            'permanentOfferType' => $form->createView(),
        ]);
    }

    #[Route('admin/offer/list', name: 'list_offer')]
    public function listOffer(EntityManagerInterface $manager): Response
    {
        $offers = $manager->getRepository(Offer::class)->findAll();
        return $this->render('offer/backoffice/list.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('admin/offer/remove/{offer}', name: 'remove_offer')]
    public function removeOffer(EntityManagerInterface $em, Offer $offer)
    {
        $em->remove($offer);
        $em->flush();
        return $this->redirectToRoute('list_offer');
    }

    #[Route('admin/offer/remove/picture/{offerPicture}', name: 'remove_picture')]
    public function removePicture(EntityManagerInterface $em, Filesystem $filesystem, OfferPicture $offerPicture)
    {
        $projectId = $offerPicture->getOffer()->getId();
        $filesystem->remove($offerPicture->getLink());
        $em->remove($offerPicture);
        $em->flush();
        return $this->redirectToRoute('edit_offer');
    }
}