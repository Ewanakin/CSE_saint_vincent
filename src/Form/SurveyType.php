<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Survey;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyType extends AbstractType
{

    private $reponseFromQuestion;
    private $reponseRepository;

    public function __construct(ReponseRepository $reponseRepository)
    {
        $this->reponseRepository = $reponseRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientResponse', EntityType::class, [
                "class" => Reponse::class,
                "choices" => $this->reponseRepository->findBy(array("question"=>$options["reponseFromQuestion"]))
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
            'reponseFromQuestion' => null
        ]);
    }
}
