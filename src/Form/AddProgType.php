<?php

namespace App\Form;

use App\Entity\ProgMeca;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
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
            ->add('plan', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ]
                    ])
                ]
            ])
            ->add('retourplan', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'retourplan',
                    'hidden' => true
                ],
                'label' => false
            ])
            ->add('step', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('retourstep', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'retourstep',
                    'hidden' => true
                ],
                'label' => false
            ])
            ->add('typemachine', ChoiceType::class, [
                'choices' => [
                    'Fraise' => 'Fraisage',
                    'Tour' => 'Tournage'
                ],
                'choice_attr' => function() {
                    return ['class' => 'form-check-input'];
                },
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProgMeca::class,
        ]);
    }
}
