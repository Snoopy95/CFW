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

class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numdossier', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'N° Dossier',
            ])
            ->add('client', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Client',
            ])
            ->add('refpiece', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Ref pièce',
            ])
            ->add('ind', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Indice',
                'required' => false
            ])
            ->add('desigpiece', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'empty_data' => '--',
                'label' => 'Désignation pièce',
                'required' => false
            ])
            ->add('plan', FileType::class, [
                'attr' => [
                    'class' => 'custom-file-input inputPlan'
                ],
                'data_class' => null,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
