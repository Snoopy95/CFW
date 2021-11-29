<?php

namespace App\Form;

use App\Entity\Contacts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
            'attr' => [
                'placeholder' => 'Nom & Prénom',
                'class' => 'form-control'
            ],
            'required' => false
            ])
            ->add('mail', EmailType::class, [
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ],
            'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contacts::class,
        ]);
    }
}
