<?php

namespace App\Form;

use App\Entity\SearchProg;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'attr' => [
                    'placeholder' => 'Recherche',
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('machine', ChoiceType::class, [
                'choices' => [
                    'Toutes' => false,
                    'Fraise' => 'Fraisage',
                    'Tour' => 'Tournage',
                ],
                'choice_attr' => function($choices) {
                    if ($choices === false) {
                        $style = ['class' => 'form-check-input', 'checked' => true];
                    } else {
                        $style = ['class' => 'form-check-input'];
                    }
                    return $style;
                },           
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchProg::class,
        ]);
    }
}
