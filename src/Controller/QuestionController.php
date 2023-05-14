<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/admin/question/list', name: 'question_list')]
    public function index(ManagerRegistry $manager): Response
    {
        $questions = $manager->getRepository(Question::class)->findAll();
        return $this->render('question/index.html.twig', [
            "questions" => $questions,
        ]);
    }

    #[Route('/admin/question/activate/{question}', name: 'question_activate')]
    public function questionActivate(EntityManagerInterface $em, Question $question): Response
    {
        $disabledQuestion = $em->getRepository(Question::class)->findOneBy(array("activate"=>1));
        if($disabledQuestion!=null)
        {
            $disabledQuestion->setActivate(0);
            $em->persist($disabledQuestion);
        }
        $question->setActivate(1);
        $em->persist($question);
        $em->flush();
        return $this->redirectToRoute('question_list');
    }

    #[Route('/admin/question/desactivate/{question}', name: 'question_desactivate')]
    public function questionDesactivate(EntityManagerInterface $em, Question $question): Response
    {
        $question->setActivate(0);
        $em->persist($question);
        $em->flush();
        return $this->redirectToRoute('question_list');
    }

    #[Route('/admin/question/add', name: 'question_add')]
    #[Route('/admin/question/edit/{question}', name: 'question_edit')]
    public function addQuestion(EntityManagerInterface $em, Request $request, Question $question=null): Response
    {
        if($question == null)
        {
            $question = new Question();
        }
        $questionForm = $this->createForm(QuestionType::class, $question);
        $questionForm->handleRequest($request);
        if($questionForm->isSubmitted() && $questionForm->isValid())
        {
            $question = $questionForm->getData();
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute("question_list");
        }
        return $this->render('question/edit.html.twig', [
            'questionForm' => $questionForm,
            'question' => $question
        ]);
    }

    #[Route('/admin/question/delete/{question}', name: 'question_delete')]
    public function questionDelete(EntityManagerInterface $em, Question $question): Response
    {
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute("question_list");
    }
}
