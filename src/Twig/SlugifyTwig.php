<?php

namespace App\Twig;

use App\Service\UtilsService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class SlugifyTwig extends AbstractExtension
{

   
    private $twig;
    private $utils;



    public function __construct(Environment $twig, UtilsService $utils)
    {
       
        $this->twig = $twig;
        $this->utils=$utils;
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('slugify', [$this, 'getSlug'], ['is_safe' => ['html']])
        ];
    }

    public function getSlug($text)
    {
        return $this->twig->render('partials/slugTwig.html.twig', [
            'text'=> $this->utils::getSlug($text)
        ]);
    }
}
