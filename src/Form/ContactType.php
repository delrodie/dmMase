<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Localisation géographique", 'autocomplete'=>"off"],
                'required'=>false
            ])
            ->add('bp', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Boite postale", 'autocomplete'=>"off"],
                'required'=>false
            ])
            ->add('phone', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Telephone", 'autocomplete'=>"off"],
                'required'=>false
            ])
            ->add('email', EmailType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Adresse email", 'autocomplete', 'off'],
                'required' => false
            ])
            ->add('map', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Adresse Google Map", 'autocomplete'=>"off"],
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
