<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddParentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' =>  'Nom',
                'attr' => [
                    'placeholder' =>  'Nom du responsable',
                    'class' => 'fs-4',
                ],
                'constraints' => [
                    new NotBlank(message:'Veuillez entre le nom'),
                    new Length(
                        min: 2,
                        max: 100,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisées'
                    )
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' =>  'Prénom',
                'attr' => [
                    'placeholder' =>  'Prénom du responsable',
                    'class' => 'fs-4',
                ],
                'constraints' => [
                    new NotBlank(message:'Veuillez entre le prénom'),
                    new Length(
                        min: 2,
                        max: 100,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisées'
                    )
                ]
            ])
            ->add('email', EmailType::class, [
                'label' =>  'Email du responsable',
                'attr' => [
                    'placeholder' =>  'Email du responsable',
                    'class' => 'fs-4',
                ],
                'constraints' => [
                    new NotBlank(message:'Email obligatoire'),
                    new Email(message: 'Veuillez entrer un email valide')
                ]
            ])
            ->add('ajouter', SubmitType::class, [])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
