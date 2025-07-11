<?php

namespace App\Form;

use App\Entity\Subjects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubjectsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la matière',
                'constraints' => [
                    new NotBlank(message: 'Veuillez entre le nom'),
                    new Length(
                        min: 5,
                        max: 50,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisés'
                    )
                    ],
                    'attr' => [
                        'placeholder' => 'Veuillez entrer le nom',
                        'class' => 'fs-4',
                ]
            ])
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subjects::class,
        ]);
    }
}
