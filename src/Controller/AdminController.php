<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Boutique;
use App\Entity\Images;
use App\Entity\Reference;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\BoutiqueType;
use App\Form\ReferenceType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\BlogRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\HeaderRepository;
use App\Repository\MenuRepository;
use App\Repository\NotificationRepository;
use App\Repository\ReferenceRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use App\Service\CategoryOptionService;
use App\Service\CategoryService;
use App\Service\InsertFileServices;
use App\Service\NotificationService;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Flex\Unpack\Result;

class AdminController extends AbstractController
{
    private $typeOptionMenuService;
    private $utilsService;
    public function __construct(UtilsService $utilsService, TypeOptionMenuService $typeOptionMenuService)
    {
        $this->typeOptionMenuService = $typeOptionMenuService;
        $this->utilsService = $utilsService;
    }
    /**
     * @Route("/teste/slug/{slug}", name="teste_slug")
     */

    public function testeSlug(string $slug, NotificationService $notificationService,  UtilsService $utilsService)
    {

        dd($notificationService->getMessageNotification());
        return new Response($utilsService->getSlug($slug));
    }
    /**
     * @Route("/admin", name="home")
     */
    public function index(BoutiqueRepository $boutiqueRepository, HeaderRepository $headerRepository, BlogRepository $blogRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $header = $headerRepository->findOneBy(['boutique' => $boutique]);
        $img_header = ($header) ? $header->getName() : 'images_default/default_image.jpg';
        $id = ($header) ? $header->getId() : false;
        return $this->render('admin/index.html.twig', [
            'pages' => 'home',
            'img_header' => $img_header,
            'id' => $id,
            'boutique' => $boutique,
            'peddingBlogs' => $blogRepository->findBy(['boutique' => $boutique, 'status' => 'PENDING']),
            'closedBlogs' => $blogRepository->findBy(['boutique' => $boutique, 'status' => 'CLOSED']),
            'validateBlogs' => $blogRepository->findBy(['boutique' => $boutique, 'validate' => true])
        ]);
    }

    /**
     * @Route("/admin/add/video", name="add_video")
     */
    public function add_video(BoutiqueRepository $boutiqueRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

        return $this->render('admin/index.html.twig', [
            'pages' => 'add_video',
            'boutique' => $boutique,
        ]);
    }

