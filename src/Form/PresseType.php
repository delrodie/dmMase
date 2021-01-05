<?php

namespace App\Form;

use App\Entity\Presse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organe', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"L'organe de presse", 'autocomplete'=>"off"]])
            ->add('titre', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Le titre de l'article", 'autocomplete'=>"off"]])
            ->add('lien', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Le lien internet", 'autocomplete'=>"off"], 'required'=>false])
            ->add('media', FileType::class,[
                'attr'=>['class'=>"custom-file-input", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo",
                'mapped' => false,
                'multiple' => false,
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
            ->add('publishedAt', TextType::class,['attr'=>['class'=>"form-control fc-datepicker", 'placeholder'=>"Date de parution", 'autocomplete'=>"off"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Presse::class,
        ]);
    }
}
