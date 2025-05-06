<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Enum\EnergyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plate', TextType::class, [
            'label' => 'Plaque d’immatriculation',
            'constraints' => [
                new NotBlank(['message' => 'La plaque est obligatoire.']),
                new Length(['min' => 5, 'max' => 15]),
                new Regex([
                    'pattern' => '/^[A-Z0-9- ]+$/i',
                    'message' => 'Format de plaque invalide.',
                ]),
            ],
        ])
        ->add('brand', TextType::class, [
            'label' => 'Marque',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 50]),
            ],
        ])
        ->add('model', TextType::class, [
            'label' => 'Modèle',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 50]),
            ],
        ])
        ->add('color', TextType::class, [
            'label' => 'Couleur',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 30]),
            ],
        ])
        ->add('first_registration', DateType::class, [
            'label' => 'Première immatriculation',
            'widget' => 'single_text',
            'constraints' => [
                new NotBlank(),
                new LessThan(['value' => 'today', 'message' => 'La date ne peut pas être dans le futur.']),
            ],
        ])
        ->add('energy_type', ChoiceType::class, [
            'label' => 'Énergie',
            'choices'=>EnergyType::cases(),
            'choice_label' => fn(EnergyType $choice) => match($choice) {
                EnergyType::ESSENCE => 'Essence',
                EnergyType::DIESEL => 'Diesel',
                EnergyType::ELECTRIQUE => 'Électrique',
                EnergyType::HYBRIDE => 'Hybride',
                EnergyType::GPL => 'GPL',
            },
            'placeholder' => 'Sélectionne un type',
            'constraints' => [
                new NotBlank(['message' => 'Sélectionnez un type d’énergie.']),
            ],
        ])
        ->add('seats_total', IntegerType::class, [
            'label' => 'Nombre de sièges',
            'constraints' => [
                new NotBlank(),
                new GreaterThan(['value' => 1]),
                new LessThan(['value' => 10]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
