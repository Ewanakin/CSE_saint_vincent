<?php

namespace App\Form;

use App\Entity\Member;
use App\Form\Transformer\FileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MemberType extends AbstractType
{
    private FileTransformer $transformer;

    public function __construct(FileTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, [
                'label' => 'Prenom du membre',
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Nom du membre',
                'required' => true,
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "choisir votre image",
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
        if ($builder->get('picture') != null) {
            $builder->get('picture')->addModelTransformer($this->transformer);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}