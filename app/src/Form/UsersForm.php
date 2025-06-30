<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Courses;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuiller entrer un email'),
                    new Email(message: 'Entrer un email valide')
                ],
                'attr' => [
                    'placeholder' => 'Entrer l\'email ici',
                    'class' => 'fs-4'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => "Rôle interne",
                'choices' => [
                    'Utiliateur' => 'ROLE_USER',
                    'Teacher' => 'ROLE_TEACHER',
                    'Admin' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un nom'),
                    new Length(
                        min: 5,
                        max: 100,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisés',
                    )
                ],
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'fs-4'
                ]
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un prénom'),
                    new Length(
                        min: 5,
                        max: 100,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisés',
                    )
                ],
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'fs-4'
                ]
            ])
            ->add('changer', SubmitType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
