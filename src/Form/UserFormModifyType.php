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
                'label' => 'E-mail* :',
                'attr' => [
                    'style' => 'text-transform: uppercase;width:30%; margin:15px;']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom* :',
                'attr' => [
                    'style' => 'text-transform: uppercase;width:30%; margin:15px;']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom* :',
                'attr' => [
                    'style' => 'text-transform: uppercase;width:30%; margin:15px;']
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse :',
                'required' => false,
                'attr' => [
                    'style' => 'text-transform: uppercase;width:30%; margin:15px;']
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal :',
                'required' => false,
                'attr' => [
                    'minlength' => "5",
                    'maxlength' => "5",
                    'style' => 'text-transform: uppercase;width:30%; margin:15px;'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville :',
                'required' => false,
                'attr' => [
                    'style' => 'text-transform: uppercase;width:30%; margin:15px;'
                ]
            ])
            ->add('tel', TelType::class, [
                'label' => 'Téléphone :',
                'required' => false,
                'attr' => [
                    'pattern' => '[0-9]{10}',
                    'style' => 'text-transform: uppercase;width:30%; margin:15px;'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier mon compte'
                ,
                'attr' => [
                    'style' => ' margin-bottom:30px;'
                ]
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
