<?php

namespace App\Form;

use App\Entity\Blog;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Titre',
                'attr'=>[
                    'class'=>"form-control"
                ]
            ])
            ->add('category', ChoiceType::class,[
                'label'=>'Category',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'choices'=>[
                    'Actualités'=>'Actualités',
                    'Atouts'=>'Atouts',
                    'Astuces'=>'Astuces',
                    'Test'=>'Test',
                    'Tutoriels'=>'Tutoriels'
                ]
            ])
            ->add('resume',TextType::class,[
                'label'=>'Résumé',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            
            ->add('keywords',TextType::class,[
                'label'=>'Mots clé',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            
            ->add('metaDescription',TextareaType::class,[
                'label'=>'Meta déscription',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('link',TextType::class,[
                'label'=>'Lien',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('description',CKEditorType::class,[
                'label'=>'Déscription',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
