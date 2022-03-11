<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raisonSociale', TextType::class,[
				'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
            ])
            ->add('siege', TextType::class,[
				'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
	            'label' => "Siège"
            ])
            ->add('adresse', TextType::class,[
				'attr'=>['class'=>'form-control', 'autocomplete'=>"off"]
            ])
            ->add('telephone', TextType::class,[
				'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
	            'label' => "Téléphone"
            ])
            ->add('email', TextType::class,[
				'attr'=>['class'=>'form-control', 'autocomplete'=>"off"]
            ])
            ->add('annee', TextType::class,[
				'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
	            'label' => "Année"
            ])
            ->add('domaine', TextareaType::class,[
				'attr'=>['class'=>'form-control']
            ])
            ->add('logo', FileType::class,[
	            'attr'=>['class'=>"custom-file-input", 'data-preview' => ".preview"],
	            'label' => "Télécharger le logo",
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
            //->add('slug')
            //->add('valid')
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
