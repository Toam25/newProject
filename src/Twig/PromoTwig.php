<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class PromoTwig extends AbstractExtension
{

    private $twig;

    public function __construct(Environment $twig)
    {

        $this->twig = $twig;
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('promo', [$this, 'getPromo'], ['is_safe' => ['html']])
        ];
    }

    public function getPromo(string $promo, $price, $price_promo, $detail = null)
    {

        $ispromo = "";

        $old_price = $price;
        if ($promo == "Vendu") {
            $ispromo = "Vendu";
        }
        if ($promo == "New") {
            $ispromo = "New";
        }

        if ($promo == "Promo") {
            $price = $price != 0 ? $price : 1;
            $remise = ($price_promo - $price) / $price * 100;
            $ispromo = $promo . " " . round($remise, 2) . ' %';
        }



        if ($detail != null) {
            $promo = ($promo != 'Normal') ? "<span class='promotion " . $promo . "'>" . $ispromo . "</span>" : '';
        } else {


            $promo = ($promo != 'Normal') ? "<div class='ribbon ribbon-top-right  " . $promo . "' ><span>" . $ispromo . "</span></div>" : '';
        }

        return $this->twig->render('partials/promo.html.twig', [
            'promo' => $promo
        ]);
    }
}
