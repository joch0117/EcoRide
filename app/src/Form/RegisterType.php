<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, PasswordType, RepeatedType, SubmitType};
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'property_path'=>'username',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le pseudo est obligatoire.']),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 30,
                        'minMessage' => 'Minimum {{ limit }} caractères.',
                        'maxMessage' => 'Maximum {{ limit }} caractères.'
                    ]),
                    new Assert\Regex([
                        // autorise lettres, chiffres, tirets, espaces, accents légers
                        'pattern' => '/^[\p{L}\p{N}\s\-_\'éèêàùçœ]+$/u',
                        'message' => 'Le pseudo contient des caractères non autorisés.'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est requis.']),
                    new Assert\Email(['message' => 'Adresse email invalide.'])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new Assert\NotBlank(['message' => 'Le mot de passe est requis.']),
                        new Assert\Length(['min' => 8]),
                        new Assert\Regex([
                            'pattern'=>'/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=\[\]{};:\'",.<>\/?\\|`~€£¤§°²])(?=.{8,}).*$/',
                            'message'=> 'Le mot de passe doit contenir 8 caractéres, une majuscule, un chiffre et un caractère spécial.'
                        ])
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'register_user'
        ]);
    }
}


