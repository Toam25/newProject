<?php

namespace App\Form;

use App\Entity\Boutique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoutiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('address',TextType::class,[
                'label'=>'Adresse',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('link',TextType::class,[
                'label'=>'Lien Fb',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('mail',EmailType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('contact',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('apropos',TextareaType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('image',FileType::class,[
                'label'=>'Logo',
                'required'=>true,
                 'mapped'=>false,
                 'required'=>false,
                 'attr'=>[
                     'required'=>false,
                     'class'=>'form-control'
                 ]
            ])
            ->add('user_condition',TextareaType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        ]);
    }
}
