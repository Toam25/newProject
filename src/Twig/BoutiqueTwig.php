<?php 
namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\BoutiqueRepository;
use Twig\Environment;

class BoutiqueTwig extends AbstractExtension{
       
    private $boutiqueRepository;
    private $twig;



    public function __construct(BoutiqueRepository $boutiqueRepository, Environment $twig)
    {
        $this->boutiqueRepository=$boutiqueRepository;
        $this->twig = $twig;
    }
    public function getFunctions()
    {
        return [
             new TwigFunction('my_boutique_twig',[$this, 'getMyBoutique'],['is_safe'=>['html']])
        ];
    }

    public function getMyBoutique($user,$filter){
        $result = $this->boutiqueRepository->findOneBy(['user'=>$user]);
        
        return $this->twig->render('partials/myBoutiqueTwig.html.twig',[
            'results'=>$result,
            'filter'=>$filter
        ]);
    }
}