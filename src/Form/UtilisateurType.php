<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles')
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Regex(pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,}$/', message: 'Votre mot de passe doit contenir 6 caractères minimum dont 1 miniscule, 1 majuscule, 1 chiffre)')
                ]
            ])
            ->add('nom', TextType::class, [
                'constraints' => [new Length([
                    'min' => 4,
                    'minMessage' => 'Votre nom doit contenir au minimum 4 caractères'
                ])]
            ])
            ->add('create_at')
            ->add('email', EmailType::class)
            ->add('profil', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3072k',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'SVP choisissez une image valide (moins de 3Mo)'
                    ])
                ],
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'H',
                    'Femme' => 'F'
                ],
                'expanded' => true,
                'multiple' => false,
                'label_attr' => [
                    'class' => 'radio-inline'
                ]
            ])
            ->add('update_at')
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
