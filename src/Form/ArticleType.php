<?php

namespace App\Form;

use App\Entity\Article;
use App\Service\CategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{    
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService= $categoryService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom',
                'attr'=>[
                    'class'=>'form-control'
                ]

            ])
            ->add('price',NumberType::class,[
                'label'=>'Price',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('price_promo',NumberType::class,[
                'label'=> 'Prix promo',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('price_global',NumberType::class,[
                'label'=>'Prix en gros',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('quantity',TextType::class,[
                'label'=>'Stock',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('promo',ChoiceType::class,[
                'label'=>'Promotion',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'choices'=>[
                    'Normal'=>'Normal',
                    'Promotion'=>'Promotion',
                    'new'=>'New'
                ]
            ])
            ->add('marque',TextType::class,[
                'label'=>'Marque',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('category',ChoiceType::class,[
                'label'=>'Categories',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'choices' => $this->categoryService->getCategory()
                
            ])
            ->add('wordKey',TextType::class,[
                'label'=>'Mots clés',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('description',TextareaType::class,[
                'label'=>'Déscription',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('images',FileType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>"images",
                'multiple'=>true,
                'mapped'=>false,
                'required'=> true

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

   
}
