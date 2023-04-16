<?php

namespace App\Form;

use App\Entity\LimitedOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LimitedOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $numbers = [];
        for ($i=0; $i <= 10; $i++)
        {
            array_push($numbers, $i);
        }
        $builder
            ->add('name', TextType::class,[
                "label" => "Nom de l'offre :",
            ])
            ->add('price', NumberType::class, [
                "label" => "Prix de l'offre :",
            ])
            ->add('nbPlaces', NumberType::class, [
                "label" => "Nombre de places :",
            ])
            ->add('orderNumber', ChoiceType::class, [
                "label" => "Ordre d'affichage",
                "choices" => $numbers,
            ])
            ->add('startDate', DateType::class, [
                "label" => "Date de début de l'offre :",
                "format" => "dd MM y",
                'years' => range(date('Y'), date('Y') + 10),
                'months' => range(1, 12),
                'days' => range(1,31),
            ])
            ->add('endDate', DateType::class, [
                "label" => "Date de fin de l'offre :",
                "format" => "dd MM y",
                'years' => range(date('Y'), date('Y') + 10),
                'months' => range(1, 12),
                'days' => range(1,31),
            ])
            ->add('startDate', DateType::class, [
                "label" => "Date de début de l'offre :",
                "format" => "dd MM y",
                'years' => range(date('Y'), date('Y') + 10),
                'months' => range(1, 12),
                'days' => range(1,31),
            ])
            ->add('endDate', DateType::class, [
                "label" => "Date de fin de l'offre :",
                "format" => "dd MM y",
                'years' => range(date('Y'), date('Y') + 10),
                'months' => range(1, 12),
                'days' => range(1,31),
            ])->add('displayStartDate', DateType::class, [
                "label" => "Date de début d'affichage :",
                "format" => "dd MM y",
                'years' => range(date('Y'), date('Y') + 10),
                'months' => range(1, 12),
                'days' => range(1,31),
            ])
            ->add('displayEndDate', DateType::class, [
                "label" => "Date de fin d'affichage :",
                "format" => "dd MM y",
                'years' => range(date('Y'), date('Y') + 10),
                'months' => range(1, 12),
                'days' => range(1,31),
            ])
            ->add('description', TextareaType::class, [
                "label" => "description de l'offre :",
                'attr' => array('cols' => '45', 'rows' => '5', 'class' => 'flex flex-col')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LimitedOffer::class,
        ]);
    }
}