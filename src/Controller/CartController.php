<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\BoutiqueRepository;
use App\Repository\CartRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{   
   /**
    * @Route("/add/{id}" , name="add_cart")
    */

    
   Public function add(CartService $cartService,  Article $article,Request $request){

     $type = $request->get('type');
     $status = $request->get('status');
     
     return new HttpFoundationResponse($cartService->addCart($article, $this->getUser(),$type,$status),200,[
        'Content-Type'=>'application/json'
    ]);
   }

   /**
    * @Route("/show/{type}" , name="show_cart")
    */
    Public function show($type,CartService $cart,CartRepository $cartRepository, BoutiqueRepository $boutiqueRepository){
       
        if($this->getUser()) {
            return $this->render("boutique/cart.html.twig" ,[
                'carts'=>$cartRepository->findAllByUser($this->getUser(),$type),
                'boutique'=>$boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
                'total'=>$cart->getTotal($cartRepository->findAllByUser($this->getUser(), $type)),
                'type'=>$type
            ]);
        }
        else{
            return $this->render("security/login.html.twig" ,[
                
            ]);;
        }
     }
    /**
     * @Route("/remove/{id}",name="remove_cart")
     */
    public function remove(CartService $cartService, $id){

        return new HttpFoundationResponse($cartService->removeCart($this->getUser(),$id));
    }
    /**
     * @Route("/update/{id}-{type}", name="uptatetype")
     */

     public function updateTypeCart(CartService $cartService, $type, $id){

        return new HttpFoundationResponse($cartService->updateTypeCart($this->getUser(),$type,$id));
     }
    /**
     * @Route("/removeOneItems/{id}",name="removeOneItems_cart")
     */
    public function removeOneItems(CartService $cartService, $id){

        return new HttpFoundationResponse($cartService->removeOneItem($this->getUser(),$id));
    }
}
