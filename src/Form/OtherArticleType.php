<?php

namespace App\Form;

use App\Entity\Article;
use App\Service\CategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherArticleType extends AbstractType
{    
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService= $categoryService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Titre',
                'attr'=>[
                    'class'=>'form-control'
                ]

            ])
            ->add('price',NumberType::class,[
                'label'=>'Prix',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('price_promo',NumberType::class,[
                'label'=> 'Prix promo',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('price_global',NumberType::class,[
                'label'=>'Prix en gros',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('quantity',TextType::class,[
                'label'=>'Stock',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])

            ->add('sous_category',HiddenType::class)
            ->add('promo',ChoiceType::class,[
                'label'=>'Promotion',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'choices'=>[
                    'Normal'=>'Normal',
                    'Promotion'=>'Promotion',
                    'new'=>'New',
                    'vendu'=>'Vendu'
                ]
            ])
            ->add('marque',TextType::class,[
                'label'=>'Marque',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('category',HiddenType::class)
            ->add('type', ChoiceType::class, [
                'label'=>'type',
                'choices' => $this->getTypeArticle(),
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('wordKey',TextType::class,[
                'label'=>'Mots clés',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('description',TextareaType::class,[
                'label'=>'Déscription',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('images',FileType::class,[
                'attr'=>[
                    'class'=>'form-control file'
                ],
                'label'=>"images",
                'multiple'=>true,
                'mapped'=>false,
                'required'=> false

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

    private  $type = [
        'Chemise', 'Barettes','Bandeau','Jeans', 'T-shirts', 'Polos', 'Pulls', 'Gilets', 'Sweats-Shirts', 'Manteaux', 'Costumes', 'Vestes', 'Pantalons', 'Short', 'Bermudas', 'Tenues de Sports', 'Vetement de nuit', 'Impermeable', 'Maillots de Bains', 'Chaussettes', 'Ensemble', 'Jogging', 'Blousons', 'Derbies', 'Chaussures de ville', 'Slippers', 'Derbie', 'Basket', 'Bottes', 'Boots', 'Chaussons', 'Chaussures bateau', 'Chaussures de Securité', 'Chaussures de sports', 'Espadrilles', 'Mocassins', 'Mulle', 'Sabot', ' Sandales', 'Tongs', 'Caleço', 'Boxeur', 'Slips', 'Shorty', 'Jock strap', 'Chaussette', 'Pantie', 'Robe de ceremonie', 'Robe de mariée', 'Robe de fiançaille', 'Cardigans', 'Body', 'Blouse', 'Débardeur', 'Tailleurs ', 'Jupes', 'Salopettes', 'Short et Bermudas', 'Leggins', 'Robes', 'Combi-short', 'Collants', 'Vêtements de Grossess', 'Vêtement de nuit', 'Vêtement de Sports', 'Imperméable', 'Maillots de Bains', 'Ballerines', 'Baskets', 'Botte', 'Dentelle côté', 'Tanga', 'Boxer',  'Accessoires', 'Bas', 'Jarretières', 'Bodys', 'Bustiers', 'Corsets', 'Caracos', 'Combinaisons', 'Jupons', 'Culottes', 'Shorties', 'Strings', 'Ensembles de Lingeries', 'Lingeries', 'Nuisettes', 'Deshabillés', 'Vêtements Thérmiques', 'Soutiens Gorges', 'Pantalon', 'Cardigans', 'Jean', 'Bermuda', 'Sous vêtements', 'Joggins', 'Salopette', 'Sweat-Shirt', 'Tailleur', 'Escarpins', 'Babies', 'Baskets mode', 'Couverture Bébé', 'Peluches', 'Veste', 'Salopettes', 'Combinaison', 'Sweat-Shirt', 'Grenoullière', 'Pyjamas', 'Couverture Bébé', 'Botillon', 'Sac à main', 'Sac à dos', 'Sac de voyage', 'Sac bandoulière', 'Portefeuilles et porte-cartes', 'Cabas', 'Pochettes', 'Sacs portes épaule', 'Alliances', 'Montre', 'Parure de bague', 'Boutons de manchette', 'Bagues', 'Pendentifs', 'Boucles d\'oreilles', 'Bracelet', 'Broches', 'Colliers', 'Sautoir', 'Parures de bijoux', 'Chaîne', 'Citrine', 'Quartz', 'Jade', 'Rubis', 'Diamant', 'Eméraude', 'Vannerie', 'Poterie', 'Miniature', 'Fruits / corbeilles à pain', 'Sets de table en bambou', 'Boîte à épices', 'Cadre photo', 'Support de pot de fleur', 'Pots de fleur', 'Décoration murale', 'Objet design fer forge', 'Embout', 'Montre mural en fer forgé', 'Bougeoir', 'Photophore', 'Applique & Luminaires', 'Cornes de zébu', 'Literie', 'Penderie', 'Table', 'Porte cintre', 'Chaise', 'Table avec chaise', 'Range chaussures', 'Sacs', 'Panier', 'Chapeaux', 'Nappe', 'Smoc', 'Couvre lit', 'Richelieu', 'Crochet', 'Châle', 'Malabary', 'Lambamena', 'Sacs', 'Panier', 'Chapeaux', 'Noeud', 'Serre tête', 'Pince à cheveux', 'Brousse', 'Elastique de cheveux', 'bandeau', 'Boucle d’oreilles', 'Colliers', 'Pendentif', 'Gourmettes', 'Alliances', 'Boutons de manchette', 'Bracelets', 'Bague', 'Parure de bague', 'Chaine', 'Sautoir', 'Montres', 'Sacs bowling', 'Ceinture', 'Gants', 'Casquettes', 'Echarpes', 'Foulards', 'Bonnets', 'Headband', 'Cravates', 'Lunettes', 'Petite Flowerbox', 'Moyenne Fowerbox', 'Grande Flowerbox', 'Flowerbox personnalisée', 'Eaux de toilettes', 'Déodorants homme', 'Déodorants femme', 'Parfums homme', 'Parfum femme', 'Eaux de Cologne', 'Huile essentielle', 'Huile végétale', 'Huile massage', 'Produits naturels amincissant', 'Crayons et eyeliners', 'Mascaras', 'Ombres à paupières', 'Palettes et coffrets', 'Blush et poudres', 'Fonds de teint et BB crème', 'Rouges à lèvres', 'Primers et correcteurs', 'pilateurs sourcils', 'Dépilatoires', 'Accessoires maquillage', 'Anti-rides et anti-âges', 'Masques et gommages', 'Nettoyants et démaquillants', 'Purifiants et matifiants', 'Soins des lèvres et des yeux', 'Crèmes', 'Lotions', 'Baume', 'Emulsions', 'Huiles pour la peau', 'Produits de bronzage', 'produits pour le rasage', 'Produits d\’hygiène dentaire et buccale', 'Produits d’hygiène  intime externe', 'Bain & douche', 'Savons de toilette', 'Soins hydratants et nourrissants', 'Base protectrice Clean', 'Vernis', 'DDissolvant', 'Faux ongles', 'Lime', 'Pack de produits', 'Vaporisateurs', 'Fixateurs', 'Shampooings', 'Après-shampooings', 'Masques', 'Gel', 'Colorants', 'Produit pour l\'ondulation', 'Produit de coiffage', 'Huiles', 'Soins traitements', 'Coques', 'Batteries, Batteries externes', 'Ecouteurs bleutooth', 'Enceintes bleutooth', 'Chargeurs', 'Oreillette bleutooth', 'Kits mains libres', 'Protection ecran', 'Carte mémoire', 'Téléphone fixe', 'Téléphone avec touche', 'Smartphone', 'I-phone', 'TV LED-LCD', 'TV 4K-UHD', 'Support TV', 'TV connectée', 'Smart TV', 'HD Ready', 'Full HD', '4K/UHD', 'Accessoires', 'Casque auto', 'Enceintes bleutooth, MP3, MP4', 'Radio', 'Dictaphone', 'Hifi', 'Bare de son', 'Flash photo', 'Filtre', 'Caméscope caméra', 'Objectif reflex', 'Objectif caméra', 'GoPro', 'Autre', 'Câble et connectique', 'Accessoires audio et video', 'Accessoires photos', 'Accessoires caméra', 'Ordinateurs de bureau', 'ordinateurs portables', 'Tablette', 'Univers gaming', 'Composants - périférique', 'Stockage', 'Réseaux', 'Hardware', 'Software', 'Imprimante jet d\'encre', 'Imprimante laser', 'Scaner', 'Cartouches', 'Toners', 'Motorisation', 'portails et volets', 'Accessoires', 'Interphone video', 'Alerme-Détecteur', 'Caméra de surveillance', 'Sécurité  incendie', 'Comprime', 'Gellule', 'Liquide', 'Injectable', 'Visage', 'Corps', 'Cheveux', 'Autres', 'Bébé', 'Enfant', 'Femme enceinte', 'adulte', 'Rectale', 'Plantes médicinales', 'Produits de santé naturels', 'Complément alimentaire', 'Argile verte', 'Huile d\'amande douce ', 'Huile d\'arachide', 'Femme enceinte', 'Huile d\'argan', 'Huile d\'avocat', 'Huile de baobab', 'Huile de calendula', 'Huile de cameline', 'Huile de coco', 'Huile de colza', 'Huile de germe de blé', 'Beurre de Karité', 'Huile de Moutarde', 'Huile d\'Olive', 'Huile de Palme', 'Huile de Ricin', 'Huile de Tournesol', 'Huile de Sésame', 'Huile de Lorenzo', 'Huile de poisson'
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
