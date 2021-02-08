<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Header;
use App\Entity\Images;
use App\Entity\Menu;
use App\Repository\BoutiqueRepository;
use App\Repository\EsArticleRepository;
use App\Repository\HeaderRepository;
use App\Repository\MenuRepository;
use App\Service\CategoryOptionService;
use App\Service\InsertFileServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/es_article/get/{category}", name="get_es_article")
     */
    public function getEsArticle(string $category, EsArticleRepository $esArticleRepository)
    {
        $es_article = $esArticleRepository->findBy(['category' => $category]);
        $data = [];
        foreach ($es_article as $key => $value) {
            $data[$key]['type'] = $value->getType();
            $data[$key]['category'] = $value->getCategory();
            $data[$key]['image'] = $value->getImage();
        }
        return new Response(json_encode($data));
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
     * @Route("/add/article", name="add_article")
     */
    public function addArticle(Request $request, InsertFileServices $insertFileServices,BoutiqueRepository $boutiqueRepository)
    {
        
        if($this->getUser()){
            
           $category = $request->request->get('categorie');
           $article = new Article();
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
           $article->setBoutique($boutiqueRepository->findOneBy(['user'=>$this->getUser()]));

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
          
          $em= $this->getDoctrine()->getManager();
          $em->persist($article);
          $em->persist($img);
          $em->flush();
          $array = [
            'status' => 'success',
            'images' => $allImages,
            'name' => $article->getName(),
            'id' => $article->getId()
        ];
           return new JsonResponse($array,200);
        }
       
        return new JsonResponse(['status'=>'error','message'=>'not Authorized'],403);
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
    public function getSousCategoryType(string $category,MenuRepository $menuRepository, CategoryOptionService $categoryOptionService)
    {
         
        if ($this->getUser()) {
            $menu = $menuRepository->findBy(['category'=>$category]);

            return new JsonResponse(['status' => 'success','results'=>$categoryOptionService->getCategoryType($menu)],200);
        } 
        else {
            return new JsonResponse(['status' => 'error', 'message' => "Non authorise"], 403);
        }
    }

     /**
     * @Route("/get/list/type/{sous_categorie}", name="getListType")
     */
    public function getListType(string $sous_categorie,MenuRepository $menuRepository)
    {
         
        if ($this->getUser()) {
            $menus = $menuRepository->findBy(['sousCategory'=>$sous_categorie]);
           $list=[];
           foreach ($menus as $key => $menu) {
               $list[$key]=$menu->getName();
           }
            return new JsonResponse(['status' => 'success','results'=>$list],200);
        } 
        else {
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
                return new JsonResponse(['images' => $file]);
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
}
