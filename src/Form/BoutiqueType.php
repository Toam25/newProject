<?php

namespace App\Form;

use App\Entity\Boutique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoutiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la boutique',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Votre adresse"
                ]
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien Fb de la boutique',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "http://www.facebook.com"
                ]
            ])
            ->add('slogan', TextType::class, [
                'label' => 'Votre slogan',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Votre slogan"
                ]
            ])
            ->add('externalLink', TextType::class, [
                'label' => 'Lien externe ',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "http://www.monSite.com"
                ]
            ])
            ->add('mail', EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('contact', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('resume', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('apropos', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Logo',
                'required' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'required' => false,
                    'class' => 'form-control file'
                ]
            ])
            ->add('user_condition', CKEditorType::class, [

                'input_sync' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        ]);
    }
}
