<?php
namespace App\Service\Cart;
use App\Entity\Article;
use App\Entity\Cart;
use App\Entity\User;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartService {
   
   private $manager;
   private $cartRepository;
   public function __construct(CartRepository $cartRepository,EntityManagerInterface $manager)
   {
       $this->manager=$manager;
       $this->cartRepository=$cartRepository;

   }
    public function addCart(Article $article,User $user,string $type,string $status){
        
       
        $cart= $this->cartRepository->findOneByArticleWithUser($user,$article,$type);
         
        if($cart){
            $cart->setQuantity($cart->getQuantity()+1);
            $this->manager->flush();
        }
        else{
            $cart = new Cart;
            $cart->addArticle($article)
            ->setUser($user)
            ->setQuantity(1)
            ->setStatus($status)
            ->setType($type);
            $this->manager->persist($cart);
            $this->manager->flush();
        }

        return json_encode([
            'status'=>"success",
            'idCart'=>$cart->getId()

            ]);
    }
 

    public function removeCart(User $user, int $idCart){
        $cart=$this->cartRepository->findOneByIdCartWithUser($user, $idCart);
        $_cart = ( $cart!=Null) ? $cart : null;
        if($_cart!=Null){
            $this->manager->remove($_cart);
            $this->manager->flush();
            return json_encode(['status'=>'success']);
        }
        else{
            return json_encode(['status'=>'cart not exist']);
        }
         
    }
  public function updateTypeCart(User $user,string $type, int $idCart){
     
     
     $cart=$this->cartRepository->findOneByIdCartWithUser($user, $idCart);
     $article=$cart->getArticles();
     $idArticle = $article[0]->getId();
     $cartInDb = $this->cartRepository->findOneByIdArticleWithUser($user,'panier', $idArticle);
   
     if($cartInDb){
        $cartInDb->setQuantity($cartInDb->getQuantity()+1);
        $this->manager->remove($cart);
        $this->manager->flush();

     }
     else{
     
        $_cart = ( $cart!=Null) ? $cart : null;
        if($_cart!=Null){
            $_cart->setType('cart');
            $_cart->setStatus($type);
            $this->manager->flush();
            return json_encode(['status'=>'success']);
        }
        else{
            return json_encode(['status'=>'cart not exist']);
        }
     }
     
  }
    public function removeOneItem(User $user, int $idCart){
        $cart=$this->cartRepository->findOneByIdCartWithUser($user, $idCart);
        $_cart = ( $cart!=Null) ? $cart : null;
        if($_cart!=Null){
            $cart->setQuantity($_cart->getQuantity()-1);
            $this->manager->flush();
            return json_encode(['status'=>'success']);
        }
        else{
            return json_encode(['status'=>'cart not exist']);
        }
    }
    public function getTotal(array $carts): array{
    
        $quatity =0;
        $totalCart=0;
 
        for ( $i=0; $i<sizeof($carts); $i++ ){
               $quatity+=$carts[$i]->getQuantity();
               $totalCart+=$carts[$i]->getArticles()[0]->getPrice()*$carts[$i]->getQuantity();
        }

        return [
                 'totalCart'=>$totalCart,
                 'quantity'=>$quatity
             ];
    }

}