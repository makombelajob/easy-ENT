<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Votre email',
                    'class' => 'fs-3',
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un email'),
                    new Email(message: 'Veuillez entrer un email valide'),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions RGPD',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisations',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mots de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'mots de passe',
                    'class' => 'fs-3'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mots de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mots de passe d\'au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'class' => 'fs-3'
                ],
                'constraints' => [
                    new Length(
                        min: 2,
                        max: 100,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisés'
                    ),
                    new NotBlank(message: 'Veuillez entrer votre nom')
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'class' => 'fs-3'
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre prénom'),
                    new Length(
                        min: 2,
                        max: 100,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisés'
                    )
                ]
            ])
            ->add('Inscription', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success fs-3',
                    //'disabled' => true,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
