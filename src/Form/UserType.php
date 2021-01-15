<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Adresse email", 'autocomplete'=>"off"],
                'label' => "Adresse email"
            ])
            ->add('roles', ChoiceType::class,[
                'choices'=>[
                    'Administrateur'=>'ROLE_ADMIN',
                    'Editeur'=>'ROLE_EDIT',
                    'Utilisateur'=>'ROLE_USER',
                ],
                'multiple'=>true,
                'expanded'=>true
            ])
            ->add('password', PasswordType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Mot de passe"],
                'label' => "Mot de passe"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
