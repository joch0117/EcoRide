<?php

namespace App\Form;

use App\Enum\EnergyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('maxPrice', IntegerType::class, [
        'label' => 'Prix maximum',
        'required' => false,
        'attr' => ['placeholder' => 'Ex : 25']
    ])
    ->add('vehicleType', ChoiceType::class, [
        'label' => 'Type de véhicule',
        'required' => false,
        'placeholder' => 'Choisir',
        'choices' => array_combine(
            array_map(fn(EnergyType $type)=>$type->label(),EnergyType::cases()),
            array_map(fn(EnergyType $type)=>$type->label(),EnergyType::cases()),
        )
        ],
    )
    ->add('isEcological', CheckboxType::class, [
        'label' => 'Véhicule écologique uniquement',
        'required' => false,
    ])
    ->add('minRating', ChoiceType::class, [
        'label' => 'Note minimale du chauffeur',
        'required' => false,
        'placeholder' => 'Aucune',
        'choices' => [
            '1 étoile ou plus' => 1,
            '2 étoiles ou plus' => 2,
            '3 étoiles ou plus' => 3,
            '4 étoiles ou plus' => 4,
            '5 étoiles' => 5,
        ],
    ])
    ->add('maxDuration', IntegerType::class, [
        'label' => 'Durée maximale (en minutes)',
        'required' => false,
        'attr' => ['placeholder' => 'Ex : 120']
    ])
    ->add('sortBy', ChoiceType::class, [
        'label' => 'Trier par',
        'required' => false,
        'placeholder' => 'Choisir',
        'choices' => [
            'Prix' => 'price',
            'Horaire' => 'datetime',
            'Durée' => 'duration',
        ],
    ]);

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
