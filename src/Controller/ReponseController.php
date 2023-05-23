<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Form\ResponseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{
    #[Route('/admin/reponse/list/{question}', name: 'reponse_list')]
    public function index(ManagerRegistry $manager, Question $question=null): Response
    {
        $reponses = $manager->getRepository(Reponse::class)->findBy(array('question'=>$question));
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponses,
        ]);
    }

    #[Route('/admin/reponse/add', name: 'reponse_add')]
    #[Route('/admin/reponse/edit/{reponse}', name: 'reponse_edit')]
    public function reponseEdit(EntityManagerInterface $em, Request $request, Reponse $reponse=null): Response
    {
        if($reponse == null)
        {
            $reponse = new Reponse();
        }
        $reponseForm = $this->createForm(ReponseType::class, $reponse);
        $reponseForm->handleRequest($request);
        if($reponseForm->isSubmitted() && $reponseForm->isValid())
        {
            $reponse = $reponseForm->getData();
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute("reponse_list", array('question'=>$reponse->getQuestion()->getId()));
        }
        return $this->render('reponse/edit.html.twig', [
            'reponseForm' => $reponseForm,
            'reponse' => $reponse
        ]);
    }

    #[Route('/admin/reponse/delete/{reponse}', name: 'reponse_delete')]
    public function reponseDelete(EntityManagerInterface $em, Reponse $reponse): Response
    {
        $em->remove($reponse);
        $em->flush();
        return $this->redirectToRoute("reponse_list");
    }
}
