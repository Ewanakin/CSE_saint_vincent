<?php

namespace App\Controller;

use App\Entity\LimitedOffer;
use App\Entity\Newsletter;
use App\Entity\Offer;
use App\Entity\OfferPicture;
use App\Entity\PermanentOffer;
use App\Events\LimitedOfferEvent;
use App\Form\LimitedOfferType;
use App\Form\OfferPictureType;
use App\Form\PermanentOfferType;
use App\Listeners\LimitedOfferListener;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    #[Route('/offers/{type}', name: 'show_offers')]
    public function show(Request $request, OfferRepository $offerRepository, PaginatorInterface $paginator, string $type = null): Response
    {
        $offers = $offerRepository->orderedOffer($type);
        foreach ($offers as $key => $offer) {
            if (is_a($offer, LimitedOffer::class)) {
                if ($offer->getOrderNumber() === 0
                    || $offer->getDisplayStartDate() >= \DateTime::createFromFormat('Y-m-d', date('Y-m-d'))
                    || $offer->getDisplayEndDate() <= \DateTime::createFromFormat('Y-m-d', date('Y-m-d'))) {
                    unset($offers[$key]);
                }
            }
        }

        $offersPaginate = $paginator->paginate(
            $offers,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('offer/index.html.twig', [
            'offers' => $offersPaginate,
            'type' => $type,
        ]);
    }

    #[Route('/offer/{offer}', name: 'show_offer')]
    public function showOffer(Offer $offer)
    {
        if ($offer == null) {
            $this->redirectToRoute('show_offers');
        }
        return $this->render('offer/showOffer.html.twig', [
            'offer' => $offer,
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
    public function createOfferLimited(Request $request, EntityManagerInterface $manager, MailerInterface $mailerInterface, Offer $offer = null): Response
    {
        if ($offer == null) {
            $offer = new LimitedOffer();
        }
        $newsletters = $manager->getRepository(Newsletter::class)->findAll();
        $dispatcher = new EventDispatcher();
        $listener = new LimitedOfferListener();
        $form = $this->createForm(LimitedOfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $offer = $form->getData();
                $manager->persist($offer);
                $manager->flush();
                $dispatcher->addListener('limited.offer.event', array($listener, 'onLimitedOfferEvent'));
                $dispatcher->dispatch(new LimitedOfferEvent($offer, $newsletters, $mailerInterface), LimitedOfferEvent::NAME);
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
        if ($offerType === 'permanent' || $offerType === null) {
            $offers = $manager->getRepository(PermanentOffer::class)->findAll();
        } elseif ($offerType === 'limited') {
            $offers = $manager->getRepository(LimitedOffer::class)->findAll();
        }

        return $this->render('offer/backoffice/list.html.twig', [
            'offers' => $offers,
            'offerType' => $offerType
        ]);
    }

    #[Route('admin/offer/remove/{entity}', name: 'remove_offer')]
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
        if (is_a($offer, PermanentOffer::class)) {
            return $this->redirectToRoute('edit_offer', array('offer' => $offer->getId()));
        }
        if (is_a($offer, LimitedOffer::class)) {
            return $this->redirectToRoute('edit_limitedOffer', array('offer' => $offer->getId()));
        }
    }

    #[Route('admin/offer/add/picture/{offer}', name: 'add_picture')]
    public function addPicture(EntityManagerInterface $em, Request $request, Offer $offer): Response
    {
        $offerPicture = new OfferPicture();
        $form = $this->createForm(OfferPictureType::class, $offerPicture, array('action' => $this->generateUrl('add_picture', array('offer' => $offer->getId()))));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $offerPicture->setOffer($offer);
            $offerPicture->setLink($form->get("picture")->getData());
            $em->persist($offerPicture);
            $em->flush();
            if (is_a($offer, PermanentOffer::class)) {
                return $this->redirectToRoute('edit_offer', array('offer' => $offer->getId()));
            }
            if (is_a($offer, LimitedOffer::class)) {
                return $this->redirectToRoute('edit_limitedOffer', array('offer' => $offer->getId()));
            }
        }

        return $this->render('form/offerPictureType.html.twig', [
            'offerPictureType' => $form->createView(),
        ]);
    }
}