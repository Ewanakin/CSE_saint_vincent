<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Survey;
use App\Form\SurveyType;
use App\Repository\SurveyRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SurveyController extends AbstractController
{

    #[Route('/admin/survey/responses', name: 'survey_response')]
    public function surveyResponse(ManagerRegistry $manager)
    {
        $surveyQuestion = $manager->getRepository(question::class)->findOneBy(array('activate'=>1));
        $survey = $manager->getRepository(Survey::class)->findAll();
        return $this->render('survey/list.html.twig', [
            'survey' => $survey,
            'surveyQuestion' => $surveyQuestion,
        ]);
    }

    #[Route('/survey', name: 'app_survey')]
    public function index(EntityManagerInterface $em, ManagerRegistry $manager, Request $request): Response
    {
        $newSurvey = new Survey();
        $survey = $manager->getRepository(Question::class)->findOneBy(array('activate'=>1));
        $surveyForm = $this->createForm(SurveyType::class, null, array("reponseFromQuestion"=> $survey->getId()));
        $surveyForm->handleRequest($request);
        if($surveyForm->isSubmitted() && $surveyForm->isValid())
        {
            $newSurvey = $surveyForm->getData();
            $date = new DateTime('now');
            $newSurvey->setDate($date);
            $em->persist($newSurvey);
            $em->flush();
        }
        return $this->render('survey/index.html.twig', [
            'surveyForm' => $surveyForm,
            'survey' => $survey,
        ]);
    }

    #[Route('/admin/survey/stats', name:'survey_stats')]
    public function surveyStats(SurveyRepository $surveyRepo)
    {
        $stats = $surveyRepo->activateStats();
        return $this->render('survey/stats.html.twig', [
            'stats' => $stats,
        ]);
    }
}
