<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SchoolFeesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de frais',
                'attr' => [
                    'placeholder' => 'Frais scolaire',
                    'class' => 'fs-4'
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entre un nom du frais')
                ]
            ])
            ->add('amount', IntegerType::class, [
                'label' => 'Montant',
                'attr' => [
                    'placeholder' => 'Montant de frais',
                    'class' => 'fs-4'
                ],
                'constraints' => [
                    new NotBlank(message: 'Montant obligatoire en euros')
                ]
            ])
            ->add('facturer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
