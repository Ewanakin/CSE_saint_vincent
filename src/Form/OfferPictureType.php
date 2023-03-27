<?php

namespace App\Form;

use App\Entity\OfferPicture;
use App\Form\Transformer\FilesTransformer;
use App\Form\Transformer\FileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class OfferPictureType extends AbstractType
{
    private $transformer;
    public function __construct(FilesTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('pictureLink', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'extensions' => [
                            'pdf',
                            'jpg',
                            'png',
                        ],
                        'extensionsMessage' => 'Veuillez choisir un fichier de type: pdf/jpg/png.'
                    ])
                ]
            ]);
        $builder->get('pictureLink')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OfferPicture::class,
        ]);
    }
}