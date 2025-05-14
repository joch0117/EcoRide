<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CreateEmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label'=>'adresse e-mail',
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]
            ])
            ->add('password',PasswordType::class,[
                'label' => 'Mot de passe',
                'mapped'=> false,
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>8]),
                    new Assert\Regex([
                        'pattern'=>'/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=\[\]{};:\'",.<>\/?\\|`~€£¤§°²])(?=.{8,}).*$/',
                        'message'=> 'Le mot de passe doit contenir 8 caractéres, une majuscule, un chiffre et un caractère spécial.'
                    ])
                ]

            ])
            ->add('username',TextType::class,[
                'label'=>'Pseudo',
                'required'=> false,
            ])
            ->add('surname',TextType::class,[
                'label'=>'Nom',
                'required'=> false,
            ])
            ->add('firstname',TextType::class,[
                'label'=>'Prénom',
                'required'=> false,
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
