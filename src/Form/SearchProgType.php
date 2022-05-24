<?php

namespace App\Form;

use App\Entity\SearchProg;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchProgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'attr' => [
                    'placeholder' => 'Recherche',
                    'class' => 'form-control-sm'
                ],
                'required' => true
            ])
            ->add('machine', ChoiceType::class, [
                'choices' => [
                    'Fraise' => 'Fraisage',
                    'Tour' => 'Tournage'
                ],
                'choice_attr' => [
                    'Fraise' => [
                        'class' => 'form-check-input',
                    ],
                    'Tour' => [
                        'class' => 'form-check-input'
                    ]
                    ],
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchProg::class,
        ]);
    }
}
