<?php

namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TypeOptionMenuService extends AbstractController
{


   public function __construct()
   {
   }
   public static function getTypeOptionMenu($typeMenu)
   {
      $type = [
         'services' => [
            [
               "category" => "Marketing",
               'sous_category' => [
                  'Visuel'
               ]
            ],
            [
               "category" => "Emplois",
               'sous_category' => []
            ],
            [
               "category" => "Formations",
               'sous_category' => [
                  'Formation en ligne'
               ]
            ],
            [
               "category" => "Educations",
               'sous_category' => [
                  'Etablissement'
               ]
            ],
            [
               "category" => "Santé-hygiène",
               'sous_category' => [
                  'Santé/Hygiène'
               ]
            ]
         ]
      ];

      return $type[strtolower($typeMenu)] ?? "";

   }
}
