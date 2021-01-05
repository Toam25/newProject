<?php

namespace App\Form;

use App\Entity\Header;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',FileType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>"images",
                'multiple'=>false,
                'mapped'=>false,
                'required'=> true

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Header::class,
        ]);
    }
}
