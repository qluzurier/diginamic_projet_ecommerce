<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail* :'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom* :',
                'attr' => [
                    'style' => 'text-transform: uppercase;'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom* :'
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse :',
                'required' => false
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal :',
                'required' => false,
                'attr' => [
                    'minlength' => "5",
                    'maxlength' => "5",
                    'style' => 'text-transform: uppercase;'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville :',
                'required' => false,
                'attr' => [
                    'style' => 'text-transform: uppercase;'
                ]
            ])
            ->add('tel', TelType::class, [
                'label' => 'Téléphone :',
                'required' => false,
                'attr' => [
                    'pattern' => '[0-9]{10}'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier mon compte'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            //'csrf_protection' => false
        ]);
    }
}