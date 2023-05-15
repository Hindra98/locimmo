<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeBienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('piece', NumberType::class)
            ->add('garage', ChoiceType::class, [
                'choices' => [
                    'avec' => 1,
                    'sans' => 0
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('ascenceur', ChoiceType::class, [
                'choices' => [
                    'avec' => 1,
                    'sans' => 0
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('etage', ChoiceType::class, [
                'choices' => [
                    'avec' => 1,
                    'sans' => 0
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('grenier', ChoiceType::class, [
                'choices' => [
                    'avec' => 1,
                    'sans' => 0
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Kot' => 0,
                    'studio' => 1
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
