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
use Symfony\Component\Validator\Constraints as Assert;
class EditProfilUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, [
                'constraints'=>[
                    new Assert\NotBlank(['message'=>'L\'adresse e-mail est requise.']),
                    new Assert\Email(['message'=>'Adresse e-mail invalide.']),
                ]
            ])
            ->add('username',TextType::class,[
                'label' => 'Pseudo',
                'constraints'=>[
                    new Assert\NotBlank(['message'=>'Le pseudo est requis.']),
                    new Assert\Length([
                        'min'=>3, 'max'=>50,
                        'minMessage'=>'Minimum {{ limit }} caractères.',
                        'maxMessage'=>'Maximum {{ limit }} caractères.'
                    ]),
                    new Assert\Regex([
                        'pattern'=>'/^[\p{L}\p{N}\s\-_.]+$/u',
                        'message'=> 'Le pseudo contient des caractères non autorisés. '
                    ])
                ]
                ])
            ->add('surname',TextType::class,[
                'required'=> false,
                'constraints'=>[
                    new Assert\Length(['max'=>50]),
                ]
                ])
            ->add('firstname',TextType::class,[
                'required'=>false,
                'constraints'=>[
                    new Assert\Length(['max'=>50]),
                ]
                ])
            ->add('phone', TextType::class,[
                'label'=> 'Numéro de téléphone',
                'required' => false,
                'constraints'=>[
                    new Assert\Regex([
                        'pattern'=>'/^[0-9 +().-]{10,20}$/',
                        'message' => 'Format de numéro invalide (10-20 caractères).',
                    ]),
                ]
            ])
            ->add('dateBirth', DateType::class, [
                'widget' => 'single_text',
                'required'=>false,
                'constraints'=>[
                    new Assert\LessThan([
                        'value' => 'today',
                        'message' => 'La date de naissance ne peut pas être dans le futur.',
                    ]),
                ]
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
            ]
            )
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
