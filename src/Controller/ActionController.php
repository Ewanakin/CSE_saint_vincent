<?php

namespace App\Controller;

use App\Entity\AboutUs;
use App\Entity\Action;
use App\Form\ActionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActionController extends AbstractController
{
    #[Route('/admin/about/create/action', name: 'create_action')]
    #[Route('/admin/about/edit/action/{action}', name: 'edit_action')]
    public function createAction(Request $request, EntityManagerInterface $em, Action $action = null): Response
    {
        $aboutUs = $em->getRepository(AboutUs::class)->findOneBy(array(), null, '1');
        if ($action == null) {
            $action = new Action();
        }


        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $action->setAboutUs($aboutUs);
            $action = $form->getData();
            $em->persist($action);
            $em->flush();
            $this->addFlash('success', 'l\'action a été créée');
            return $this->redirectToRoute('list_about');
        }

        return $this->render('about/backoffice/action/create.html.twig', [
            'action' => $action,
            'actionType' => $form->createView(),
        ]);
    }

    #[Route('/admin/about/action/list', name: 'list_actions')]
    public function listMembers(EntityManagerInterface $em): Response
    {
        $actions = $em->getRepository(Action::class)->findAll();

        return $this->render('about/backoffice/action/list.html.twig',[
            'actions' => $actions,
        ]);
    }

    #[Route('/admin/about/action/remove/{entity}', name: 'remove_action')]
    public function removeAction(EntityManagerInterface $em, Action $entity)
    {
        $em->remove($entity);
        $em->flush();
        return $this->redirectToRoute('list_actions');
    }
}