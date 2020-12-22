<?php

namespace App\Form;

use App\Entity\Slider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SliderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,['attr'=>['class'=>"form-control", 'autocomplete'=>"off"]])
            ->add('media', FileType::class,[
                'attr'=>['class'=>"custom-file-input", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo",
                'mapped' => false,
                'multiple' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => "1000000k",
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "Votre fichier doit être de type image"
                    ])
                ],
                'required' => false
            ])
            ->add('statut', CheckboxType::class,['required'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slider::class,
        ]);
    }
}
