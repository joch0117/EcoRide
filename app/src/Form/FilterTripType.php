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
    ->add('isEcological', CheckboxType::class, [
        'label' => 'Véhicule écologique uniquement',
        'required' => false,
    ])
    ->add('minRating', ChoiceType::class, [
        'label' => 'Note minimale du chauffeur',
        'required' => false,
        'placeholder' => 'Aucune',
        'choices' => [
            '1 feuille ou plus' => 1,
            '2 feuilles ou plus' => 2,
            '3 feuilles ou plus' => 3,
            '4 feuilles ou plus' => 4,
            '5 feuilles' => 5,
        ],
    ])
    ->add('maxDuration', IntegerType::class, [
        'label' => 'Durée maximale (en minutes)',
        'required' => false,
        'attr' => ['placeholder' => 'Ex : 120']
    ]);

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, 
            'method' => 'GET',
            'csrf_protection' => false,

        ]);
    }
}
