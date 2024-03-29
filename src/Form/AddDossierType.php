<?php

namespace App\Form;

use App\Entity\Dossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AddDossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numdossier', IntegerType::class, [
                'attr' => [
                    'placeholder' => "N° dossier",
                    'class' => 'form-control',
                    'min' => 0
                ]
            ])
            ->add('client', TextType::class, [
                'attr' => [
                    'placeholder' => 'Client',
                    'class' => 'form-control'
                ]
            ])
            ->add('ind', TextType::class, [
                'attr' => [
                    'placeholder' => 'Indice',
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('refpiece', TextType::class, [
                'attr' => [
                    'placeholder' => 'Réf pièce',
                    'class' => 'form-control'
                ]
            ])
            ->add('desigpiece', TextType::class, [
                'attr' => [
                    'placeholder' => 'Désignation pièce',
                    'class' => 'form-control'
                ],
                'empty_data' => '--',
                'required' => false
            ])
            ->add('plan', FileType::class, [
                'attr' => [
                    'class' => 'form-control inputPlan',
                ],
                'required'=> false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ]
                    ])
                ]
            ])
            ->add('step', FileType::class, [
                'attr' => [
                    'class' => 'form-control inputStep',
                ],
                'required'=> false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                        ]
                    ])
                    ]
            ])
            ->add('print', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                    'checked' => true
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'label' => 'Imprimer en quittant',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
