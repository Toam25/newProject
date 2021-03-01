<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class SlugifyTwig extends AbstractExtension
{

   
    private $twig;



    public function __construct(Environment $twig)
    {
       
        $this->twig = $twig;
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('slugify', [$this, 'getSlug'], ['is_safe' => ['html']])
        ];
    }

    public function getSlug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $this->twig->render('partials/slugTwig.html.twig', [
            'text'=>$text
        ]);
    }
}
