<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\PartnerPicture;
use App\Form\PartnerPictureType;
use App\Form\PartnerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends AbstractController
{
    #[Route('/partner', name: 'partner_list')]
    public function partnerList(ManagerRegistry $em): Response
    {
        $partners = $em->getRepository(Partner::class)->findAll();
        return $this->render('partner/index.html.twig', [
            'partners' => $partners,
        ]);
    }


    #[Route('/admin/partner', name: 'partner_admin_list')]
    public function partnerAdminList(ManagerRegistry $em): Response
    {
        $partners = $em->getRepository(Partner::class)->findAll();
        return $this->render('partner/list.html.twig', [
            'partners' => $partners,
        ]);
    }

    #[Route('/admin/partner/ajout', name: 'partner_add')]
    #[Route('/admin/partner/modification/{partner}', name: 'partner_edit')]
    public function partnerEdit(EntityManagerInterface $em, Partner $partner = null, Request $request): Response
    {
        if($partner==null)
        {
            $partner = new Partner();
        }
        $formPartner = $this->createForm(PartnerType::class, $partner);
        $formPartner->handleRequest($request);
        if($formPartner->isSubmitted() && $formPartner->isValid())
        {
            $partner = $formPartner->getData();
            $partner->setLink($formPartner->get('link')->getData());
            $em->persist($partner);
            $em->flush();
            return $this->redirectToRoute("partner_admin_list");
        }
        return $this->render('partner/edit.html.twig', [
            'partnerForm' => $formPartner->createView(),
            'partner' => $partner,
        ]);
    }

    #[Route('/admin/partner/supprimer/{partner}', name: 'partner_delete')]
    public function partnerDelete(EntityManagerInterface $em, Partner $partner): Response
    {
        $em->remove($partner);
        $em->flush();
        return $this->redirectToRoute("partner_admin_list");
    }
    
}
