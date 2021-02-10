<?php

namespace App\Twig;

use App\Entity\Boutique;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\CategoryService;
use Twig\Environment;

class ListOptionTwig extends AbstractExtension
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
            new TwigFunction('getListOption', [$this, 'getListOption'], ['is_safe' => ['html']])
        ];
    }

    public function getListOption(array $list, $category)
    {
       
        return $this->twig->render('partials/list_categories.html.twig', [
            'listCategories' => $list[$category] ?? ""
        ]);
    }
}
