<?php

namespace App\Form;

use App\Entity\Historique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('annee', TextType::class, ['attr'=>['class'=>"form-control", "autocomplete"=>"off", 'placeholder'=>"annee"]])
            ->add('action', TextareaType::class,['attr'=>['class'=>"form-control", "rows"=>5]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Historique::class,
        ]);
    }
}
