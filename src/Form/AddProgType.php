<?php

namespace App\Form;

use App\Entity\ProgMeca;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputClient',
                    'placeholder' => 'Client'
                ]
            ])
            ->add('refpiece', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputRef',
                    'placeholder' => 'Référence pièce'
                ]
            ])
            ->add('ind', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputInd',
                    'placeholder' => 'Ind'
                ]
            ])
            ->add('desigpiece', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputDesign',
                    'placeholder' => 'Désignation pièce'
                ]
            ])
            ->add('typemachine', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Fraisage' => 'fraisage',
                    'Tournage' => 'tournage'
                ],
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label' => "false",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProgMeca::class,
        ]);
    }
}
