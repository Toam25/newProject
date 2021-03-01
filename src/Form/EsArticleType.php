<?php

namespace App\Form;

use App\Entity\EsArticle;
use App\Service\CategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EsArticleType extends AbstractType
{
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService=$categoryService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photos')
            ->add('type',ChoiceType::class,[
                'choices'=>$this->categoryService->getTypeArticle()
            ])
            ->add('sous_category',HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EsArticle::class,
        ]);
    }
}
