<?php

namespace App\Form;

use App\Entity\SocialNetwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photos',FileType::class,[
                'label'=>'Images',
                'attr'=>[
                    'class'=>'form-control file'
                ],
                'required'=>false
            ])
            ->add('nameLink',TextType::class ,[
                'label'=>'Nom du lien',
                'attr'=>[
                    'class'=>'form-control namelink'
                ]
            ])
            ->add('link',TextType::class,[
                'label'=>'Lien',
                'attr'=>[
                    'class'=>'form-control link'
                ]
            ])
            ->add('description',TextareaType::class,[
                'label'=>'Description',
                'attr'=>[
                    'class'=>'form-control description'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SocialNetwork::class,
        ]);
    }
}
