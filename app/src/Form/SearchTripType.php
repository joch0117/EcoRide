<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureCity', TextType::class, [
                'label' => 'Départ',
                'required' => false,
            ])
            ->add('arrivalCity', TextType::class, [
                'label' => 'Arrivée',
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-eco w-100 mt-2']
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // ← TRÈS IMPORTANT
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

}

