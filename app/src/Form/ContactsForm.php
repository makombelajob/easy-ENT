<?php

namespace App\Form;

use App\Entity\Contacts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(message: 'Veuillez entre un email vaalide'),
                    new NotBlank(message: 'L\'Email est obligatoire')
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre e-mail',
                    'class' => 'fs-3',
                ]
            ])
            ->add('subject', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 100,
                        'minMessage' => 'au moins {{ limit }} caractères autorisées',
                        'maxMessage' => 'au plus {{ limit }} caractères autorisées'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Entrez un sujet',
                    'class' => 'fs-3',
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'minMessage' => 'au moins {{ limit }} caractères autorisées',
                    ])
                ],
                'attr' => [
                    'rows' => 10,
                    'cols' => 30,
                    'placeholder' => 'Veuillez entre votre message ici. D\'au moins 10 caractères.',
                    'class' => 'fs-3',
                ]
            ])
            ->add('gpdr', CheckboxType::class, [
                'label' => 'J\'accpetes les conditions d\'utilisations',
                'mapped' => false,
                /*'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez cochez cette',
                    ])
                ]*/
            ])
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contacts::class,
        ]);
    }
}
