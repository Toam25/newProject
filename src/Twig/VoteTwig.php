<?php 
namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class VoteTwig extends AbstractExtension{
       
 
    private $twig;




    public function __construct( Environment $twig)
    {

        $this->twig = $twig;
    }
    public function getFunctions()
    {
        return [
             new TwigFunction('my_vote_twig',[$this, 'getNumberVote'],['is_safe'=>['html']])
        ];
    }

    public function getNumberVote($vote){
       
        
       
        if(is_int($vote)){
            $nbrVote=$vote;
        }
        else{
            $totalVote=sizeof($vote);
            $total=0;
            if( sizeof($vote)!=0 ){

                for($i=0; $i<$totalVote;$i++){
                    $total = $total + $vote[$i]->getValue();
                }
            }
            else{
                $totalVote=1;
            }
            
            $nbrVote = $total/$totalVote;
        }
        

        return $this->twig->render('partials/myVoteTwig.html.twig',[
            'vote'=>$nbrVote,
        ]);
    }
}