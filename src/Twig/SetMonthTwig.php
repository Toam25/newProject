<?php 
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class SetMonthTwig extends AbstractExtension{
       
    private $twig;

    public function __construct(  Environment $twig)
    {
        
        $this->twig = $twig;

    }
    public function getFunctions()
    {
        return [
             new TwigFunction('setMonth',[$this, 'setMonth'],['is_safe'=>['html']])
        ];
    }

    public function setMonth(string $month){
         
        $monthLiteraly = ['Janvier','Fervrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'];
         
        return $this->twig->render('partials/month.html.twig',[
            'month'=> $monthLiteraly[intval($month)-1]
        ]);
    }
}