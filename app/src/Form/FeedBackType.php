<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class,[
                'label'=>'Note du trajet',
                'choices'=>[
                    '🌿' => 1,
                    '🌿🌿' => 2,
                    '🌿🌿🌿' => 3,
                    '🌿🌿🌿🌿' => 4,
                    '🌿🌿🌿🌿🌿' => 5,
                ],
                'expanded'=>false,
            ])
            ->add('comment',TextareaType::class,[
                'label'=>'Commentaire (obligatoire si le trajet si problème)',
                'required'=>false,
                'attr'=>['rows'=>4],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
