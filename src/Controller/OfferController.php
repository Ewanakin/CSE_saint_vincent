<?php

namespace App\Controller;

use App\Entity\LimitedOffer;
use App\Entity\Offer;
use App\Entity\OfferPicture;
use App\Entity\PermanentOffer;
use App\Form\LimitedOfferType;
use App\Form\OfferPictureType;
use App\Form\PermanentOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isType;

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
                $this->addFlash('success', 'l\'offre a été créée');
                return $this->redirectToRoute('edit_offer', array('offer' => $offer->getID()));

            } else {
                $this->addFlash('error', 'Une erreur est survenue');
            }
        }

        return $this->render('offer/backoffice/create.html.twig', [
            'offer' => $offer,
            'permanentOfferType' => $form->createView(),
        ]);
    }

    #[Route('/admin/offer/limited/create', name: 'create_limitedOffer')]
    #[Route('/admin/offer/limited/edit/{offer}', name: 'edit_limitedOffer')]
    public function createOfferLimited(Request $request, EntityManagerInterface $manager, Offer $offer = null): Response
    {
        if ($offer == null) {
            $offer = new LimitedOffer();
        }

        $form = $this->createForm(LimitedOfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $offer = $form->getData();
                $manager->persist($offer);
                $manager->flush();
                $this->redirectToRoute('list_offer');
                $this->addFlash('success', 'l\'offre a été créée');
                return $this->redirectToRoute('edit_limitedOffer', array('offer' => $offer->getID()));
            } else {
                $this->addFlash('error', 'Une erreur est survenue');
            }
        }

        return $this->render('offer/backoffice/createLimited.html.twig', [
            'offer' => $offer,
            'limitedOfferType' => $form->createView(),
        ]);
    }

    #[Route('admin/offer/list/{offerType}', name: 'list_offer')]
    public function listOffer(EntityManagerInterface $manager, string $offerType = null): Response
    {
        $offers = null;
        if ($offerType === 'permanent'){
            $offers = $manager->getRepository(PermanentOffer::class)->findAll();
        }
        elseif ($offerType === 'limited'){
            $offers = $manager->getRepository(LimitedOffer::class)->findAll();
        }
        return $this->render('offer/backoffice/list.html.twig', [
            'offers' => $offers,
            'offerType' => $offerType
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
    public function removePicture(EntityManagerInterface $em, Filesystem $filesystem, OfferPicture $offerPicture = null)
    {
        $offer = $offerPicture->getOffer();
        $filesystem->remove($offerPicture->getLink());
        $em->remove($offerPicture);
        $em->flush();
        if (is_a($offer, PermanentOffer::class)){
            return $this->redirectToRoute('edit_offer', array('offer' => $offer->getId()));
        }
        if (is_a($offer, LimitedOffer::class)){
            return $this->redirectToRoute('edit_limitedOffer', array('offer' => $offer->getId()));
        }
    }

    #[Route('admin/offer/add/picture/{offer}', name: 'add_picture')]
    public function addPicture(EntityManagerInterface $em,Request $request,  Offer $offer)
    {
        $offerPicture = new OfferPicture();
        $form = $this->createForm(OfferPictureType::class, $offerPicture, array('action'=>$this->generateUrl('add_picture', array('offer' => $offer->getId()))));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $offerPicture->setOffer($offer);
            $offerPicture->setLink($form->get("picture")->getData());
            $em->persist($offerPicture);
            $em->flush();
            if (is_a($offer, PermanentOffer::class)){
                return $this->redirectToRoute('edit_offer', array('offer' => $offer->getId()));
            }
            if (is_a($offer, LimitedOffer::class)){
                return $this->redirectToRoute('edit_limitedOffer', array('offer' => $offer->getId()));
            }
        }

        return $this->render('form/offerPictureType.html.twig', [
           'offerPictureType' => $form->createView(),
        ]);
    }
}