    /**
     * @Route("/admin/user", name="user")
     */
    public function user(BoutiqueRepository $boutiqueRepository, UserRepository $userRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'user',
            'boutique' => $boutique,
            'users'  => $userRepository->findAllWithRoleSuperAdmin("ROLE_USER")
        ]);
    }
    /**
     * @Route("/admin/edit/video/{id}", name="edit_video")
     */
    public function edit_video(int $id, BoutiqueRepository $boutiqueRepository, VideoRepository $videoRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $video = $videoRepository->findOneBy(['boutique' => $boutique, 'id' => $id]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'edit_video',
            'boutique' => $boutique,
            'video' => $video
        ]);
    }
    /**
     * @Route("/admin/list/video", name="list_video")
     */
    public function video(BoutiqueRepository $boutiqueRepository, VideoRepository $videoRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $videos = $videoRepository->findBy(['boutique' => $boutique]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'list_video',
            'boutique' => $boutique,
            'videos' => $videos
        ]);
    }
    /**
     * @Route("/admin/article_list/{category}/{sous_category}", name="article_list")
     */
    public function addArticleInShop($category, $sous_category, CategoryOptionService $categoryOptionService, Request $request, CategoryService $categoryService, BoutiqueRepository $boutiqueRepository, InsertFileServices $insertFileServices, ArticleRepository $articleRepository)
    {


        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        ////

        /*  if ($form->isSubmitted()) {
            $allImages = [];
            $entityManager = $this->getDoctrine()->getManager();
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = $insertFileServices->insertFile($image);
                $img = new Images();

                $img->setName($fichier);
                $article->addImage($img);

                array_push($allImages, $fichier);
                break;
            }


            $article->setBoutique($boutique);
            $entityManager->persist($article);
            $entityManager->flush();

            $array = [
                'status' => 'success',
                'images' => $allImages,
                'name' => $article->getName(),
                'id' => $article->getId()
            ];
            return new JsonResponse($array, Response::HTTP_OK);
        }

        */

        ///

        return $this->render('admin/index.html.twig', [
            'pages' => 'articleinshop',
            'articles' => $articleRepository->findBy(['boutique' => $boutique, 'category' => $sous_category]),
            'boutique' => $boutique,
            'add_button' => $categoryService->getAddButton($this->typeOptionMenuService->getTypeOptionMenu($boutique->getType(), $category), "btn btn-success ajout_article_ev"),
            'form' => $form->createView(),
            'category' => $sous_category
        ]);
    }
    /**
     * @Route("admin/reference", name="reference")
     */
    public function reference(ReferenceRepository $referenceRepository,  BoutiqueRepository $boutiqueRepository, InsertFileServices $insertFileServices, Request $request, HeaderRepository $headerRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

        $reference = new Reference();
        $form = $this->createForm(ReferenceType::class, $reference);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $reference->setBoutique($boutique);
            $file = $insertFileServices->insertFile($reference->getPhotos());
            $reference->setImages($file);
            $em = $this->getDoctrine()->getManager();
            $em->persist($reference);
            $em->flush();
        }
        return $this->render('admin/index.html.twig', [
            'pages' => 'reference',
            'boutique' => $boutique,
            'form' => $form->createView(),
            'references' => $referenceRepository->findBy(['boutique' => $boutique])
        ]);
    }
    /**
     * @Route("admin/condition", name="condition")
     */
    public function condition(BoutiqueRepository $boutiqueRepository, HeaderRepository $headerRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'reference',
            'boutique' => $boutique
        ]);
    }
    /**
     * @Route("admin/reseau", name="reseaux")
     */
    public function reseaux(BoutiqueRepository $boutiqueRepository, HeaderRepository $headerRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'reseaux',
            'boutique' => $boutique
        ]);
    }
    /**
     * @Route("admin/parametre/option", name="parametre_option")
     */
    public function parametreOption(BoutiqueRepository $boutiqueRepository, MenuRepository $menuRepository, CategoryOptionService $categoryOptionService)
    {


        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $menu = $menuRepository->findBy(['boutique' => $boutique]);

        return $this->render('admin/index.html.twig', [
            'pages' => 'parametreOption',
            'boutique' => $boutique,
            'listmenu' => $menu,
            'listCategories' => $categoryOptionService->getListPerCategory($menu)
        ]);
    }
    /**
     * @Route("/superadmin/inscription", name="admin_registration")
     */
    public function registration(Request $request, BoutiqueRepository $boutiqueRepository, UserRepository $userRepository, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $boutique = new Boutique();



        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allUsers = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($allUsers == NULL) {
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $user->setRoles(["ROLE_ADMIN"]);

                $boutique->setName('myBoutiqueName')
                    ->setType($request->get('shop_type'))
                    ->setAddress('myBoutiqueAdress')
                    ->setLink('myLinkForSiteWeb')
                    ->setMail('myBoutiqueAdressMailOf')
                    ->setContact('myAdressContact')
                    ->setApropos('DescriptionOfMyBoutque')
                    ->setUser($user);
                $manager->persist($user);
                $manager->persist($boutique);

                $manager->flush();
            } else {

                return new Response(json_encode(['status' => 'ko', 'msg' => 'Adresse mail existe']), 200, [
                    'Content-Type' => 'application/json'
                ]);
            }

            return new Response(json_encode(['status' => 'ok', 'msg' => 'Enregistrer avec succée']), 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'forms' => $form->createView(),
            'pages' => 'new_user',
            'boutique' => $boutique
        ]);
    }

    /**
     * @Route("/admin/boutique", name="admin_boutique")
     */

    public function editBoutique(BoutiqueRepository $boutiqueRepository, Request $request)
    {

        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted()  and $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {

                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $boutique->setLogo($fichier);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($boutique);
            $em->flush();

            return new JsonResponse(['status=>success']);
        }

        return $this->render('admin/index.html.twig', [
            'pages' => 'edition_boutique',
            'form' => $form->createView(),
            'boutique' => $boutique
        ]);
    }
    /**
     * @Route("/admin/boutique/list", name="list_boutique")
     */

    public function listBoutique(BoutiqueRepository $boutiqueRepository, Request $request)
    {

        $boutiques = $boutiqueRepository->findAll();
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'list_boutique',
            'boutiques' => $boutiques,
            'boutique' => $boutique
        ]);
    }
    /**
     * @Route("admin/shop/longLat", name="setLongLat", methods={"POST"})
     */
    public function setLongLat(BoutiqueRepository $boutiqueRepository, Request $request)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

        $em = $this->getDoctrine()->getManager();
        $boutique->setLongLat([
            "long" => $request->request->get('long'),
            "lat" => $request->request->get('lat'),
        ]);
        $em->persist($boutique);
        $em->flush();

        return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
    }
    public function buttonAddProduct(array $categories, string $class)
    {
        $button = "";
        foreach ($categories as $key => $category) {
            $button .= '<button id="' . $this->utilsService::getSlug($category) . '"class="' . $class . '">' . $category . '</button>';
        }
        return $button;
    }
    static function button_add_boutique(string $categorie, $class)
    {

        $button = " ";
        switch ($categorie) {
            case 'habillement_homme':

                $button = '<button id="' . 'Vetements_homme' . '"class="' . $class . '">Vêtement homme</button>';
                $button .= '<button id="' . 'Chaussures_homme' . '"class="' . $class . '">Chaussure homme</button>';
                $button .= '<button id="' . 'Lingeries_homme' . '"class="' . $class . '">Lingeries homme</button>';

                break;
            case 'habillement_femme':
                $button = '<button id="' . 'Vetements_femme' . '"class="' . $class . '">Vêtement femme</button>';
                $button .= '<button id="' . 'Chaussures_femmme' . '"class="' . $class . '">Chaussure femme</button>';
                $button .= '<button id="' . 'Lingeries_femme' . '"class="' . $class . '">Lingeries femme</button>';
                break;
            case 'habillement_enfant':
                $button = '<button id="' . 'Vetements_enfant' . '"class="' . $class . '">Garçon enfant</button>';
                $button .= '<button id="' . 'Lingeries_enfant' . '"class="' . $class . '">fille enfant</button>';
                $button .= '<button id="' . 'Chaussures_enfant' . '"class="' . $class . '">Chaussure enfant</button>';
                break;
            case 'habillement_bebe':
                $button = '<button id="' . 'bebe_garcons' . '"class="' . $class . '">Garçon bébé</button>';
                $button .= '<button id="' . 'bebe_filles' . '"class="' . $class . '">fille bébé</button>';
                $button .= '<button id="' . 'bebe_chaussure' . '"class="' . $class . '">Chaussure bébé</button>';
                break;
            case 'acc_parfums':
                $button = '<button id="acc_parfums"class="' . $class . '">Parfums</button>';
                break;
            case 'acc_beaute_bio':
                $button = '<button id="acc_beaute_bio"class="' . $class . '">Beauté bio</button>';
                break;

            case 'acc_cosmetique':

                $button = '<button id="acc_soins_corps_visage"class="' . $class . '">Soin du corps et du visage</button>';
                $button .= '<button id="acc_soins_ongle"class="' . $class . '">Soin d\'ongle</button>';
                $button .= '<button id="acc_soins_cheveux"class="' . $class . '">Soin des cheveux</button>';

                break;
            case 'bijoux_pierre':
                $button = '<button id="' . 'bijoux' . '"class="' . $class . '">Bijoux</button>';
                $button .= '<button id="' . 'pierre_precieuse' . '"class="' . $class . '">Pierre précieuse</button>';
                break;
            case 'accessoire_decoration':
                $button = '<button id="artistique"class="' . $class . '">Artistique</button>';
                $button .= '<button id="travail_du_bois"class="' . $class . '">Sculpture sur bois</button>';
                $button .= '<button id="decoration_interieure"class="' . $class . '">Décoration interieure</button>';
                $button .= '<button id="travail_du_fer"class="' . $class . '">Meuble intérieure</button>';
                break;
            case 'maroquineries':
                $button = '<button id="maroquineries"class="' . $class . '">Maroquineries</button>';
                break;
            case 'produit_soie_raphia':

                $button = '<button id="raphia"class="' . $class . '">Raphia</button>';
                $button .= '<button id="broderie"class="' . $class . '">Broderie</button>';
                $button .= '<button id="produit_en_soie"class="' . $class . '">Soie</button>';
                $button .= '<button id="sisal"class="' . $class . '">Sisal</button>';

                break;
            case 'acc_cheveux':
                $button = '<button id="acc_cheveux"class="' . $class . '">Accessoire cheveux</button>';
                break;
            case 'acc_bijoux_montre':
                $button = '<button id="acc_bijoux_montre"class="' . $class . '">Accessoire bijouc et montre</button>';
                break;
            case 'acc_sacs_maroquinerie':
                $button = '<button id="' . 'acc_sacs_maroquinerie' . '"class="' . $class . '">Accessoire sacs et maroquinerie</button>';
                break;
            case 'acc_flowerbox':
                $button = '<button id="' . 'acc_flowerbox' . '"class="' . $class . '">Flowerbox</button>';
                break;

            case 'Accessoires_tele':

                $button = '<button id="raphia"class="' . $class . '">Raphia</button>';
                $button .= '<button id="broderie"class="' . $class . '">Broderie</button>';
                $button .= '<button id="produit_en_soie"class="' . $class . '">Soie</button>';
                $button .= '<button id="sisal"class="' . $class . '">Sisal</button>';

                break;
            case 'tous_accessoires':

                $button = '<button id="Tous_les_accessoires"class="' . $class . '">Tous les accessoires</button>';

                break;
            case 'systeme_domotique':

                $button = '<button id="Systeme_domotique"class="' . $class . '">Système domotique</button>';

                break;
            case 'impression':

                $button = '<button id="Impression"class="' . $class . '">Impression</button>';

                break;

            case 'telephone':
                $button = '<button id="Téléphone"class="' . $class . '">Téléphone</button>';
                break;
            case 'accessoires':
                $button = '<button id="Accessoires"class="' . $class . '">Accessoires</button>';
                break;

            case 'materiels_informatique':
                $button = '<button id="Materiels_informatiques" class="' . $class . '">Matériel informatiques</button>';
                break;
            case 'diagnotiques':
                $button = '<button id="Diagnostiques"class="' . $class . '">Diagnostiques</button>';
                break;
            case 'tv':
                $button = '<button id="TV"class="' . $class . '">TV</button>';
                break;
            case 'videoprojecteur':
                $button = '<button id="Video_projecteur"class="' . $class . '">Video projecteur</button>';
                break;
            case 'son':
                $button = '<button id="Son"class="' . $class . '">Son</button>';
                break;
            case 'photo_et_camera':
                $button = '<button id="Phone_et_caméra"class="' . $class . '">Phone et caméra</button>';
                break;

            case 'huille_essentiel_et_vegetale':
                $button = '<button id="hev"class="' . $class . '">Huile essentiel et vegetale</button>';
                break;
            case 'voies_orale':
                $button = '<button id="orale"class="' . $class . '">Orale</button>';
                break;
            case 'injectable':
                $button = '<button id="injectable"class="' . $class . '">Injectable</button>';
                break;
            case 'dermique':
                $button = '<button id="dermique"class="' . $class . '">Dermique</button>';
                break;
            case 'inhalee':
                $button = '<button id="inhalee"class="' . $class . '">Inhalée</button>';
                break;
            case 'outillages':
                $button = '<button id="outillages"class="' . $class . '">Outillages</button>';
                break;
            case 'outillages_pro':
                $button = '<button id="outillages_pro"class="' . $class . '">Outillages Pro</button>';
                break;
            case 'outils_de_jardin':
                $button = '<button id="outils_de_jardin"class="' . $class . '">Outils de jardin</button>';
                break;
            case 'rectale':
                $button = '<button id="rectale"class="' . $class . '">Rectale</button>';
                break;


            default:
                $button = "";
                break;
        }


        return $button;
    }
    static function nameMenu($categorie)
    {
        switch ($categorie) {
            case 'outils_de_jardin':
                return 'Outils de jardin';
                break;
            case 'outillages':
                return 'Outillages';
                break;
            case 'outillages_pro':
                return 'Outillages pro';
                break;
            case 'habillement_homme':
                return 'Habillement homme';
                break;
            case 'habillement_femme':
                return 'Habillement femme';
                break;
            case 'habillement_enfant':
                return 'Habillement enfant';
                break;
            case 'habillement_bebe':
                return 'Habillement bébé';
                break;
            case 'acc_parfums':
                return 'Parfums';
                break;
            case 'acc_beaute_bio':
                return 'Beauté_bio';

            case 'acc_cosmetique':
                return 'Cosmetique';

                break;
            case 'bijoux_pierre':
                return 'Bijoux et pierre précieuse';
                break;
            case 'accessoire_decoration':
                return 'Accessoire décoration';
                break;
            case 'maroquineries':
                return 'Maroquineries';
                break;
            case 'produit_soie_raphia':
                return 'Produit en soie, raphia';

                break;
            case 'acc_cheveux':
                return 'Accessoire cheveux';
                break;
            case 'acc_bijoux_montre':
                return 'Bijoux et montre';
                break;
            case 'acc_sacs_maroquinerie':
                return 'Sacs, maroquinerie';
                break;
            case 'acc_flowerbox':
                return 'Fashion +';
                break;


            case 'telephone':
                return 'Téléphone';
                break;
            case 'Accessoires_tele':

                return 'Accessoire télé';

                break;
            case 'tous_accessoires':

                return 'Tous les accessoires';

                break;
            case 'systeme_domotique':

                return 'Système domotique';

                break;
            case 'impression':

                return 'Impression';

                break;

            case 'accessoires':
                return 'Accessoire';
                break;

            case 'matierels_informatique':
                return 'Matiériel informatique';
                break;
            case 'diagnotiques':
                return 'Diagnostique';
                break;
            case 'tv':
                return 'Tv';
                break;
            case 'videoprojecteur':
                return 'Video projecteur';

                break;
            case 'son':
                return 'Son';
                break;
            case 'photo_et_camera':
                return 'Photo et caméra';
                break;

            case 'huille_essentiel_et_vegetale':
                return 'Huille essentiel et végetale';
                break;
            case 'voies_orale':
                return 'Voies orale';
                break;
            case 'injectable':
                return 'Injectable';
                break;
            case 'dermique':
                return 'Dermique';
                break;
            case 'inhalee':
                return 'Inhalée';
                break;
            case 'rectale':
                return 'Rectale';
                break;
            default:
                return '.....Page introuvable';
                break;
        }
    }
}
