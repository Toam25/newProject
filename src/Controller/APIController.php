<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Blog;
use App\Entity\Boutique;
use App\Entity\EsArticle;
use App\Entity\Header;
use App\Entity\Images;
use App\Entity\Menu;
use App\Entity\Notification;
use App\Entity\Page;
use App\Entity\ProfilJob;
use App\Entity\User;
use App\Entity\UserCondition;
use App\Entity\UserVote;
use App\Entity\Video;
use App\Entity\Vote;
use App\Entity\Votes;
use App\Repository\ArticleRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\EsArticleRepository;
use App\Repository\HeaderRepository;
use App\Repository\ImagesRepository;
use App\Repository\MenuRepository;
use App\Repository\NotificationRepository;
use App\Repository\PageRepository;
use App\Repository\ProfilJobRepository;
use App\Repository\ReferenceRepository;
use App\Repository\SliderRepository;
use App\Repository\SocialNetworkRepository;
use App\Repository\UserConditionRepository;
use App\Repository\UserRepository;
use App\Repository\UserVoteRepository;
use App\Repository\VoteRepository;
use App\Service\ArticlePerShopService;
use App\Service\CategoryOptionService;
use App\Service\InsertFileServices;
use App\Service\NotificationService;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api", name="api")
 */
class APIController extends AbstractController
{
    private $em;
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->entityManager = $em;
    }
    /**
     * @Route("/v1/blog/delete/{id}", name=".delete_blog", methods={"POST"})
     */

    public function deleteBlog(Blog $blog)
    {
        if ($this->getUser() == $blog->getUser() or $this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {


            $this->em->remove($blog);
            $this->em->flush();
            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/v1/page/delete/{id}", name=".delete_my_page", methods={"POST"})
     */

    public function deletePage(Page $page)
    {
        if ($this->getUser() == $page->getUser() or $this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {


            $this->em->remove($page);
            $this->em->flush();
            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/v1/video/delete/{id}", name=".delete_video", methods={"POST"})
     */

    public function deleteVideo(Video $video, BoutiqueRepository $boutiqueRepository)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        if ($boutique == $video->getBoutique() or $this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {


            $this->em->remove($video);
            $this->em->flush();
            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/{type}/update/{value}/{id}" , name="showBlogArticle", methods={"POST"})
     */

    public function showBlogArticle(string $type, $value, Boutique $boutique)
    {
        $value = $value == 1 ? true : false;
        if ($type == "articleShow") {
            $boutique->setShowArticle($value);
        } elseif ($type == "blogShow") {
            $boutique->setShowBlog($value);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse(["status" => "success"], Response::HTTP_OK);
    }
    /**
     * @Route("/v1/saveblog", name="saveBlog", methods="POST")
     */

    public function saveBlog(Request $request, InsertFileServices $insertFileServices, UserRepository $userRepository, EntityManagerInterface $em, BoutiqueRepository $boutiqueRepository, UtilsService $utilsService)
    {


        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()->getId()]);

        $blogData = $request->request->get('blog');


        if ($boutique) {

            if ($boutique->getDetailOffer() == "Gratuit") {
                $blogs = $boutique->getBlogs();
                $sizeBlogs = sizeof($blogs);
                if ($sizeBlogs > 2) {
                    return $this->json(['status' => 'ko', 'message' => "Votre offre ne permet pas d'ajout plus de deux blog"], Response::HTTP_UNAUTHORIZED);
                }
            }

            $blog = new Blog();
            $blog->setTitle($blogData['title'])
                ->setStatus($request->request->get('status'))
                ->setResume($blogData['resume'])
                ->setKeywords($blogData['keywords'])
                ->setCategory($blogData['category'])
                ->setMetaDescription($blogData['metaDescription'])
                ->setLink($utilsService->getSlug($blogData['link']))
                ->setDescription($blogData['description'])
                ->setUser($this->getUser())
                ->setBoutique($boutique)
                ->setImages("null");;
            if ($request->files->get('image_blog')) {
                $file = $insertFileServices->insertFile($request->files->get('image_blog'));
                $blog->setImage($file);
            }


            $em->persist($blog);
            $em->flush();
            $notification = new Notification();
            $notification->setSubject('REQUEST_APPROVAL_BLOG');
            $notification->setDescription($blog->getId());
            $notification->setFromUser($this->getUser()->getId());
            $users = $userRepository->findAllWithRoleSuperAdmin("ROLE_SUPER_ADMIN");
            foreach ($users as $user) {
                $notification->addToUser($user);
            }
            $em->persist($notification);
            $em->flush();

            return new JsonResponse(['status' => "success", 'id' => $blog->getId()]);
        } else {
            return new JsonResponse(['status' => "ko", "message" => "Boutique inconue"], Response::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * @Route("/v1/edit/blog/{id}", name="editBlog", methods="POST")
     */

    public function editBlog(Request $request, Blog $blog, InsertFileServices $insertFileServices, BoutiqueRepository $boutiqueRepository, UtilsService $utilsService)
    {


        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()->getId()]);

        $blogData = $request->request->get('blog');

        if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) or $boutique and $blog->getBoutique() == $boutique) {
            $blog->setTitle($blogData['title'])
                ->setStatus($request->request->get('status'))
                ->setResume($blogData['resume'])
                ->setKeywords($blogData['keywords'])
                ->setCategory($blogData['category'])
                ->setMetaDescription($blogData['metaDescription'])
                ->setLink($utilsService->getSlug($blogData['link']))
                ->setDescription($blogData['description'])
                ->setUser($this->getUser())
                ->setBoutique($boutique);

            if ($request->files->get('image_blog')) {
                $file = $insertFileServices->insertFile($request->files->get('image_blog'));
                $blog->setImage($file);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
            return new JsonResponse(['status' => "success", 'id' => $blog->getId()]);
        } else {
            return new JsonResponse(['status' => "error"], Response::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * @Route("/es_article/get/{sous_category}", name="get_es_article")
     */
    public function getEsArticle(string $sous_category, EsArticleRepository $esArticleRepository, UtilsService $utilsService)
    {
        $es_article = $esArticleRepository->findBy(['sous_category' => $sous_category]);
        $data = [];
        foreach ($es_article as $key => $value) {
            $data[$key]['type'] = $value->getType();
            $data[$key]['category'] = $utilsService->getSlug($value->getSousCategory());
            $data[$key]['image'] = $value->getImage();
        }
        return new Response(json_encode($data));
    }
    /**
     * @Route("/delete/review/{id}", name="delete-review", methods={"POST"})
     */

    public function deleteReview(Votes $votes)
    {

        if ($this->getUser() and $votes) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($votes);
            $em->flush();
            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/v1/add_image", name="get-image", methods={"GET"})
     */

    public function getImage(ImagesRepository $imagesRepository, BoutiqueRepository $boutiqueRepository)
    {


        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $images = $imagesRepository->findBy(['boutique' => $boutique]);

            $allimages = [];

            for ($i = 0; $i < sizeof($images); $i++) {
                array_push($allimages, [
                    'id' => $images[$i]->getId(),
                    'name' => $images[$i]->getName()
                ]);
            }

            return new JsonResponse(['images' => $allimages], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/v1/add_image/{id}", name="delete-image", methods={"POST"})
     */

    public function deleteImage(Images $images, BoutiqueRepository $boutiqueRepository)
    {


        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            if ($images->getBoutique() == $boutique or $images->getUser() == $this->getUser()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($images);
                $em->flush();
                unlink('images/' . $images->getName());
                return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
            }
        }
        return new JsonResponse(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/v1/add_image", name="add-image", methods={"POST"})
     */

    public function addImage(Request $request, InsertFileServices $insertFileServices, BoutiqueRepository $boutiqueRepository)
    {

        $file = $request->files->get('my_image_file');
        if ($file and $this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

            $myimage = $insertFileServices->insertFile($file, ['png', 'jpg', 'jpeg', 'webp', 'gif']);
            if ($myimage !== false) {
                $image = new Images();
                $image->setName($myimage);
                if ($boutique) {
                    $image->setBoutique($boutique);
                }
                $image->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->flush();
                return new JsonResponse(['id' => $image->getId(), 'image' => $myimage], Response::HTTP_OK);
            }
        }
        return new JsonResponse(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/getOptions/{sous_category}", name="get_option")
     */
    public function getOption(string $sous_category, TypeOptionMenuService $typeOptionMenuService)
    {
        $listOption = $typeOptionMenuService->getOption($sous_category);


        return new JsonResponse($listOption, Response::HTTP_OK);
    }

    /**
     * @Route("/set/numberVoteIndex/{status}-{id_vote}", name="set_number_vote_index", methods={"POST"})
     */
    public function setNumberVoteIndex($status, $id_vote, VoteRepository $voteRepository, UserVoteRepository $userVoteRepository)
    {
        $vote = $voteRepository->findOneBy(['id' => $id_vote]);
        $userVote = $userVoteRepository->findOneBy(['vote' => $vote, 'user' => $this->getUser()]);

        if ($this->getUser() and $vote and $userVote == null) {

            $userVote = new UserVote();
            if ($status == "haut") {

                $vote->setNbrVote($vote->getNbrVote() + 1);
                $vote->setPlacement($vote->getPlacement() + 1);
            } else {
                if ($status == "bas") {
                    if ($vote->getNbrVote() != 0) {
                        $vote->setNbrVote($vote->getNbrVote() - 1);
                    }
                }
            }
            $userVote->setVote($vote);
            $userVote->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($userVote);
            $em->flush();
            return new JsonResponse(['status' => "success", 'nbr_vote' => $vote->getNbrVote()], Response::HTTP_OK);
        }

        return new JsonResponse(['status' => "error"], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/profil/update/me/user/{id}", name="profil_update_user")
     */
    public function updateUser(Request $request, User $user, $id, InsertFileServices $insertFileServices)
    {
        if ($this->getUser()->getId() == intVal($id)) {
            $user->setName($request->request->get('name') ?? $user->getName());
            $user->setFirstname($request->request->get('first_name') ?? $user->getFirstname());

            if ($request->files->get('images')) {

                if (($user->getAvatar() !== "images_default/default_image.jpg")) {
                    unlink("/images/" . $user->getAvatar());
                }
                $user->setAvatar($insertFileServices->insertFile($request->files->get('images')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new JsonResponse(["status" => "sucess"], Response::HTTP_OK);
        } else {
            return new JsonResponse(["status" => "error"], Response::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * @Route("/login/update/me/user/{id}", name="profil_password", methods={"POST"})
     */
    public function upPassWordUser(Request $request, User $user, $id, InsertFileServices $insertFileServices, UserPasswordEncoderInterface $encoder)
    {


        if ($this->getUser()->getId() == intVal($id) &&   $encoder->isPasswordValid($user, $request->request->get('a_password'))) {

            $user->setEmail($request->request->get('mail'));

            if ($request->request->get('new_password') != "") {
                $user->setPassword($encoder->encodePassword($user, $request->request->get('new_password')));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse(["status" => "sucess"], Response::HTTP_OK);
        } else {
            return new JsonResponse(["status" => "error", 'message' => "Mot de passe incorect"], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse(["status" => "error", 'message' => "Mot de passe incorect"], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/profil/cv/user/{id}", name="profil_cv", methods="POST")
     */
    public function upProfilCvUser(Request $request, User $user, $id, UtilsService $utilsService, ProfilJobRepository $profilJobRepository, InsertFileServices $insertFileServices)
    {


        if ($this->getUser()->getId() == intVal($id)) {
            $profilJob = $profilJobRepository->findOneBy(['user' => $user]);
            $file = $insertFileServices->insertFile($request->files->get('cv'), ['pdf'], $this->getParameter('pdf_directory'));
            $em = $this->getDoctrine()->getManager();
            if ($profilJob != null) {

                //  unlink($this->getParameter('pdf_directory') . '/' . $profilJob->getCv());
                $profilJob->setCv($file);
            } else {
                $newProfilJob = new ProfilJob();
                $newProfilJob->setCv($file);
                $newProfilJob->setUser($user);
                $em->persist($newProfilJob);
            }
            $em->flush();
            return new JsonResponse(["status" => "sucess", "name" => $file], Response::HTTP_OK);
        } else {
            return new JsonResponse(["status" => "error", 'message' => "Mot de passe incorect"], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @Route("/profil/delete/cv", name="delete_profil_cv", methods="POST")
     */
    public function deleteProfilCvUser(Request $request, ProfilJobRepository $profilJobRepository)
    {

        $profilJob = $profilJobRepository->findOneBy(['user' => $this->getUser()]);
        if ($this->getUser()) {

            unlink($this->getParameter('pdf_directory') . "/" . $profilJob->getCv());
            $em = $this->getDoctrine()->getManager();
            $em->remove($profilJob);
            $em->flush();
            return new JsonResponse(["status" => "sucess"], Response::HTTP_OK);
        } else {
            return new JsonResponse(["status" => "error", 'message' => "Mot de passe incorect"], Response::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * @Route("/delete/boutique/{id}", name="deleteboutique")
     */
    public function deteteBoutique(Boutique $boutique, BoutiqueRepository $boutiqueRepository, UserRepository $userRepository, ArticleRepository $articleRepository)
    {


        if ($boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN')) {
            $articles = $articleRepository->findOneArticleByBoutiqueWithImage($boutique->getId());
            if ($articles) {
                foreach ($articles as $article) {
                    foreach ($article->getImages() as $image) {
                        // unlink('images/' . $image->getName());
                    }
                }
            }
            if ($boutique->getLogo() !== "images_default/default_image.jpg") {
                //unlink('images/'.$boutique->getLogo());
            }
            $em = $this->getDoctrine()->getManager();
            $users = $boutique->getUser();

            $em->remove($users);

            $em->remove($boutique);
            $em->flush();

            return new JsonResponse(['status' => 'success']);
        } else {
            return new JsonResponse(['status' => 'error'], 402);
        }
    }
    /**
     * @Route("/get/listOption", name="get_listOption")
     */
    public function getListOption(Request $request, MenuRepository $menuRepository, BoutiqueRepository $boutiqueRepository)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $sous_category = $request->request->get('categorie_sante_in');
        $list = [];
        $listMenu = $menuRepository->findBy(['boutique' => $boutique, 'sousCategory' => $sous_category]);
        foreach ($listMenu as  $key => $menu) {
            $list[$key] = [
                'name' => $menu->getName(),
                'id' => $menu->getId()
            ];
        }
        return new JsonResponse($list);
    }
    /**
     * @Route("/update/article", name="update_article")
     */
    public function updateArticle(Request $request, ArticleRepository $articleRepository, BoutiqueRepository $boutiqueRepository)
    {
        $article = $articleRepository->findOneBy(['id' => $request->request->get('id-article')]);

        if ($this->getUser() and $article) {
            $article->setCategory($request->request->get('categorie'));
            $article->setExternalDetail($request->request->get('external-link'));
            $article->setName($request->request->get('name'));
            $article->setPrice($request->request->get('price'));
            $article->setPriceGlobal($request->request->get('global_price'));
            $article->setPricePromo($request->request->get('price_promo'));
            $article->setPromo($request->request->get('promotion'));
            $article->setType($request->request->get('type') ?? $request->request->get('sous_category'));
            $article->setQuantity($request->request->get('quantity'));
            $article->setMarque("");
            $article->setDescription($request->request->get('description'));
            $article->setReferency($request->request->get('referency'));
            $article->setSousCategory($request->request->get('sous_category'));
            $article->setSlide($request->request->get('slider'));
            $article->setBoutique($boutiqueRepository->findOneBy(['user' => $this->getUser()]));


            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $array = [
                'status' => 'success',
                'name' => $article->getName(),
                'id' => $article->getId()
            ];
            return new JsonResponse($array, 200);
        }

        return new JsonResponse(['status' => 'error', 'message' => 'not Authorized'], 403);
    }
    /**
     * @Route("/add/article", name="add_article")
     */
    public function addArticle(Request $request, InsertFileServices $insertFileServices, BoutiqueRepository $boutiqueRepository)
    {


        if ($this->getUser()) {

            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            if ($boutique->getDetailOffer() == "Gratuit") {
                $articles = $boutique->getArticle();
                $sizeBlogs = sizeof($articles);
                if ($sizeBlogs > 10) {
                    return $this->json(['status' => 'ko', 'message' => "Votre offre ne permet pas d'ajout plus de 10 articles"], Response::HTTP_UNAUTHORIZED);
                }
            }
            $article = new Article();
            $article->setCategory($request->request->get('categorie'));
            $article->setName($request->request->get('name'));
            $article->setPrice($request->request->get('price'));
            $article->setPriceGlobal($request->request->get('global_price'));
            $article->setPricePromo($request->request->get('price_promo'));
            $article->setPromo($request->request->get('promotion'));
            $article->setExternalDetail($request->request->get('external-link'));

            $article->setType($request->request->get('type') ?? $request->request->get('sous_category'));
            $article->setQuantity($request->request->get('quantity'));
            $article->setMarque("");
            $article->setDescription($request->request->get('description'));
            $article->setReferency($request->request->get('referency'));
            $article->setSousCategory($request->request->get('sous_category'));
            $article->setSlide($request->request->get('slider'));
            $article->setBoutique($boutique);
            $article->setSlide(0);

            $images = $request->files->get('images');


            foreach ($images as $image) {
                $allImages = [];
                $fichier = $insertFileServices->insertFile($image);
                $img = new Images();

                $img->setName($fichier);
                $article->addImage($img);
                array_push($allImages, $fichier);
                break;
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->persist($img);
            $em->flush();
            $array = [
                'status' => 'success',
                'images' => $allImages,
                'name' => $article->getName(),
                'id' => $article->getId()
            ];
            return new JsonResponse($array, 200);
        }

        return new JsonResponse(['status' => 'error', 'message' => 'not Authorized'], 403);
    }



    /**
     * @Route("/delete/option/{id}", name="delete_listOption")
     */
    public function deleteOption(Menu $menu)
    {

        if ($this->getUser() == $menu->getBoutique()->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();
            return new JsonResponse(['status' => 'success']);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => "Non authorise"], 403);
        }
    }

    /**
     * @Route("/get/sous_category/type/{category}", name="getSousCategoryType")
     */
    public function getSousCategoryType(string $category, TypeOptionMenuService $typeOptionMenuService, BoutiqueRepository $boutiqueRepository, MenuRepository $menuRepository, CategoryOptionService $categoryOptionService)
    {

        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            if ($boutique->getType() == 'Habillement' or $boutique->getType() == 'accessoires' or $boutique->getType() == 'beaute-et-bien-etre') {
                $category = $typeOptionMenuService->getOption($category, $boutique->getType());

                $results = [
                    'type' => "",
                    'option' => $category['option'],
                ];
                return new JsonResponse(['status' => 'success', 'results' => [$results]], 200);
            }

            $menu = $menuRepository->findBy(['category' => $category]);
            return new JsonResponse(['status' => 'success', 'results' => $categoryOptionService->getCategoryType($menu)], 200);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => "Non authorise"], 403);
        }
    }

    /**
     * @Route("/get/article/{id}", name="getOneArtilce")
     */
    public function getOneArticle(CategoryOptionService $categoryOptionService, TypeOptionMenuService $typeOptionMenuService, BoutiqueRepository $boutiqueRepository, ArticleRepository $articleRepository, int $id, MenuRepository $menuRepository)
    {

        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $article = $articleRepository->findOneArticleByBoutiqueWithImage($id, $boutique);
            if ($boutique->getType() == 'Habillement' or $boutique->getType() == "accessoires" or $boutique->getType() == "beaute-et-bien-etre") {
                $category = $typeOptionMenuService->getOption($article->getCategory(), $boutique->getType());
                $list_menu = [[
                    'type' => "",
                    'option' => $category['option'],
                ]];
            } else {
                $list_menu = $categoryOptionService->getCategoryType($menuRepository->findBy(['category' => $article->getCategory()]));
            }

            $image = $article->getImages();
            $list = [
                'id' => $article->getId(),
                'category' => $article->getCategory(),
                'name' => $article->getName(),
                'price' => $article->getPrice(),
                'global_price' => $article->getPriceGlobal(),
                'promo_price' => $article->getPricePromo(),
                'promo' => $article->getPromo(),
                'type' => $article->getType(),
                'quantity' => $article->getQuantity(),
                'marque' => $article->getMarque(),
                'description' => $article->getDescription(),
                'referency' => $article->getReferency(),
                'sous_category' => $article->getSousCategory(),
                'slider' => $article->getSlide(),
                'images' => [
                    'name' => $image[0]->getName(),
                    'id' => $image[0]->getId()
                ],
                'view_external_link' => $article->getExternalDetail(),
                'list_menu' => $list_menu

            ];

            return new JsonResponse($list);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => "Non authorise"], 403);
        }
    }
    /**
     * @Route("/get/list/type/{sous_categorie}", name="getListType")
     */
    public function getListType(string $sous_categorie, MenuRepository $menuRepository)
    {

        if ($this->getUser()) {
            $menus = $menuRepository->findBy(['sousCategory' => $sous_categorie]);
            $list = [];
            foreach ($menus as $key => $menu) {
                $list[$key] = $menu->getName();
            }
            return new JsonResponse(['status' => 'success', 'results' => $list], 200);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => "Non authorise"], 403);
        }
    }

    /**
     * @Route("/add/listOption", name="add_listOption")
     */
    public function addListOption(Request $request, BoutiqueRepository $boutiqueRepository)
    {

        if ($this->getUser() != null) {

            $menu = new Menu();
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $category = $request->request->get('categorie_sante') ?? "";
            $name = $request->request->get('name_option') ?? "";
            $sous_category = $request->request->get('sous_categorie_menu') ?? "";

            $menu->setCategory($category);
            $menu->setName($name);
            $menu->setSousCategory($sous_category);
            $menu->setBoutique($boutique);
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            return new Response(json_encode([
                'status' => 'success',
                'results' => [
                    'category' => $category,
                    'name' => $name,
                    'id' => $menu->getId(),
                    'sous_category' => $sous_category,
                    'boutique_id' => $boutique->getId()
                ]
            ]));
        } else {
            return new JsonResponse(['status' => 'not authorised'], 403);
        }
    }

    /**
     * @Route("/update/images", name="update_images")
     */

    public function updateImages(Request $request, ImagesRepository $imagesRepository, InsertFileServices $insertFileServices)
    {


        $images = $request->files->get('images');

        if ($this->getUser()) {
            $file = $insertFileServices->insertFile($images, ['jpeg', 'jpg', 'gif', 'png', 'webp']);
            $images = $imagesRepository->findOneBy(['id' => $request->request->get('id-image')]);

            if ($file != false) {
                $images = $imagesRepository->findOneBy(['id' => $request->request->get('id-image')]);
                $images->setName($file);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                unlink('images/' . $images->getName());
                return new JsonResponse(['images' => $file, 'id' => $images->getId()]);
            } else {
                return new JsonResponse(['message' => "form fichier invalide"], Response::HTTP_NOT_ACCEPTABLE);
            }
        }

        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/header_image/edit", name="edit_images_header")
     */

    public function editImagesHeaderAdmin(Request $request, HeaderRepository $headerRepository, BoutiqueRepository $boutiqueRepository, InsertFileServices $insertFileServices)
    {


        $images = $request->files->get('header_image');

        if ($this->getUser()) {
            $file = $insertFileServices->insertFile($images, ['jpeg', 'jpg', 'gif', 'png']);

            if ($file != false) {
                $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
                $header = $headerRepository->findOneBy(['boutique' => $boutique]);

                if ($header) {
                    $header->setName($file);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                } else {
                    $header = new Header();
                    $header->setName($file);
                    $header->setBoutique($boutique);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($header);
                    $em->flush();
                }
                return new JsonResponse(['images' => $file, "id" => $header->getId()]);
            } else {
                return new JsonResponse(['message' => "form fichier invalide"], Response::HTTP_NOT_ACCEPTABLE);
            }
        }

        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/header_image/delete/{id}", name="header_delete", methods={"DELETE"})
     */
    public function header_delete($id, Header $header): Response
    {
        if ($this->getUser() and $header->getId() == $id) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($header);
            $entityManager->flush();
            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['status' => 'error'], Response::HTTP_NOT_EXTENDED);
        }
    }
    /**
     * @Route("/delete/header_images", name="header_delete_shop", methods={"POST"})
     */
    public function header_delete_shop(HeaderRepository $headerRepository, BoutiqueRepository $boutiqueRepository): Response
    {
        $header = $headerRepository->findOneBy(['boutique' => $boutiqueRepository->findOneBy(['user' => $this->getUser()])]);

        if ($header) {
            unlink('images/' . $header->getName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($header);
            $entityManager->flush();
            return new JsonResponse(['images' => 'images/images_default/default_image.jpg', 'status' => 'success'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['status' => 'error'], Response::HTTP_NOT_EXTENDED);
        }
    }
    /**
     * @Route("/get/listArticlePerBoutique/{category}/{type}", name="listeArticlePerBoutique", methods={"GET"})
     */
    public function listArticlePerBoutique($category, Request $request, $type, ArticleRepository $articleRepository, ArticlePerShopService $articlePerShopService): Response
    {

        if ($request->isXmlHttpRequest()) {
            return $this->render('boutique/index_list_article_per_shop.html.twig', [
                "articles" => $articlePerShopService->getListArticlePerShop($articleRepository->findAllArticleBySousCategory($category, $type))
            ]);
        }
        return new JsonResponse(['status' => "error"], Response::HTTP_OK);
    }
    /**
     * @Route("/v1/notification", name="getNotifications", methods={"GET"})
     */

    public function getNotification(NotificationService $notificationService)
    {
        $data = $this->getUser()->getData();

        if ($data != null && sizeof($data) > 0) {
            $data['message'] = [];
            $this->getUser()->setData($data);
            $this->getUser()->setNbrNotification(0);
            $this->entityManager->persist($this->getUser());
            $this->entityManager->flush();
        }
        return new JsonResponse($notificationService->getMessageNotification(), Response::HTTP_OK);
    }

    /**
     * @Route("/v1/formation", name="update_formation", methods={"UPDATE"})
     */

    public function updateFormation(Request $request, BoutiqueRepository $boutiqueRepository, PageRepository $pageRepository, InsertFileServices $insertFileServices)
    {
        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $id = $request->request->get('id');
            $page = $pageRepository->findOneBy(['id' => $id, 'boutique' => $boutique]);
            if ($page) {


                $pagedate = $request->request->get('page');
                $title = $pagedate['title'];
                $price = $pagedate['price'];
                $resume = $pagedate['resume'];
                $description = $pagedate['description'];
                $file = $request->files->get('image');
                if ($file) {
                    $image = $insertFileServices->insertFile($file, ['jpg', 'jpeg', 'gif', 'webm', 'png']);
                    $page->setImage($image);
                }
                $page->setTitle($title);
                $page->setPrice($price);
                $page->setResume($resume);
                $page->setDescription($description);
                $page->setBoutique($boutique);
                $page->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();
                return new JsonResponse(["status" => "Success"], Response::HTTP_OK);
            }
        }

        return new JsonResponse(["status" => "Unauthorizerd"], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/v1/formation", name="add_formation", methods={"POST"})
     */

    public function addFormation(Request $request, UserRepository $userRepository,  BoutiqueRepository $boutiqueRepository, InsertFileServices $insertFileServices)
    {
        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $pagedate = $request->request->get('page');

            $page = new Page();
            $title = $pagedate['title'];
            $price = $pagedate['price'] ? $pagedate['price'] : "";
            $category = $pagedate['category'] ? $pagedate['category'] : "formation";
            $resume = $pagedate['resume'];
            $description = $pagedate['description'];
            $file = $request->files->get('image');
            if ($file) {
                $image = $insertFileServices->insertFile($file, ['jpg', 'jpeg', 'gif', 'webm', 'png']);
                $page->setImage($image);
            }
            $page->setTitle($title);
            $page->setPrice($price);
            $page->setResume($resume);
            $page->setDescription($description);
            $page->setBoutique($boutique);
            $page->setCategory($category);
            $page->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $notification = new Notification();
            $notification->setSubject('REQUEST_APPROVAL_PAGE');
            $notification->setDescription($page->getId());
            $notification->setFromUser($this->getUser()->getId());
            $users = $userRepository->findAllWithRoleSuperAdmin("ROLE_SUPER_ADMIN");
            foreach ($users as $user) {
                $notification->addToUser($user);
                $data = $user->getData() != Null ? $user->getData() : ['notification' => []];
                if (!in_array($user->getId(), $data['notification'])) {
                    array_push($data['notification'], $user->getId());
                    $user->setData($data);
                    $nbrNotification = sizeof($data);
                    $user->setNbrNotification(intval($nbrNotification));
                    $this->entityManager->persist($user);
                }
            }

            $em->persist($notification);
            $em->flush();
            return new JsonResponse(["status" => "Success"], Response::HTTP_OK);
        }

        return new JsonResponse(["status" => "Unauthorizerd"], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/v1/video", name="add_video", methods={"POST"})
     */

    public function addVideo(Request $request, UserRepository $userRepository, BoutiqueRepository $boutiqueRepository)
    {
        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $video = new Video();
            $title = $request->request->get('title');
            $link = $request->request->get('link');
            $description = $request->request->get('description');

            $video->setTitle($title)
                ->setLink($link)
                ->setDescription($description)
                ->setBoutique($boutique);
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            $notification = new Notification();
            $notification->setSubject('REQUEST_APPROVAL_VIDEO');
            $notification->setDescription($video->getId());
            $notification->setFromUser($this->getUser()->getId());
            $users = $userRepository->findAllWithRoleSuperAdmin("ROLE_SUPER_ADMIN");
            foreach ($users as $user) {
                $notification->addToUser($user);
                $data = $user->getData() != Null ? $user->getData() : ['notification' => []];
                if (!in_array($user->getId(), $data['notification'])) {
                    array_push($data['notification'], $user->getId());
                    $user->setData($data);
                    $nbrNotification = sizeof($data);
                    $user->setNbrNotification(intval($nbrNotification));
                    $this->entityManager->persist($user);
                }
            }
            $em->persist($notification);
            $em->flush();
            return new JsonResponse(["status" => "Success"], Response::HTTP_OK);
        }

        return new JsonResponse(["status" => "Unauthorizerd"], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/v1/video/{id}", name="video", methods={"UPDATE"})
     */
    public function updateVideo(Request $request, BoutiqueRepository $boutiqueRepository, Video $video)
    {
        if ($video->getBoutique() == $boutiqueRepository->findOneBy(['user' => $this->getUser()])) {

            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $title = $request->request->get('title');
            $link = $request->request->get('link');
            $description = $request->request->get('description');

            $video->setTitle($title)
                ->setLink($link)
                ->setDescription($description)
                ->setBoutique($boutique);
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            return new JsonResponse(["status" => "Success"], Response::HTTP_OK);
        }

        return new JsonResponse(["status" => "Unauthorizerd"], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/v1/view/{id}", name="setView" ,methods={"POST"})
     */

    public function setView(int $id, NotificationRepository $notificationRepository)
    {


        if ($this->getUser()) {
            $notification = $notificationRepository->findOneBy(['id' => $id]);
            if ($notification) {
                $listViewNotification = [];
                $listViewNotification = $notification->getView();
                if (!in_array($this->getUser()->getId(), $listViewNotification)) {
                    array_push($listViewNotification, $this->getUser()->getId());
                    $notification->setView($listViewNotification);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    return new JsonResponse([], Response::HTTP_OK);
                }
                return new JsonResponse(["message" => "view exist"], Response::HTTP_UNAUTHORIZED);
            }
            return new JsonResponse(["message" => "not notif"], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }
}
