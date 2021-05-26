<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AutorisationServices extends AbstractController
{

    public function getAccess($offer)
    {
        if ($offer == 'vip') {
            return [];
        } else if ($offer = "premium") {
            return [];
        }

        return   [
            'product' => [
                'max' => 10,
                'link' => "none"
            ],
            'blog' => [
                'max' => 2,
                'index' => false
            ],
            'linkEx' => false,
            'selection' => [
                'max' => 3
            ],
            'page' => 'false',
            'referency' => [
                'max' => 4
            ],
            'notification' => false,
            'message' => false,
            'shop' => false,
        ];
    }
}

// 1-Afaka mampiditra produit 10 max fa tsisy lien makany amin'ny produit siteny
// Produit vitrine fotsiny zany
// 2-Afaka manao blog fa article 2  (actualité ihany no mandé)
// 3-Tsy afaka mampiditra ny lien siteny
// 4-Nos selections : Anaky 3 ihany no afaka ampidiriny
// 5-Tsy manana espace page
// 6-Nos references : 4 ihany
// 7-Tsy misy boite message sy notification
// 8-tsy mipoitra amin ny galerie marchande
// 9- tsy mipoitra any amin ny acceuil ny blogany--Gratuit : Manome Don