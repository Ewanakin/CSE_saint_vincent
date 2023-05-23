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
            $member = $form->getData();
            $member->setAboutUs($aboutUs);
            if ($form->get("picture")->getData() == null){
                $member->setName($form->get("name")->getData());
                $member->setFirstName($form->get("firstname")->getData());
            }
            else{
                $member = $member->setPicture($form->get('picture')->getData());
            }
            $em->persist($member);
            $em->flush();
            $this->addFlash('success', 'Le membre a été créée');
            return $this->redirectToRoute('list_members');
        }

        return $this->render('about/backoffice/member/create.html.twig', [
            'member' => $member,
            'memberType' => $form->createView(),
        ]);
    }

    #[Route('/admin/about/member/list', name: 'list_members')]
    public function listMembers(EntityManagerInterface $em): Response
    {
        $members = $em->getRepository(Member::class)->findAll();

        return $this->render('about/backoffice/member/list.html.twig',[
           'members' => $members,
        ]);
    }

    #[Route('/admin/about/member/remove/{entity}', name: 'remove_member')]
    public function removeMember(EntityManagerInterface $em, Member $entity)
    {
        $em->remove($entity);
        $em->flush();
        return $this->redirectToRoute('list_members');
    }
}