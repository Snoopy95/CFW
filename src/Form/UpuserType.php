<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpuserType extends AbstractType
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
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control newpwd',
                    'placeholder' => 'Votre mot de passe'
                ],
                'required' => false,
                'empty_data' => 'empty'
            ])
            ->add('cfpassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control confpwd',
                    'placeholder' => 'Confirmez votre mot de passe'
                ],
                'required' => false,
                'empty_data' => 'empty'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
