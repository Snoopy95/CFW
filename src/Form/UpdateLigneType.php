<?php

namespace App\Form;

use App\Entity\AppelOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateLigneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Client'
                ]
            ])
            ->add('matiere', TextType::class, [
                'attr' => [
                    'class'=> 'form-control',
                    'placeholder' => 'Matiere'
                ]
            ])
            ->add('debit', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Debit'
                ]
            ])
            ->add('epaisseur', IntegerType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'placeholder' => 'Epaisseur'
                ]
            ])
            ->add('quantite', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Quantite'
                ]
            ])
            ->add('naf', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'NAF'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppelOffre::class,
        ]);
    }
}
