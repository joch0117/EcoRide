<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
class EditProfilUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class)
            ->add('username',TextType::class,['label' => 'Pseudo'])
            ->add('surname',TextType::class,['required'=> false])
            ->add('firstname',TextType::class,['required'=>false])
            ->add('phone', TextType::class,[
                'label'=> 'Numéro de téléphone',
                'required' => false,
            ])
            ->add('date_birth', DateType::class, [
                'widget' => 'single_text',
                'required'=>false,
            ])
            ->add('photo_url',FileType::class,[
                'label'=>'Photo de profil',
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'2M',
                        'mimeTypes'=>['image/jpeg','image/png'],
                        'mimeTypesMessage'=>'Formats autorisés : JPG, PNG',
                    ])
                ]
            ])
            ->add('is_driver',CheckboxType::class,[
                'label'=>'Je suis chauffeur',
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
