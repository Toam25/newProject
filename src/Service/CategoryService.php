<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\Query\AST\Functions\LowerFunction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryService
{

   static $category;
   private $articleRepository;
   private $typeBoutique;

   public function __construct(ArticleRepository $articleRepository)
   {
      $this->articleRepository = $articleRepository;
      $this->typeBoutique = "high-tech";
   }
   public function getCategoryForArticle($boutique)
   {
      $list = [];

      $article = $this->articleRepository->findAllArticleByBoutique($boutique);
      $allCategory = $this->getAllCategory($boutique->getType());

      for ($i = 0; $i < sizeof($article); $i++) {
         if (!in_array($article[$i]->getCategory(), $list)) {
            foreach ($allCategory as $values) {
               foreach ($values as $key => $value) {
                  if (in_array($article[$i]->getCategory(), $value)) {
                     $is_exist = false;
                     foreach ($list as $keylist => $valuelist) {
                        if (array_key_exists($key, $valuelist)) {
                           $is_exist = true;
                        }
                     }
                     if ($is_exist) {
                        if (!in_array($article[$i]->getCategory(), $list[$keylist][$key])) {
                           array_push($list[$keylist][$key], $article[$i]->getCategory());
                        }
                     } else {
                        array_push($list, [$key => [$article[$i]->getCategory()]]);
                     }
                  }
               }
            }
         }
      }
      $marque = [];

      for ($i = 0; $i < sizeof($article); $i++) {
         if (!in_array($article[$i]->getMarque(), $marque)) {
            array_push($marque, $article[$i]->getMarque());
         }
      }

      return [
         'marque' => "", //$this->htlmMarque($marque),
         'categorie' => $this->htlmCategory($list)
      ];
   }
   public function getCategory()
   {
      $type = strtolower($this->typeBoutique);
      if (in_array($type, ['high-tech', 'art malagasy', 'mode'])) {
         return $this->getAllCategory($this->typeBoutique);
      } else {
         return [];
      }
   }
   public function getAllCategory($type)
   {
      $array = [
         'high-tech' => [
            'Téléphone' => $this->getAttribut('Téléphone'),
            'Accessoires' => $this->getAttribut('Accessoires'),
            'Tv' => $this->getAttribut('TV'),
            'Video projecteur' => $this->getAttribut('Video projecteur'),
            'Son' => $this->getAttribut('Son'),
            'Phone et Caméra' => $this->getAttribut('Phone et caméra'),
            'Tous les accessoire' => $this->getAttribut('Tous les accessoires'),
            'Matériels Informatiques' => $this->getAttribut('Matériel informatiques'),
            'Diagnostiques' => $this->getAttribut('Diagnostiques'),
            'Systeme domotique' => $this->getAttribut('Système domotique'),
            'Impression' => $this->getAttribut('Impression'),
         ]

      ];


      return $array[strtolower($type)];
   }
   public function getAttribut($categorie_menu)
   {
      $option = [];
      $listOption = [];
      //telefonie
      if ($categorie_menu == "Accessoires") {

         $listOption = ['Coques', 'Batteries, Batteries externes', 'Ecouteurs', 'Enceintes bluetooth', 'Chargeurs', 'Oreillette bluetooth', 'Kits mains libres', 'Protection écran', 'Carte mémoire', 'Casque'];
      }

      if ($categorie_menu == "Téléphone") {
         $listOption = ['Téléphone fixe', 'Téléphone avec touche', 'Smartphone', 'I-phone'];
      }
      //image et son
      if ($categorie_menu == "TV") {
         $listOption = ['TV LED-LCD', 'TV 4K-UHD', 'Support TV', 'TV connectée', 'Smart TV'];
      }
      if ($categorie_menu == "Video projecteur") {

         $listOption = ['HD Ready', 'Full HD', '4K/UHD', 'Accessoires'];
      }
      if ($categorie_menu == "Son") {

         $listOption = ['Casque auto', 'Enceintes bleutooth, MP3, MP4', 'Radio', 'Dictaphone', 'Hifi', 'Bare de son'];
      }
      if ($categorie_menu == "Phone et caméra") {

         $listOption = ['Flash photo', 'Filtre', 'Caméscope caméra', 'Objectif reflex', 'Objectif caméra', 'GoPro', 'Autre'];
      }
      if ($categorie_menu == "Tous les accessoires") {

         $listOption = ['Câble et connectique', 'Accessoires audio et video', 'Accessoires photos', 'Accessoires caméra'];
      }
      //informatique
      if ($categorie_menu == "Matériels informatiques") {

         $listOption = ['Ordinateurs de bureau', 'ordinateurs portables', 'Tablette', 'Univers gaming', 'Composants - périférique', 'Stockage', 'Réseaux'];
      }
      if ($categorie_menu == "Diagnostiques") {

         $listOption = ['Hardware', 'Software'];
      }
      //impression
      if ($categorie_menu == "Impression") {

         $listOption = ['Imprimante jet d\'encre', 'Imprimante laser', 'Scaner', 'Cartouches', 'Toners'];
      }

      if ($categorie_menu == "Système domotique") {

         $listOption = ['Motorisation', 'portails et volets', 'Accessoires', 'Interphone video', 'Alarme-Détecteur', 'Caméra de surveillance', 'Sécurité  incendie'];
      }

      foreach ($listOption as $k => $v) {
         $option[$v] = $v;
      }


      return $option;
   }
   private function htlmCategory(array $list)
   {

      $html = "<div>";
      $html .= "<label for='search' class=' '>Recherche</label>";
      $html .= "<input id='search' type='text' placeholder='Recherche' name='q' class='form-control' />";
      $html .= "<label for='min_price ' class=' '>Prix</label>";
      $html .= "<div class='containt_price '>";
      $html .= "<input type='number' placeholder='min' name='min_price' class='form-control' />";
      $html .= "<input type='number' placeholder='max' name='max_price' class='form-control'/>
       </div>";

      $html .= "</div>";
      $html .= "<ul>";
      for ($i = 0, $j = 0; $i < sizeof($list); $i++) {
         foreach ($list[$i] as $keys => $values) {
            $html .= "<label for='category" . $j . "' class=' form-check-label '>" . ucfirst(mb_strtolower($keys, 'UTF-8')) . "</label>";
            $html .= "<ul class=''>";
            foreach ($values as $key => $value) {
               $j++;
               $html .= "<li class=''>";
               $html .= "<input id='category" . $j . "' class='' type='checkbox' name='category[]' value='" . $value . "'><label for='category" . $j . "'class='form-check-label'>" . ucfirst(mb_strtolower($value, 'UTF-8')) . "</label>";
               $html .= "<li>";
            }
            $j++;
            $html .= "</ul>";
         }
      }
      $html .= "</ul>";
      return $html;
   }

   public function htlmMarque(array $marque)
   {
      if (sizeof($marque) > 0) {
         $html = "
        <label class=''>Marques :</label>
        <div>
        <ul>
        ";
         $j = 0;
         foreach ($marque as  $value) {
            $html .= "<li>";
            $html .= "<input id='marque" . $j++ . "' class='' type='checkbox' name='marque[]' value='" . $value . "'><label for='marque" . $j . "'class='form-check-label'>" . ucfirst(mb_strtolower($value, 'UTF-8')) . "</label>";
            $html .= "</li>";
         }
         $html .= "
        </ul>
        </div>";
      }
      return $html;
   }
   private  $type = [
      'Chemise', 'Jeans', 'Barettes', 'Bandeau', 'T-shirts', 'Polos', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Costumes', 'Vestes', 'Pantalons', 'Short', 'Bermudas', 'Tenues de Sports', 'Vetement de nuit', 'Impermeable', 'Maillots de Bains', 'Chaussettes', 'Ensemble', 'Jogging', 'Blousons', 'Derbies', 'Chaussures de ville', 'Slippers', 'Derbie', 'Basket', 'Bottes', 'Boots', 'Chaussons', 'Chaussures bateau', 'Chaussures de Securité', 'Chaussures de sports', 'Espadrilles', 'Mocassins', 'Mulle', 'Sabot', ' Sandales', 'Tongs', 'Caleço', 'Boxeur', 'Slips', 'Shorty', 'Jock strap', 'Chaussette', 'Pantie', 'Robe de ceremonie', 'Robe de mariée', 'Robe de fiançaille', 'Cardigans', 'Body', 'Blouse', 'Débardeur', 'Tailleurs ', 'Jupes', 'Salopettes', 'Short et Bermudas', 'Leggins', 'Robes', 'Combi-short', 'Collants', 'Vêtements de Grossess', 'Vêtement de nuit', 'Vêtement de Sports', 'Imperméable', 'Maillots de Bains', 'Ballerines', 'Baskets', 'Botte', 'Dentelle côté', 'Tanga', 'Boxer',  'Accessoires', 'Bas', 'Jarretières', 'Bodys', 'Bustiers', 'Corsets', 'Caracos', 'Combinaisons', 'Jupons', 'Culottes', 'Shorties', 'Strings', 'Ensembles de Lingeries', 'Lingeries', 'Nuisettes', 'Deshabillés', 'Vêtements Thérmiques', 'Soutiens Gorges', 'Pantalon', 'Cardigans', 'Jean', 'Bermuda', 'Sous vêtements', 'Joggins', 'Salopette', 'Sweat-Shirt', 'Tailleur', 'Escarpins', 'Babies', 'Baskets mode', 'Couverture Bébé', 'Peluches', 'Veste', 'Salopettes', 'Combinaison', 'Sweat-Shirt', 'Grenoullière', 'Pyjamas', 'Couverture Bébé', 'Botillon', 'Sac à main', 'Sac à dos', 'Sac de voyage', 'Sac bandoulière', 'Portefeuilles et porte-cartes', 'Cabas', 'Pochettes', 'Sacs portes épaule', 'Alliances', 'Montre', 'Parure de bague', 'Boutons de manchette', 'Bagues', 'Pendentifs', 'Boucles d\'oreilles', 'Bracelet', 'Broches', 'Colliers', 'Sautoir', 'Parures de bijoux', 'Chaîne', 'Citrine', 'Quartz', 'Jade', 'Rubis', 'Diamant', 'Eméraude', 'Vannerie', 'Poterie', 'Miniature', 'Fruits / corbeilles à pain', 'Sets de table en bambou', 'Boîte à épices', 'Cadre photo', 'Support de pot de fleur', 'Pots de fleur', 'Décoration murale', 'Objet design fer forge', 'Embout', 'Montre mural en fer forgé', 'Bougeoir', 'Photophore', 'Applique & Luminaires', 'Cornes de zébu', 'Literie', 'Penderie', 'Table', 'Porte cintre', 'Chaise', 'Table avec chaise', 'Range chaussures', 'Sacs', 'Panier', 'Chapeaux', 'Nappe', 'Smoc', 'Couvre lit', 'Richelieu', 'Crochet', 'Châle', 'Malabary', 'Lambamena', 'Sacs', 'Panier', 'Chapeaux', 'Noeud', 'Serre tête', 'Pince à cheveux', 'Brousse', 'Elastique de cheveux', 'bandeau', 'Boucle d’oreilles', 'Colliers', 'Pendentif', 'Gourmettes', 'Alliances', 'Boutons de manchette', 'Bracelets', 'Bague', 'Parure de bague', 'Chaine', 'Sautoir', 'Montres', 'Sacs bowling', 'Ceinture', 'Gants', 'Casquettes', 'Echarpes', 'Foulards', 'Bonnets', 'Headband', 'Cravates', 'Lunettes', 'Petite Flowerbox', 'Moyenne Fowerbox', 'Grande Flowerbox', 'Flowerbox personnalisée', 'Eaux de toilettes', 'Déodorants homme', 'Déodorants femme', 'Parfums homme', 'Parfum femme', 'Eaux de Cologne', 'Huile essentielle', 'Huile végétale', 'Huile massage', 'Produits naturels amincissant', 'Crayons et eyeliners', 'Mascaras', 'Ombres à paupières', 'Palettes et coffrets', 'Blush et poudres', 'Fonds de teint et BB crème', 'Rouges à lèvres', 'Primers et correcteurs', 'pilateurs sourcils', 'Dépilatoires', 'Accessoires maquillage', 'Anti-rides et anti-âges', 'Masques et gommages', 'Nettoyants et démaquillants', 'Purifiants et matifiants', 'Soins des lèvres et des yeux', 'Crèmes', 'Lotions', 'Baume', 'Emulsions', 'Huiles pour la peau', 'Produits de bronzage', 'produits pour le rasage', 'Produits d\’hygiène dentaire et buccale', 'Produits d’hygiène  intime externe', 'Bain & douche', 'Savons de toilette', 'Soins hydratants et nourrissants', 'Base protectrice Clean', 'Vernis', 'DDissolvant', 'Faux ongles', 'Lime', 'Pack de produits', 'Vaporisateurs', 'Fixateurs', 'Shampooings', 'Après-shampooings', 'Masques', 'Gel', 'Colorants', 'Produit pour l\'ondulation', 'Produit de coiffage', 'Huiles', 'Soins traitements', 'Coques', 'Batteries, Batteries externes', 'Ecouteurs bleutooth', 'Enceintes bleutooth', 'Chargeurs', 'Oreillette bleutooth', 'Kits mains libres', 'Protection ecran', 'Carte mémoire', 'Téléphone fixe', 'Téléphone avec touche', 'Smartphone', 'I-phone', 'TV LED-LCD', 'TV 4K-UHD', 'Support TV', 'TV connectée', 'Smart TV', 'HD Ready', 'Full HD', '4K/UHD', 'Accessoires', 'Casque auto', 'Enceintes bleutooth, MP3, MP4', 'Radio', 'Dictaphone', 'Hifi', 'Bare de son', 'Flash photo', 'Filtre', 'Caméscope caméra', 'Objectif reflex', 'Objectif caméra', 'GoPro', 'Autre', 'Câble et connectique', 'Accessoires audio et video', 'Accessoires photos', 'Accessoires caméra', 'Ordinateurs de bureau', 'ordinateurs portables', 'Tablette', 'Univers gaming', 'Composants - périférique', 'Stockage', 'Réseaux', 'Hardware', 'Software', 'Imprimante jet d\'encre', 'Imprimante laser', 'Scaner', 'Cartouches', 'Toners', 'Motorisation', 'portails et volets', 'Accessoires', 'Interphone video', 'Alerme-Détecteur', 'Caméra de surveillance', 'Sécurité  incendie', 'Comprime', 'Gellule', 'Liquide', 'Injectable', 'Visage', 'Corps', 'Cheveux', 'Autres', 'Bébé', 'Enfant', 'Femme enceinte', 'adulte', 'Rectale', 'Plantes médicinales', 'Produits de santé naturels', 'Complément alimentaire', 'Argile verte', 'Huile d\'amande douce ', 'Huile d\'arachide', 'Femme enceinte', 'Huile d\'argan', 'Huile d\'avocat', 'Huile de baobab', 'Huile de calendula', 'Huile de cameline', 'Huile de coco', 'Huile de colza', 'Huile de germe de blé', 'Beurre de Karité', 'Huile de Moutarde', 'Huile d\'Olive', 'Huile de Palme', 'Huile de Ricin', 'Huile de Tournesol', 'Huile de Sésame', 'Huile de Lorenzo', 'Huile de poisson'
   ];

   public function getTypeArticle(): ?array
   {
      $type = $this->type;
      $output = array();
      for ($i = 0; $i < sizeof($type); $i++) {
         $output[$type[$i]] = $type[$i];
      }
      return $output;
   }
}
