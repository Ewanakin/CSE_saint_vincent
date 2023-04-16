<?php

namespace App\Form;

use App\Entity\AboutUs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rules', TextareaType::class, [
                'label' => 'Règlement du CSE : ',
                'required' => true,
                'attr' => array('cols' => '45', 'rows' => '5', 'class' => 'flex flex-col')
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email du CSE : ',
                'required' => true,
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AboutUs::class,
        ]);
    }
}