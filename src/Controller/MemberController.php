<?php

namespace App\Controller;

use App\Entity\AboutUs;
use App\Entity\Member;
use App\Form\MemberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    #[Route('/admin/about/create/member', name: 'create_member')]
    #[Route('/admin/about/edit/member/{member}', name: 'edit_member')]
    public function createMember(Request $request, EntityManagerInterface $em, Member $member = null): Response
    {
        $aboutUs = $em->getRepository(AboutUs::class)->findOneBy(array(), null, '1');
        if ($member == null) {
            $member = new Member();
        }

        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $member = $member->setAboutUs($aboutUs);
            $member->setPicture($form->get("picture")->getData());
            $em->persist($member);
            $em->flush();
            $this->addFlash('success', 'le membre a été créée');
            return $this->redirectToRoute('list_about');
        }

        return $this->render('about/backoffice/member/create.html.twig', [
            'member' => $member,
            'memberType' => $form->createView(),
        ]);
    }
}