<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as PassType;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', PassType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Ancien mot de passe"],
                'label' => "Ancien mot de passe"
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PassType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                //'required' => $options['passwordRequired'],
                'first_options'  => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Répétez le mot de passe'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
