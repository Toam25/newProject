<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Boutique;
use App\Entity\EsArticle;
use App\Entity\Header;
use App\Entity\Images;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\UserCondition;
use App\Entity\UserVote;
use App\Entity\Vote;
use App\Repository\ArticleRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\EsArticleRepository;
use App\Repository\HeaderRepository;
use App\Repository\ImagesRepository;
use App\Repository\MenuRepository;
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
use App\Service\TypeOptionMenuService;
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
    /**
     * @Route("/es_article/get/{sous_category}", name="get_es_article")
     */
    public function getEsArticle(string $sous_category, EsArticleRepository $esArticleRepository)
    {
        $es_article = $esArticleRepository->findBy(['sous_category' => $sous_category]);
        $data = [];
        foreach ($es_article as $key => $value) {
            $data[$key]['type'] = $value->getType();
            $data[$key]['category'] = $value->getCategory();
            $data[$key]['image'] = $value->getImage();
        }
        return new Response(json_encode($data));
    }
   
    /**
     * @Route("/getOptions/{sous_category}", name="get_option")
     */
    public function getOption(string $sous_category,TypeOptionMenuService $typeOptionMenuService )
    {
        $listOption = $typeOptionMenuService->getOption($sous_category);
        
        
        return new JsonResponse($listOption,Response::HTTP_OK);
    }

    /**
     * @Route("/set/numberVoteIndex/{status}-{id_vote}", name="set_number_vote_index", methods={"POST"})
     */
    public function setNumberVoteIndex($status,$id_vote, VoteRepository $voteRepository, UserVoteRepository $userVoteRepository)
    {   
        $vote= $voteRepository->findOneBy(['id'=>$id_vote]);
        $userVote= $userVoteRepository->findOneBy(['vote'=>$vote,'user'=>$this->getUser()]);
        
        if($this->getUser() and $vote and $userVote==null){
            
            $userVote= new UserVote();
            if($status=="haut"){

                $vote->setNbrVote($vote->getNbrVote()+1);
                $vote->setPlacement($vote->getPlacement()+1);
              
                    
            }
            else{
                if($status=="bas"){
                    if($vote->getNbrVote()!=0){
                       $vote->setNbrVote($vote->getNbrVote()-1);
                    }
               }
            }
            $userVote->setVote($vote);
            $userVote->setUser($this->getUser());

            $em=$this->getDoctrine()->getManager();
            $em->persist($userVote);
            $em->flush();
            return new JsonResponse(['status'=>"success",'nbr_vote'=>$vote->getNbrVote()],Response::HTTP_OK);
        }
        
        return new JsonResponse(['status'=>"error"],Response::HTTP_UNAUTHORIZED);
    }

     /**
     * @Route("/profil/update/user/{id}", name="profil_update_user")
     */
    public function updateUser(Request $request, User $user, $id, InsertFileServices $insertFileServices)
    {
        if($this->getUser()->getId()== intVal($id)){
             $user->setName($request->request->get('name')??$user->getName());
             $user->setFirstname($request->request->get('first_name')??$user->getFirstname());
            
              if($request->files->get('images')){
                   
                   if(($user->getAvatar()!=="images_default/default_image.jpg")){
                            unlink("/images/".$user->getAvatar());
                   }
                   $user->setAvatar($insertFileServices->insertFile($request->files->get('images')));
               
              }
              $em=$this->getDoctrine()->getManager();
              $em->flush();
              return new JsonResponse(["status"=>"sucess"], Response::HTTP_OK);
        }
        else {
          return new JsonResponse(["status"=>"error"], Response::HTTP_UNAUTHORIZED);
        }
    }
       /**
     * @Route("/api/login/update/user/{id}", name="profil_password", methods="POST")
     */
    public function upPassWordUser(Request $request, User $user, $id, InsertFileServices $insertFileServices, UserPasswordEncoderInterface $encoder)
    {   
        
      
        if($this->getUser()->getId()== intVal($id) &&   $encoder->isPasswordValid($user,$request->request->get('a_password'))){
             
             $user->setEmail($request->request->get('mail'));

             if($request->request->get('new_password')!=""){
                $user->setPassword($encoder->encodePassword($user, $request->request->get('new_password')));
             }
            
              $em=$this->getDoctrine()->getManager();
              $em->flush();
              return new JsonResponse(["status"=>"sucess"], Response::HTTP_OK);
        }
        else {
          return new JsonResponse(["status"=>"error",'message'=>"Mot de passe incorect"], Response::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * @Route("/delete/boutique/{id}", name="deleteboutique")
     */
    public function deteteBoutique(Boutique $boutique, BoutiqueRepository $boutiqueRepository,UserRepository $userRepository, ArticleRepository $articleRepository)
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
           if($boutique->getLogo()!=="images_default/default_image.jpg"){
              //unlink('images/'.$boutique->getLogo());
            }
            $em = $this->getDoctrine()->getManager();
            $users=$boutique->getUser();
         
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
    public function updateArticle(Request $request,ArticleRepository $articleRepository,BoutiqueRepository $boutiqueRepository)
    {
        $article = $articleRepository->findOneBy(['id'=>$request->request->get('id-article')]);

        if($this->getUser() and $article){
           $article->setCategory($request->request->get('categorie'));
           $article->setName( $request->request->get('name'));
           $article->setPrice( $request->request->get('price'));
           $article->setPriceGlobal( $request->request->get('global_price'));
           $article->setPricePromo( $request->request->get('price_promo'));
           $article->setPromo( $request->request->get('promotion'));
           $article->setType( $request->request->get('type'));
           $article->setQuantity( $request->request->get('quantity'));
           $article->setMarque("");
           $article->setDescription( $request->request->get('description'));
           $article->setReferency( $request->request->get('referency'));
           $article->setSousCategory( $request->request->get('sous_category'));
           $article->setSlide($request->request->get('slider'));
           $article->setBoutique($boutiqueRepository->findOneBy(['user'=>$this->getUser()]));


          $em= $this->getDoctrine()->getManager();
          $em->flush();
          $array = [
            'status' => 'success',
            'name' => $article->getName(),
            'id' => $article->getId()
        ];
           return new JsonResponse($array,200);
        }
       
        return new JsonResponse(['status'=>'error','message'=>'not Authorized'],403);
    }
     /**
     * @Route("/add/article", name="add_article")
     */
    public function addArticle(Request $request, InsertFileServices $insertFileServices, BoutiqueRepository $boutiqueRepository)
    {     
       

        if ($this->getUser()) {

           
            $article = new Article();
            $article->setCategory($request->request->get('categorie'));
            $article->setName($request->request->get('name'));
            $article->setPrice($request->request->get('price'));
            $article->setPriceGlobal($request->request->get('global_price'));
            $article->setPricePromo($request->request->get('price_promo'));
            $article->setPromo($request->request->get('promotion'));

            $article->setType($request->request->get('type')??$request->request->get('sous_category'));
            $article->setQuantity($request->request->get('quantity'));
            $article->setMarque("");
            $article->setDescription($request->request->get('description'));
            $article->setReferency($request->request->get('referency'));
            $article->setSousCategory($request->request->get('sous_category'));
            $article->setSlide($request->request->get('slider'));
            $article->setBoutique($boutiqueRepository->findOneBy(['user' => $this->getUser()]));
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
    public function getSousCategoryType(string $category, MenuRepository $menuRepository, CategoryOptionService $categoryOptionService)
    {

        if ($this->getUser()) {
            $menu = $menuRepository->findBy(['category' => $category]);

            return new JsonResponse(['status' => 'success', 'results' => $categoryOptionService->getCategoryType($menu)], 200);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => "Non authorise"], 403);
        }
    }

    /**
     * @Route("/get/article/{id}", name="getOneArtilce")
     */
    public function getOneArticle(CategoryOptionService $categoryOptionService, BoutiqueRepository $boutiqueRepository, ArticleRepository $articleRepository, int $id, MenuRepository $menuRepository)
    {

        if ($this->getUser()) {
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $article = $articleRepository->findOneArticleByBoutiqueWithImage($id, $boutique);
            $list_menu = $categoryOptionService->getCategoryType($menuRepository->findBy(['category' => $article->getCategory()]));
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
                'slider'=>$article->getSlide(),
                'images' => [
                    'name' => $image[0]->getName(),
                    'id' => $image[0]->getId()
                ],
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
            $file = $insertFileServices->insertFile($images, ['jpeg', 'jpg', 'gif', 'png','webp']);
            $images= $imagesRepository->findOneBy(['id'=>$request->request->get('id-image')]);
    
            if ($file != false) {
                $images= $imagesRepository->findOneBy(['id'=>$request->request->get('id-image')]);
                $images->setName($file);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                unlink('images/'.$images->getName());
                return new JsonResponse(['images' => $file,'id'=>$images->getId()]);
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
                return new JsonResponse(['images' => $file,"id"=>$header->getId()]);
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
            $header= $headerRepository->findOneBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])]);
            
            if($header){
                unlink('images/'.$header->getName());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($header);
                $entityManager->flush();
                return new JsonResponse(['images'=>'images/images_default/default_image.jpg','status' => 'success'], Response::HTTP_OK);
            }
            else{
                return new JsonResponse(['status' => 'error'], Response::HTTP_NOT_EXTENDED);
            }
       
    }
     /**
     * @Route("/get/listArticlePerBoutique/{category}/{type}", name="listeArticlePerBoutique", methods={"GET"})
     */
    public function listArticlePerBoutique($category, $type, ArticleRepository $articleRepository, ArticlePerShopService $articlePerShopService): Response
    {
            $articles = $articleRepository->findAllArticleBySousCategory($category,$type);

            

            return new JsonResponse($articlePerShopService->getListArticlePerShop($articles), Response::HTTP_OK);
        
    }
}
