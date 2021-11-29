<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse Email',
                ],
                'required' => false
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom ou Pseudo'
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control newpwd',
                    'placeholder' => 'Votre mot de passe'
                ]
            ])
            ->add('cfpassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control confpwd',
                    'placeholder' => 'Confirmez votre mot de passe'
                ]
            ])
            ->add('selectroles', ChoiceType::class, [
                'choices' => [
                    'User' => 'USER',
                    'Admin' => 'ADMIN'
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
