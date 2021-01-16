<?php

namespace App\Twig;

use App\Entity\Boutique;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\CategoryService;
use Twig\Environment;

class CategorieTwig extends AbstractExtension
{

    private $twig;
    private $categoryService;


    public function __construct(CategoryService $categoryService, Environment $twig)
    {
        $this->categoryService = $categoryService;
        $this->twig = $twig;
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('categorie', [$this, 'getCategorie'], ['is_safe' => ['html']])
        ];
    }

    public function getCategorie(Boutique $boutique)
    {

        $categories = $this->categoryService->getCategoryForArticle($boutique);
        return $this->twig->render('partials/categorie.html.twig', [
            'categories' => $categories
        ]);
    }
}
