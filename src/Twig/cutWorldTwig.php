<?php 
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class cutWorldTwig extends AbstractExtension{
       
    private $twig;

    public function __construct(  Environment $twig)
    {
        
        $this->twig = $twig;

    }
    public function getFunctions()
    {
        return [
             new TwigFunction('cutWorld',[$this, 'getWorldCut'],['is_safe'=>['html']])
        ];
    }

    public function getWorldCut(string $description,$max_caracteres,$coup =true){
         
        if (strlen($description)>$max_caracteres)
        {   
            // Séléction du maximum de caractères
            $description = substr($description, 0, $max_caracteres);
            // Récupération de la position du dernier espace (afin déviter de tronquer un mot)
            if($coup==true){
                $position_espace = strrpos($description, " ");   
                $description = substr($description, 0, $position_espace);    
            }
            // Ajout des "..."
            $description = $description."...";
        }
        return $this->twig->render('partials/cutworld.html.twig',[
            'description'=> $description
        ]);
    }
}