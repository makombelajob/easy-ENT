<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Ressources;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RessourcesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fileName', TextType::class, [
                'label' => 'Nom du fichier',
                'attr' => [
                    'placeholder' => 'Veuillez entrer le nom',
                    'class' => 'fs-4'
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un nom'),
                    new Length(
                        min: 5,
                        max: 100,
                        minMessage: 'Au moins {{ limit }} caractères autorisés',
                        maxMessage: 'Au plus {{ limit }} caractères autorisés'
                    )
                ]
            ])
            ->add('file', FileType::class, [ // <- ici le vrai champ de fichier
                'label' => 'Fichier à importer',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez télécharger un fichier']),
                     new File([
                        'maxSize' => '10M',
                        'mimeTypesMessage' => 'Ce type de fichier n\'est pas autorisé',
            ])
        ]
            ])
            ->add('fileType', TextType::class, [
                'label' => 'Type du fichier',
                'attr' => [
                    'placeholder' => 'Type du fichier',
                    'class' =>  'fs-4'
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer le type du fichier'),
                    new Length(
                        min: 2,
                        max: 10,
                        minMessage : 'Au moins {{ limit }} caractères autorisés',
                        maxMessage : 'Au plus {{ limit }} caractères autorisés'
                    )
                ]
            ])
            ->add('courses', EntityType::class, [
                'label' => 'Cours',
                'class' => Courses::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressources::class,
        ]);
    }
}
