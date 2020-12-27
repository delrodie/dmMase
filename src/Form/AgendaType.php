<?php

namespace App\Form;

use App\Entity\Agenda;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['attr'=>['class'=>"form-control", 'autocomplete'=>"off", 'placeholder'=>"le titre de l'agenda"]])
            ->add('contenu', TextareaType::class, ['attr'=>['class'=>"form-control", 'rows'=>5]])
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
            ->add('dateDebut', TextType::class,['attr'=>['class'=>"form-control fc-datepicker", 'placeholder'=>"", 'autocomplete'=>"off"]])
            ->add('dateFin', TextType::class,['attr'=>['class'=>"form-control fc-datepicker", 'placeholder'=>"", 'autocomplete'=>"off"]])
            //->add('heureDebut', TextType::class,['attr'=>['class'=>"form-control heurepicker", 'placeholder'=>"", 'autocomplete'=>"off"]])
            //->add('heureFin', TextType::class,['attr'=>['class'=>"form-control heurepicker", 'placeholder'=>"", 'autocomplete'=>"off"]])
           // ->add('slug')
            //->add('createdAt')
           // ->add('updatedAt')
           // ->add('createdBy')
            //->add('updatedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}
