<?php

namespace App\Form;

use App\Entity\SearchIn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchin', TextType::class, [
                'attr' => [
                    'placeholder' => 'Recherche',
                    'class' => 'form-control-sm'
                ],
                'required' => false
            ])
            ->add('infield', ChoiceType::class, [
                'choices' => [
                    'Référence pièce' => 'refpiece',
                    'Client' => 'client',
                    'Désignation pièce' => 'desigpiece'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchIn::class,
        ]);
    }
}
