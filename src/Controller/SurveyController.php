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
    #[Route('/survey', name: 'app_survey')]
    public function index(EntityManagerInterface $em, ManagerRegistry $manager, Request $request): Response
    {
        $newSurvey = new Survey();
        $survey = $manager->getRepository(Question::class)->findOneBy(array('activate'=>1));
        $surveyForm = $this->createForm(SurveyType::class, null, array("reponseFromQuestion"=> $survey->getId()));
        $surveyForm->handleRequest($request);
        if($surveyForm->isSubmitted() && $surveyForm->isValid())
        {
            $date = new DateTime('now');
            $newSurvey->setClientReponse($surveyForm->get("clientReponse")->getData()->getId());
            $newSurvey->setDate($date);
            $em->persist($newSurvey);
            $em->flush();
        }
        return $this->render('survey/index.html.twig', [
            'surveyForm' => $surveyForm,
            'survey' => $survey,
        ]);
    }
}
