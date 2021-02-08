<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail* :'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'first_options'  => [
                    'label' => 'Mot de passe* :',
                    'attr' => [
                        'placeholder' => "6 caractères minimum"
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe* :',
                    'attr' => [
                        'placeholder' => "6 caractères minimum"
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit comprendre au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ]
                ]
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte les termes d\'inscription au site.',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes d\'inscription au site.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer mon compte !'
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
