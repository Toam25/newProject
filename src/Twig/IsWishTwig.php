<?php 
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class IsWishTwig extends AbstractExtension{
       

    private $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;

    }
    public function getFunctions()
    {
        return [
             new TwigFunction('isWish',[$this, 'getCart'],['is_safe'=>['html']])
        ];
    }

    public function getCart(Object $carts, $user){
        
    
       $istrue="false";
       foreach($carts as $cart){
            if($cart->getType()=='wish'){
                if($cart->getUser()==$user){
                    $istrue="true";
                    break;
                }
                
            }
       }

      return $this->twig->render('partials/istrueTwig.html.twig',[
            'istrue'=> $istrue
        ]);
    }
}