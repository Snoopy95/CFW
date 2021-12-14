<?php

namespace App\Form;

use App\Entity\ProgMeca;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddProgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Client'
                ]
            ])
            ->add('refpiece', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Référence pièce'
                ]
            ])
            ->add('ind', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ind'
                ],
                'required' => false
            ])
            ->add('desigpiece', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
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
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('plan', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'input-group-text'
                ],
                'required' => false
            ])
            ->add('step', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'input-group-text'
                ],
                'required' => false
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
