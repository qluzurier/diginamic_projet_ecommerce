<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'first_options'  => [
                    'label' => 'Nouveau mot de passe* :',
                    'attr' => [
                        'minlength' => 6,
                        'placeholder' => "6 caractères minimum"
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe* :',
                    'attr' => [
                        'minlength' => 6,
                        'placeholder' => "6 caractères minimum"
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
