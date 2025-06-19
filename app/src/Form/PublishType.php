<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**@var User|null $user  */
        $user = $options['user'] ?? null;
        $vehicles =$user ? $user->getVehicles() : [];
        $builder
            ->add('departure_city',TextType::class,[
                'label'=>'Ville de départ',
                'attr' =>['placeholder'=>'Ex :Paris','class'=>'bg-eco-cream text-eco-green form-control']
            ])
            ->add('arrival_city',TextType::class,[
                'label'=>'Ville d\'arrivée',
                'attr'=>['placeholder'=>'Ex:Lyon','class'=>'bg-eco-cream text-eco-green form-control'],
            ])
            ->add('departure_datetime', DateTimeType::class, [
                'label'=>'Date et heure de départ',
                'widget' => 'single_text',
                'input' => 'datetime',
                'attr'=> ['class'=>'bg-eco-cream text-eco-green form-control']
            ])
            ->add('arrival_datetime', DateTimeType::class, [
                'label'=>'Date et heure de d\'arrivée',
                'widget' => 'single_text',
                'input' => 'datetime',
                'attr'=> ['class'=>'bg-eco-cream text-eco-green form-control']
            ])
            ->add('price',IntegerType::class,[
                'label'=>'Prix en crédit',
                'attr'=> ['class'=>'bg-eco-cream text-eco-green form-control']
            ])
            ->add('seats_available',IntegerType::class,[
                'label'=>'Places disponibles',
                'attr'=> ['class'=>'bg-eco-cream text-eco-green form-control']
            ])
            ->add('vehicle',EntityType::class,[
                'class'=>Vehicle::class,
                'choices'=>$vehicles,
                'choice_label'=>function(Vehicle $vehicle){
                    return sprintf('%s %s (%s)',
                    $vehicle->getBrand(),
                    $vehicle->getModel(),
                    $vehicle->getEnergyType()->value,
                    );
                },
                'label'=>'Véhicules',
                'placeholder'=>'Séléctionnez un vehicule',
                "required"=>true,
                'attr'=>['class'=>'bg-eco-cream text-eco-green form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
            'user'=>null,
        ]);
    }
}
