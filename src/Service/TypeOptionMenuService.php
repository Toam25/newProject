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
               ],
               'options' => []
            ],
            [
               "category" => "Emplois",
               'sous_category' => [],
               'options' => []
            ],
            [
               "category" => "Formations",
               'sous_category' => [
                  'Formation en ligne'
               ],
               'options' => []
            ],
            [
               "category" => "Educations",
               'sous_category' => [
                  'Etablissement'
               ],
               'options' => []
            ],
            [
               "category" => "Santé-hygiène",
               'sous_category' => [
                  'Santé/Hygiène'
               ],
               'options' => []
            ]
         ],

         'maison' => [
            [
               'category' => 'Bricolage',
               'sous_category' => [
                  [
                     'name' => 'Outillages',
                     'options' => [
                        'Outils à main', 'Accessoires', 'Outils divers', 'Outils multifonctions', 'Outils éléctriques', 'Pièces détachées'
                     ]
                  ]

               ]
            ],
            [
               'category' => 'Professionnel',
               'sous_category' => [
                  [
                     'name' => 'Outillages Pro',
                     'options' => [
                        'Outillage à main', 'Outillage éléctroportatif', 'Machines equipements', 'Eléctricité', 'Quicallerie'
                     ]
                  ]

               ]
            ],
            [
               'category' => 'Jardinage',
               'sous_category' => [
                  [
                     'name' => 'Outils de jardin',
                     'options' => [
                        'Outils de jardin'
                     ]
                  ]

               ]
            ]

         ]

      ];

      return $type[strtolower($typeMenu)] ?? "";
   }

   public static function getAddButton($category)
   {
   }
}
