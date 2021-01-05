<?php

namespace App\Form;

use App\Data\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minPrice')
            ->add('maxPrice')
            ->add('categorie')
            ->add('q',TextType::class,[
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'plecehoder'=>'Recherche'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'csrf_protection'=> false,
            'method'=>'GET'
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
