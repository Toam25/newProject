<?php

namespace App\Form;

use App\Entity\Blog;
use App\Repository\BoutiqueRepository;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class BlogType extends AbstractType
{

    private $category;
    public function __construct(CategoryService $categoryService, Security $security, CategoryRepository $categoryRepository, BoutiqueRepository $boutiqueRepository)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $security->getUser()]);
        $category = $categoryRepository->findBy(['boutique' => $boutique, 'type' => 'blog']);
        $this->category = $categoryService->getCategoryFormat($category, true);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre*',
                'attr' => [
                    'class' => "form-control",
                    'required' => true
                ]
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Categorie*',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ],
                'choices' => $this->category
            ])
            ->add('resume', TextType::class, [
                'label' => 'Résumé*',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ]
            ])

            ->add('keywords', TextType::class, [
                'label' => 'Mots clé*',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ]
            ])

            ->add('metaDescription', TextareaType::class, [
                'label' => 'Meta déscription*',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien*',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true

                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Déscription*',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ],
                'input_sync' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
