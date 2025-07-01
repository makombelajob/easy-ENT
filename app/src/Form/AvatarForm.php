<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvatarForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar', FileType::class, [
                'label' => 'Photo de profile',
                'constraints' => [
                    new NotBlank(),
                    new File(
                        maxSize: '7M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        mimeTypesMessage: 'Seule le formats JPEG, PNG et WEBP sont autorisée',
                    )
                ],
                'mapped' => false,
                'required' => true,
            ])
            ->add('changer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
