<?php

namespace App\Form;

use App\Entity\Dossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
                    'placeholder' => 'Ref pièce',
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
                    'class' => 'custom-file-input'
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
