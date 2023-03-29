<?php

namespace App\Form;

use App\Entity\PermanentOffer;
use App\Form\Transformer\FilesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermanentOfferType extends AbstractType
{
    private $transformer;
    private $dt;
    public function __construct(FilesTransformer $transformer)
    {
        $this->transformer = $transformer;
        $this->dt = new \DateTime();
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ->add('startDate', DateType::class, [
                "label" => "Date de début de l'offre :",
                "format" => "dd MM y",
                'years' => [date('Y')],
                'months' => [date('m')],
                'days' => [date('d')],
            ])
            ->add('endDate', DateType::class, [
                "label" => "Date de fin de l'offre :",
                "format" => "dd MM y",
                'years' => [date('Y')],
                'months' => [date('m')],
                'days' => [date('d')],
            ])
            ->add('description', TextareaType::class, [
                "label" => "description de l'offre :",
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => OfferPictureType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
        ;
        $builder->get('pictures')->addModelTransformer($this->transformer);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PermanentOffer::class,
        ]);
    }
}
