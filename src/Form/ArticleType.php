<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\BoutiqueRepository;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ArticleType extends AbstractType
{

    private $category;
    public function __construct(CategoryService $categoryService, Security $security, CategoryRepository $categoryRepository, BoutiqueRepository $boutiqueRepository)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $security->getUser()]);
        $category = $categoryRepository->findBy(['boutique' => $boutique, 'type' => 'product']);
        $this->category = $categoryService->getCategoryFormat($category, true);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control'
                ]

            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('price_promo', NumberType::class, [
                'label' => 'Prix promo',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('referency', TextType::class, [
                'label' => "reference",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('slide', ChoiceType::class, [
                'label' => 'Ajouter dans le slide',
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ]
            ])
            ->add('externalDetail', TextType::class, [
                'label' => "Lien externe",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('price_global', NumberType::class, [
                'label' => 'Prix en gros',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('quantity', TextType::class, [
                'label' => 'Stock',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('sous_category', HiddenType::class)
            ->add('promo', ChoiceType::class, [
                'label' => 'Promotion',
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Normal' => 'Normal',
                    'Promotion' => 'Promo',
                    'new' => 'New',
                    'vendu' => 'Vendu'
                ]
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('category', HiddenType::class)
            ->add('type', ChoiceType::class, [
                'label' => 'type',
                'choices' => $this->category,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // ->add('type', EntityType::class, [
            //     'label' => 'Category',
            //     'class' => Category::class,
            //     'attr' => [
            //         'class' => 'form-control'
            //     ],
            //     'query_builder' => function (CategoryRepository $categoryRepository) {
            //         return $categoryRepository->createQueryBuilder('category')
            //             ->andWhere('category.boutique = :boutique')
            //             ->setParameter('boutique', $this->boutique);
            //     }
            // ])
            ->add('wordKey', TextType::class, [
                'label' => 'Mots clés',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Déscription',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('images', FileType::class, [
                'attr' => [
                    'class' => 'form-control file'
                ],
                'label' => "images",
                'multiple' => true,
                'mapped' => false,
                'required' => false

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

    public function getCategoryArticle(): ?array
    {
        $category = $this->category;
        dd($category);
        $output = array();
        for ($i = 0; $i < sizeof($category); $i++) {
            $output[$category[$i]] = $category[$i];
        }
        return $output;
    }
}
