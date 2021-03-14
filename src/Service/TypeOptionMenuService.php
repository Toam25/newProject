<?php

namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TypeOptionMenuService extends AbstractController
{

   private $utilsService;
   public function __construct(UtilsService $utilsService)
   {
      $this->utilsService = $utilsService;
   }
   /**
    * @return  array
    */
   public function getTypeOptionMenu($typeMenu=null, $category = null, $sous_category = null)
   {   
      $utils = $this->utilsService;

  
      if($utils->getSlug($typeMenu) == "habillement"){
        $type = [
            'habillement'=>$this->getCategoryHabillement(),
        ];
      }
      elseif($utils->getSlug($typeMenu)=='accessoires'){
         $type =  [
             'accessoires'=>$this->getCategoryAccessoires()
          ];
      }
      elseif($utils->getSlug($typeMenu)=="beaute-et-bien-etre"){
        
         $type = [
            'beaute-et-bien-etre'=>$this->getCategoryBeauteEtBienEtre()
           
        ];
      }
      else{
         $type = [
            ////////informatique
            'high-tech' => $this->getCategoryHighTech(),
   
            ////////mode
            'mode' => $this->getCategoryMode(),
   
            ////////service
            'services' => $this->getCategoryServices(),
   
            //////////////maison
            'maison' => $this->getCategoryMaison()
   
         ];
      }
      
      

      if ($sous_category != null) {
         return $type[strtolower($typeMenu)][$utils->getSlug($category)]['sous_category'][$utils->getSlug($sous_category)] ?? [];
      } else if ($category != null) {
         return $type[strtolower($typeMenu)][$utils->getSlug($category)]['sous_category'] ?? [];
      }
      else if ($typeMenu!=null){
        
         return $type[strtolower($typeMenu)] ?? [];
      }

      return $type;
   }
   /**
    * return @array
    */
   public function getOption($option,string $type=""){
      $array = [];
       $utils= $this->utilsService;
      if($type==""){
         
          $categories = $this->getTypeOptionMenu();
          foreach ($categories as $keyc=> $category) {
            foreach ($category as $keys=>$sous_category) {
              foreach ($sous_category['sous_category'] as $key=>$_sous_category) {
                  if($option == $key){
  
                    return  [
                       'category'=>$keyc,
                       'sous_category'=>$sous_category['category'],
                       'name'=>$_sous_category['name'],
                       'option'=>$_sous_category['options']
                    ] ;
                  }
             }
            }
       }
      }
      else{
         $categories = $this->getTypeOptionMenu($utils->getSlug($type));
         
            foreach ($categories as $keys=>$sous_category) {
               
              foreach ($sous_category['sous_category'] as $key=>$_sous_category) {

                  if($option == $key){
                    return  [
                       'category'=>$utils->getSlug($type),
                       'sous_category'=>$sous_category['category'],
                       'name'=>$_sous_category['name'],
                       'option'=>$_sous_category['options']
                    ] ;
                  }
             }
            }
      }
      
     
      return $array;
   }
   public function getCategoryMaison()
   {
      return [
         "bricolage" => [
            'category' => 'Bricolage',
            'sous_category' => [
               "outillages" => [

                  'name' => 'Outillages',
                  'options' => [
                     'Outils à main', 'Accessoires', 'Outils divers', 'Outils multifonctions', 'Outils éléctriques', 'Pièces détachées'
                  ]
               ]
            ]
         ],
         "professionnel" => [
            'category' => 'Professionnel',
            'sous_category' => [
               "outillages-pro" => [
                  'name' => 'Outillages Pro',

                  'options' => [
                     'Outillage à main', 'Outillage éléctroportatif', 'Machines equipements', 'Eléctricité', 'Quicallerie'
                  ]
               ]

            ]
         ],
         "jardinage" => [
            'category' => 'Jardinage',

            'sous_category' => [
               "outils-de-jardin" => [
                  'name' => 'Outils de jardin',

                  'options' => [
                     'Outils de jardin'
                  ]
               ]

            ]
         ]

      ];
   }
   public function getCategoryBeauteEtBienEtre()
   {
      return  [
         "parfumeries" =>
         [
            "category" => "Parfumeries",
            'sous_category' => [
               'parfumeries' => [

                  'name' => 'Parfumeries',
                  'options' => [
                     'Eaux de toilettes', 'Déodorants homme', 'Déodorants femme', 'Parfums homme', 'Parfum femme', 'Eaux de Cologne'
                  ]
               ]

            ]
         ],
         "beaute-bio" =>
         [
            "category" => "Beauté bio",
            'sous_category' => [
               'beaute-bio' => [

                  'name' => 'Beauté bio',
                  'options' => [
                     'Huile essentielle', 'Huile végétale', 'Huile massage', 'Produits naturels amincissant'
                  ]
               ]

            ]
         ],
         "cosmetiques" =>
         [
            "category" => "Cosmétiques",
            'sous_category' => [
               'soins-de-corps-et-visage' => [
                  'name' => 'Soins de corps et visage',
                  'options' => [
                     'Crayons et eyeliners', 'Mascaras', 'Ombres à paupières', 'Palettes et coffrets', 'Blush et poudres', 'Fonds de teint et BB crème', 'Rouges à lèvres', 'Primers et correcteurs', 'pilateurs sourcils', 'Dépilatoires', 'Accessoires maquillage ', 'Anti-rides et anti-âges', 'Masques et gommages', 'Nettoyants et démaquillants', 'Purifiants et matifiants', 'Soins des lèvres et des yeux', 'Crèmes', 'Crème solaire', 'Lotions', 'Baume', 'Emulsions', 'Huiles pour la peau', 'produits de bronzage', 'produits pour le rasage', 'Produits d’hygiène dentaire et buccale', 'Produits d’hygiène  intime externe', 'Bain & douche', 'Savons de toilette', 'Soins hydratants et nourrissants'
                  ]
               ],
               'soins-des-ongles' => [

                  'name' => 'Soins des ongles',
                  'options' => [
                     'Base protectrice Clean', 'Vernis', 'Dissolvant', 'Faux ongles', 'Lime'
                  ]
               ],
               'soins-des-cheveux' => [

                  'name' => 'Soins des cheveux',
                  'options' => [
                     'Pack de produits', 'Vaporisateurs', 'Fixateurs', 'Shampooings', 'Après-shampooings', 'Masques', 'Gel', 'Colorants', 'Produit pour l\'ondulation', 'Produit de coiffage', 'Huiles', 'Soins traitements'
                  ]
               ]

            ]
         ]
      ];
   }
   public function getCategoryAccessoires()
   {
      return  [
         "accessoires-des-cheveux" =>
         [
            "category" => "Accessoires des cheveux",
            'sous_category' => [
               'accessoires-des-cheveux' => [
                  'name' => 'Accessoires des cheveux',
                  'options' => [
                     'Boucle d’oreilles', 'Colliers', 'Pendentif', 'Gourmette', 'Alliances', 'Boutons de manchette', 'Bracelets', 'Bague', 'Parure de bague', 'Chaine', 'Sautoir', 'Montres'
                  ]
               ]

            ]
         ],
         "bijoux-et-montres" =>
         [
            "category" => "Bijoux et montres",
            'sous_category' => [
               'bijoux-et-montres' => [

                  'name' => 'Bijoux et montres',
                  'options' => [
                     "Communication", "Promotion", "Publicité", "Contenus", "Vogue"
                  ]
               ]

            ]
         ],
         "sacs-et-maroquineries" =>
         [
            "category" => "Sacs et maroquineries",
            'sous_category' => [
               'sacs-et-maroquineries' => [

                  'name' => 'Sacs et maroquineries',
                  'options' => [
                     'Sac à main', 'Sac à dos', 'Sac de voyage', 'Sac bandoulière ', 'Portefeuilles et porte-cartes', 'Cabas', 'Pochettes', 'Sacs bowling', 'Sacs portés épaule '
                  ]
               ]

            ]
         ],
         "fashion-plus" =>
         [
            "category" => "Fashion plus",
            'sous_category' => [
               'fashion-plus' => [

                  'name' => 'Fashion plus',
                  'options' => [
                     'Ceinture', 'Gants', 'Casquettes', 'Chapeaux', 'Echarpes', 'Foulards', 'Bonnets', 'Headband', 'Cravates ', 'Lunettes'
                  ]
               ]

            ]
         ]
      ];
   }
   public function getCategoryServices()
   {
      return  [
         "marketing" =>
         [
            "category" => "Marketing",
            'sous_category' => [
               'visuel' => [

                  'name' => 'Visuel',
                  'options' => [
                     "Communication", "Promotion", "Publicité", "Contenus", "Vogue"
                  ]
               ]

            ]
         ],
         "emplois" =>
         [
            "category" => "Emplois",
            'sous_category' => [
               "emplois" => [
                  'name' => 'Emplois',
                  'options' => [
                     "Offres d'emplois",
                     "Demande d'emplois",
                     "Profils"
                  ]
               ]
            ]
         ],
         "formations" => [
            "category" => "Formations",

            'sous_category' => [
               "formation-en-ligne" => [
                  'name' => 'Formation en ligne',
                  'options' => [
                     "BTP"
                  ]
               ]

            ]
         ],
         "educations" => [
            "category" => "Educations",

            'sous_category' => [
               "etablissement" => [

                  'name' => 'Etablissement',
                  'options' => [
                     "Général", "Technique", "Spécialisé", "Etablissement public", "Astuce pedagogie"
                  ]
               ]
            ]
         ],
         "sante-hygiene" => [
            "category" => "Santé-hygiène",
            'sous_category' => [
               "sante-hygiene" => [
                  'name' => 'Santé/Hygiène',
                  'options' => [
                     "Astuce", "Traîtement", "Conseil", "Cabinet médical", "Pharmacie", "Homeopharma"
                  ]
               ]

            ]
         ]
      ];
   }
   public function getCategoryMode()
   {
      return [
         "habillement-homme" =>
         [
            "category" => "Habillement homme",
            'sous_category' => [

               "vetement-homme" => [
                  'name' => 'Vêtement homme',
                  'options' => [
                     'Chemise', 'Jeans', 'T-shirts', 'Polos', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Costumes', 'Vestes', 'Pantalons', 'Short', 'Bermudas', 'Tenues de Sports', 'Vetement de nuit', 'Impermeable', 'Maillots de Bains', 'Chaussettes', 'Ensemble', 'Jogging', 'Blousons'
                  ]
               ],
               "chaussure-homme" => [
                  'name' => 'Chaussure homme',
                  'options' => [
                     'Derbies', 'Chaussures de ville', 'Slippers', 'Derbie', 'Baskets', 'Bottes', 'Boots', 'Chaussons', 'Chaussures bateau', 'Chaussures de Securité', 'Chaussures de sports', 'Espadrilles', 'Mocassins', 'Mulle', ' Sabot', 'Sandales', 'Tongs'
                  ]
               ],
               "lingeries-homme" => [
                  'name' => 'Lingeries homme',
                  'options' => [
                     'Caleçon', 'Boxeur', 'Slips', 'Short', 'Jock strap', 'Chaussette', 'Pantie'
                  ]
               ]

            ]
         ],
         "habillement-femme" =>
         [
            "category" => "Habillement femme",
            'sous_category' => [
               "vetement-femme" => [
                  'name' => 'Vêtement femme',
                  'options' => [
                     'Robe de ceremonie', 'Robe de mariée', 'Robe de fiançaille', 'Sweats-Shirts', 'Jeans', 'Cardigans', 'Chemise', 'Body', 'Blouse', 'T-shirts', 'Polos', 'Débardeur', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Tailleurs', 'Vestes', 'Blousons', 'Jupes', 'Pantalons', 'Salopettes', 'Combinaisons', 'Combi-short', 'Chaussettes', 'Collants', 'Vêtements de Grossesse', 'Vetement de nuit', 'Vetement de Sports', 'Imperméable', 'Maillots de Bains', 'Costumes', 'Ensemble', 'Jogging'
                  ]
               ],
               "chaussure-femme" => [
                  'name' => 'Chaussure femme',
                  'options' => [
                     'Ballerines ', 'Baskets', 'Bottes', 'Boots', 'Chaussons', 'Chaussures Bateau', 'Chaussures de Securité', 'Chaussures de ville', 'Derbie', 'Designer', 'Escarpins', 'Espadrilles', ' Mary Janes', 'Mocassins', 'Mulle', 'Sabot', 'Sandales', 'Sport', 'Tongs'
                  ]
               ],
               "lingeries-femme" => [
                  'name' => 'Lingeries femme',
                  'options' => [
                     'Slips', 'Dentelle côté', 'Tanga', 'Boxer', 'Accessoires', 'Bas', 'Jarretières', 'Bodys', 'Bustiers', 'corsets', 'Caracos', 'Combinaisons', 'Jupons', 'Culottes', 'Shorties', 'Strings', 'Ensembles de Lingeries', 'Lingeries Sculptantes', 'Nuisettes', 'Deshabillés', 'Vêtements Thérmiques', 'Soutiens Gorges'
                  ]
               ],
            ]
         ],
         "habillement-enfant" => [
            "category" => "Habillement enfant",

            'sous_category' => [
               "vetement-enfant" => [
                  'name' => 'Vêtement enfant',
                  'options' => [
                     'Pantalon', 'Cardigans', ' Blousons', 'Sweats-Shirts', 'Bermudas', 'Chemise', 'T-shirts', 'Polos', 'Pulls', 'Jean', 'Short', 'Bermudas', 'Salopettes', 'Ensembles', 'Maillots de bains', 'Sous - vêtements', 'Joggins', 'Imperméable'
                  ]
               ],
               "lingeries-enfant" => [
                  'name' => 'Lingeries enfant',
                  'options' => [
                     'Salopette', 'Jeans', 'Sweat-Shirt', 'Tailleur', 'Veste', 'Chemise', 'Blouses', 'T-shirts', 'Pulls', 'Débardeur', 'Gilets', 'Cardigans', 'Manteaux', 'Blousons', 'Jupes', 'Short', 'Bermudas', 'Pantalons', 'Leggins', 'Robes', 'Ensembles', 'Salopettes', 'Combinaisons', 'Combi-short', 'Sous - vêtements', 'Collants', 'Maillots de Bains', 'Tenues de Sports', 'Vêtements de nuits', 'Peignoirs', 'Imperméable'
                  ]
               ],
               "chaussure-enfant" => [
                  'name' => 'Chaussure enfant',
                  'options' => [
                     'Escarpins', 'Boots', ' Babies', 'Ballerines', 'Baskets mode', 'Bottes', 'Bottines', 'Chaussures Bateau', 'Chaussures de Sport', 'Chaussures de ville', 'Espadrilles', 'Mocassins', 'Mulle', 'Sabot', 'Sandales', 'Tongs '
                  ]
               ]

            ]
         ],
         "habillement-bebe" => [
            "category" => "Habillement bébé",
            'sous_category' => [
               "bebe-garcons" => [
                  'name' => 'Bébé garçon',
                  'options' => [
                     'Couverture Bébé', 'Peluches', 'Veste', 'Salopettes', 'Combinaison', 'Sweat-Shirt', 'Polos', 'Bodys', 'Grenoullière', 'T-shirts', 'Débardeur', 'Chemise', 'Pulls', 'Cardigans', 'Pantalons', 'Shor', 'Pyjamas', 'Ensembles', 'Chaussettes', 'Maillots de bains'
                  ]
               ],
               "bebe-fille" => [
                  'name' => 'Bébé fille',
                  'options' => [
                     'Couverture Bébé', 'Peluches', 'Vestes', 'Salopette', 'Combinaisons', 'Sweats-Shirts', 'Polos', 'Bodys', 'Grenoullère', 'T-shirts', 'Débardeur', 'Chemise  ', 'Pulls', 'Cardigans', 'Pantalons', 'Short', 'Pyjamas', 'Ensembles', 'Robes', 'Chaussettes', 'Maillots de bains'
                  ]
               ],
               "bebe-chaussure" => [
                  'name' => 'Bébé chaussure',
                  'options' => [
                     'Baskets', 'Babies', 'Bottes', 'Botillons', 'Boots', 'Chaussons', 'Sandales'
                  ]
               ]
            ]
         ]
         ,
         "accessoires-des-cheveux" =>
         [
            "category" => "Accessoires des cheveux",
            'sous_category' => [
               'accessoires-des-cheveux' => [
                  'name' => 'Accessoires des cheveux',
                  'options' => [
                     'Boucle d’oreilles', 'Colliers', 'Pendentif', 'Gourmette', 'Alliances', 'Boutons de manchette', 'Bracelets', 'Bague', 'Parure de bague', 'Chaine', 'Sautoir', 'Montres'
                  ]
               ]

            ]
         ],
         "bijoux-et-montres" =>
         [
            "category" => "Bijoux et montres",
            'sous_category' => [
               'bijoux-et-montres' => [

                  'name' => 'Bijoux et montres',
                  'options' => [
                     "Communication", "Promotion", "Publicité", "Contenus", "Vogue"
                  ]
               ]

            ]
         ],
         "sacs-et-maroquineries" =>
         [
            "category" => "Sacs et maroquineries",
            'sous_category' => [
               'sacs-et-maroquineries' => [

                  'name' => 'Sacs et maroquineries',
                  'options' => [
                     'Sac à main', 'Sac à dos', 'Sac de voyage', 'Sac bandoulière ', 'Portefeuilles et porte-cartes', 'Cabas', 'Pochettes', 'Sacs bowling', 'Sacs portés épaule '
                  ]
               ]

            ]
         ],
         "fashion-plus" =>
         [
            "category" => "Fashion plus",
            'sous_category' => [
               'fashion-plus' => [

                  'name' => 'Fashion plus',
                  'options' => [
                     'Ceinture', 'Gants', 'Casquettes', 'Chapeaux', 'Echarpes', 'Foulards', 'Bonnets', 'Headband', 'Cravates ', 'Lunettes'
                  ]
               ]

            ]
                  ],
                  "parfumeries" =>
                  [
                     "category" => "Parfumeries",
                     'sous_category' => [
                        'parfumeries' => [
         
                           'name' => 'Parfumeries',
                           'options' => [
                              'Eaux de toilettes', 'Déodorants homme', 'Déodorants femme', 'Parfums homme', 'Parfum femme', 'Eaux de Cologne'
                           ]
                        ]
         
                     ]
                  ],
                  "beaute-bio" =>
                  [
                     "category" => "Beauté bio",
                     'sous_category' => [
                        'beaute-bio' => [
         
                           'name' => 'Beauté bio',
                           'options' => [
                              'Huile essentielle', 'Huile végétale', 'Huile massage', 'Produits naturels amincissant'
                           ]
                        ]
         
                     ]
                  ],
                  "cosmetiques" =>
                  [
                     "category" => "Cosmétiques",
                     'sous_category' => [
                        'soins-de-corps-et-visage' => [
                           'name' => 'Soins de corps et visage',
                           'options' => [
                              'Crayons et eyeliners', 'Mascaras', 'Ombres à paupières', 'Palettes et coffrets', 'Blush et poudres', 'Fonds de teint et BB crème', 'Rouges à lèvres', 'Primers et correcteurs', 'pilateurs sourcils', 'Dépilatoires', 'Accessoires maquillage ', 'Anti-rides et anti-âges', 'Masques et gommages', 'Nettoyants et démaquillants', 'Purifiants et matifiants', 'Soins des lèvres et des yeux', 'Crèmes', 'Crème solaire', 'Lotions', 'Baume', 'Emulsions', 'Huiles pour la peau', 'produits de bronzage', 'produits pour le rasage', 'Produits d’hygiène dentaire et buccale', 'Produits d’hygiène  intime externe', 'Bain & douche', 'Savons de toilette', 'Soins hydratants et nourrissants'
                           ]
                        ],
                        'soins-des-ongles' => [
         
                           'name' => 'Soins des ongles',
                           'options' => [
                              'Base protectrice Clean', 'Vernis', 'Dissolvant', 'Faux ongles', 'Lime'
                           ]
                        ],
                        'soins-des-cheveux' => [
         
                           'name' => 'Soins des cheveux',
                           'options' => [
                              'Pack de produits', 'Vaporisateurs', 'Fixateurs', 'Shampooings', 'Après-shampooings', 'Masques', 'Gel', 'Colorants', 'Produit pour l\'ondulation', 'Produit de coiffage', 'Huiles', 'Soins traitements'
                           ]
                        ]
         
                     ]
                  ]
         
      ];
   }
   public function getCategoryHabillement()
   {
      return  [
         "habillement-homme" =>
         [
            "category" => "Habillement homme",
            'sous_category' => [

               "vetement-homme" => [
                  'name' => 'Vêtement homme',
                  'options' => [
                     'Chemise', 'Jeans', 'T-shirts', 'Polos', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Costumes', 'Vestes', 'Pantalons', 'Short', 'Bermudas', 'Tenues de Sports', 'Vetement de nuit', 'Impermeable', 'Maillots de Bains', 'Chaussettes', 'Ensemble', 'Jogging', 'Blousons'
                  ]
               ],
               "chaussure-homme" => [
                  'name' => 'Chaussure homme',
                  'options' => [
                     'Derbies', 'Chaussures de ville', 'Slippers', 'Derbie', 'Baskets', 'Bottes', 'Boots', 'Chaussons', 'Chaussures bateau', 'Chaussures de Securité', 'Chaussures de sports', 'Espadrilles', 'Mocassins', 'Mulle', ' Sabot', 'Sandales', 'Tongs'
                  ]
               ],
               "lingeries-homme" => [
                  'name' => 'Lingeries homme',
                  'options' => [
                     'Caleçon', 'Boxeur', 'Slips', 'Short', 'Jock strap', 'Chaussette', 'Pantie'
                  ]
               ]

            ]
         ],
         "habillement-femme" =>
         [
            "category" => "Habillement femme",
            'sous_category' => [
               "vetement-femme" => [
                  'name' => 'Vêtement femme',
                  'options' => [
                     'Robe de ceremonie', 'Robe de mariée', 'Robe de fiançaille', 'Sweats-Shirts', 'Jeans', 'Cardigans', 'Chemise', 'Body', 'Blouse', 'T-shirts', 'Polos', 'Débardeur', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Tailleurs', 'Vestes', 'Blousons', 'Jupes', 'Pantalons', 'Salopettes', 'Combinaisons', 'Combi-short', 'Chaussettes', 'Collants', 'Vêtements de Grossesse', 'Vetement de nuit', 'Vetement de Sports', 'Imperméable', 'Maillots de Bains', 'Costumes', 'Ensemble', 'Jogging'
                  ]
               ],
               "chaussure-femme" => [
                  'name' => 'Chaussure femme',
                  'options' => [
                     'Ballerines ', 'Baskets', 'Bottes', 'Boots', 'Chaussons', 'Chaussures Bateau', 'Chaussures de Securité', 'Chaussures de ville', 'Derbie', 'Designer', 'Escarpins', 'Espadrilles', ' Mary Janes', 'Mocassins', 'Mulle', 'Sabot', 'Sandales', 'Sport', 'Tongs'
                  ]
               ],
               "lingeries-femme" => [
                  'name' => 'Lingeries femme',
                  'options' => [
                     'Slips', 'Dentelle côté', 'Tanga', 'Boxer', 'Accessoires', 'Bas', 'Jarretières', 'Bodys', 'Bustiers', 'corsets', 'Caracos', 'Combinaisons', 'Jupons', 'Culottes', 'Shorties', 'Strings', 'Ensembles de Lingeries', 'Lingeries Sculptantes', 'Nuisettes', 'Deshabillés', 'Vêtements Thérmiques', 'Soutiens Gorges'
                  ]
               ],
            ]
         ],
         "habillement-enfant" => [
            "category" => "Habillement enfant",

            'sous_category' => [
               "vetement-enfant" => [
                  'name' => 'Vêtement enfant',
                  'options' => [
                     'Pantalon', 'Cardigans', ' Blousons', 'Sweats-Shirts', 'Bermudas', 'Chemise', 'T-shirts', 'Polos', 'Pulls', 'Jean', 'Short', 'Bermudas', 'Salopettes', 'Ensembles', 'Maillots de bains', 'Sous - vêtements', 'Joggins', 'Imperméable'
                  ]
               ],
               "lingeries-enfant" => [
                  'name' => 'Lingeries enfant',
                  'options' => [
                     'Salopette', 'Jeans', 'Sweat-Shirt', 'Tailleur', 'Veste', 'Chemise', 'Blouses', 'T-shirts', 'Pulls', 'Débardeur', 'Gilets', 'Cardigans', 'Manteaux', 'Blousons', 'Jupes', 'Short', 'Bermudas', 'Pantalons', 'Leggins', 'Robes', 'Ensembles', 'Salopettes', 'Combinaisons', 'Combi-short', 'Sous - vêtements', 'Collants', 'Maillots de Bains', 'Tenues de Sports', 'Vêtements de nuits', 'Peignoirs', 'Imperméable'
                  ]
               ],
               "chaussure-enfant" => [
                  'name' => 'Chaussure enfant',
                  'options' => [
                     'Escarpins', 'Boots', ' Babies', 'Ballerines', 'Baskets mode', 'Bottes', 'Bottines', 'Chaussures Bateau', 'Chaussures de Sport', 'Chaussures de ville', 'Espadrilles', 'Mocassins', 'Mulle', 'Sabot', 'Sandales', 'Tongs '
                  ]
               ]

            ]
         ],
         "habillement-bebe" => [
            "category" => "Habillement bébé",
            'sous_category' => [
               "bebe-garcons" => [
                  'name' => 'Bébé garçon',
                  'options' => [
                     'Couverture Bébé', 'Peluches', 'Veste', 'Salopettes', 'Combinaison', 'Sweat-Shirt', 'Polos', 'Bodys', 'Grenoullière', 'T-shirts', 'Débardeur', 'Chemise', 'Pulls', 'Cardigans', 'Pantalons', 'Shor', 'Pyjamas', 'Ensembles', 'Chaussettes', 'Maillots de bains'
                  ]
               ],
               "bebe-fille" => [
                  'name' => 'Bébé fille',
                  'options' => [
                     'Couverture Bébé', 'Peluches', 'Vestes', 'Salopette', 'Combinaisons', 'Sweats-Shirts', 'Polos', 'Bodys', 'Grenoullère', 'T-shirts', 'Débardeur', 'Chemise  ', 'Pulls', 'Cardigans', 'Pantalons', 'Short', 'Pyjamas', 'Ensembles', 'Robes', 'Chaussettes', 'Maillots de bains'
                  ]
               ],
               "bebe-chaussure" => [
                  'name' => 'Bébé chaussure',
                  'options' => [
                     'Baskets', 'Babies', 'Bottes', 'Botillons', 'Boots', 'Chaussons', 'Sandales'
                  ]
               ]
            ]
         ]
      ];
   }
   public function  getCategoryHighTech()
   {
      return  [
         "informatique" =>
         [
            "category" => "Informatique",
            'sous_category' => [
               "materiels-informatique" => [

                  'name' => 'Matériels informatique',
                  'options' => [
                     "Communication", "Promotion", "Publicité", "Contenus", "Vogue"
                  ]

               ],
               "diagnostiques" => [

                  'name' => 'Diagnostique',
                  'options' => [
                     "Communication", "Promotion", "Publicité", "Contenus", "Vogue"
                  ]
               ]
            ]
         ],
         "systeme-domotique" =>
         [
            "category" => "Système domotique",
            'sous_category' => [
               "systeme-domotique" => [

                  'name' => "Système domotique",
                  'options' => [
                     "Communication", "Promotion", "Publicité", "Contenus", "Vogue"
                  ]
               ]
            ]
         ],
         "impression" =>
         [
            "category" => "Impression",
            'sous_category' => [
               "impression" => [
                  'name' => "Impression",
                  'options' => [
                     "Communication", "Promotion", "Publicité", "Contenus", "Vogue"
                  ]
               ]
            ]
         ],
         "images-et-son" =>
         [
            "category" => "Images et son",
            'sous_category' => [
               "Tv" => [
                  'name' => 'Tv',
                  'options' => [
                     "Offres d'emplois",
                     "Demande d'emplois",
                     "Profils"
                  ]
               ],
               "video-projecteur" => [
                  'name' => 'Video projecteur',
                  'options' => [
                     "Offres d'emplois",
                     "Demande d'emplois",
                     "Profils"
                  ]
               ],
               "son" => [
                  'name' => 'Son',
                  'options' => [
                     "Offres d'emplois",
                     "Demande d'emplois",
                     "Profils"
                  ]
               ],
               "photos-et-camera" => [
                  'name' => 'Photos et caméra',
                  'options' => [
                     "Offres d'emplois",
                     "Demande d'emplois",
                     "Profils"
                  ]
               ],
               "tous-les-accessoires" => [
                  'name' => 'Tous les accessoires',
                  'options' => [
                     "Offres d'emplois",
                     "Demande d'emplois",
                     "Profils"
                  ]
               ]
            ]
         ]
      ];
   }
}
