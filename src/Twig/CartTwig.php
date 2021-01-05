<?php 
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\BoutiqueRepository;
use App\Repository\CartRepository;
use App\Service\Cart\CartService;
use Twig\Environment;

class CartTwig extends AbstractExtension{
       
    private $twig;
    private $cart;
    private $cartRepository;

    public function __construct( CartService $cart, BoutiqueRepository $boutiqueRepository,CartRepository $cartRepository, Environment $twig)
    {
        $this->boutiqueRepository=$boutiqueRepository;
        $this->twig = $twig;
        $this->cart = $cart;
        $this->cartRepository = $cartRepository;

    }
    public function getFunctions()
    {
        return [
             new TwigFunction('mycart',[$this, 'getCart'],['is_safe'=>['html']])
        ];
    }

    public function getCart($user,string $type, string $totalOrQuantity){
        
        $quatityOrTotal="";
        
        if($type=='wish'){
            $quatityAndTotal=$this->cart->getTotal($this->cartRepository->findAllByUserAndWish($user,$type));
            
        }
        else{
            $quatityAndTotal=$this->cart->getTotal($this->cartRepository->findAllByUserAndPanier($user,$type));
        }

        $quatityOrTotal="";
        if($totalOrQuantity=='total'){
            $quatityOrTotal=$quatityAndTotal['totalCart'];
        }
        if($totalOrQuantity=='quantity'){
            $quatityOrTotal=$quatityAndTotal['quantity'];
        }
        return $this->twig->render('partials/cartTwig.html.twig',[
            'quatityOrTotal'=> $quatityOrTotal
        ]);
    }
}