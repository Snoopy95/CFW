<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestpwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control newpwd',
                    'placeholder' => 'Nouveau mot de passe'
                ]
            ])
            ->add('cfpassword', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control confpwd',
                    'placeholder' => 'Confirmez votre mot de passe'
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
