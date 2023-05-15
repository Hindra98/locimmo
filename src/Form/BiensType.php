<?php

namespace App\Form;

use App\Entity\Biens;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class BiensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix', NumberType::class)
            ->add('superficie', NumberType::class)
            ->add('rue')
            ->add('numero', NumberType::class)
            ->add('code')
            ->add('ville')
            ->add('description', TextareaType::class)
            ->add('adresse')
            ->add('dateCreation')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Chambres' => 0,
                    'Studios' => 1,
                    'Appartements' => 2,
                    'Maisons'=> 3
                ],
            ])
            ->add('photos', FileType::class, [
                'required' => false,
                'mapped' => false,
                'multiple' => true,
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Biens::class,
        ]);
    }
}
