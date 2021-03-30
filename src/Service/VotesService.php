<?php

namespace App\Service;

use App\Data\Search;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class VotesService
{

    private $articleRepository;
    private $security;

    public function __construct(Security $security)
    {
        $this->security=$security;
    }
     /**
     * @return array 
     */
    public function getNumberTotalVote(array $votes)
    {
        $nbr = 0;
        $five = 0;
        $four = 0;
        $tree = 0;
        $two = 0;
        $one = 0;
        $array = [];
       
        $user = [
            'isComment' => false
        ];
        for ($i = 0; $i < sizeof($votes); $i++) {
            if ($votes[$i]->getValue() == 5) {
                $five++;
            }
            if ($votes[$i]->getValue() == 4) {
                $four++;
            }
            if ($votes[$i]->getValue() == 3) {
                $tree++;
            }
            if ($votes[$i]->getValue() == 2) {
                $two++;
            }
            if ($votes[$i]->getValue() == 1) {
                $one++;
            }
            if ($votes[$i]->getUser() ==  $this->security->getUser()) {
                $user = [
                    'isComment' => true,
                    'comment' => $votes[$i]->getComment(),
                    'value' => $votes[$i]->getValue(),
                    'id'=>$votes[$i]->getId(),
                    'owner'=>$votes[$i]->getUser()->getId()
                ];
            }
            $nbr += $votes[$i]->getValue();
        }
        return [
            'user' => $user,
            'votes' => [
                'five' => $five,
                'four' => $four,
                'tree' => $tree,
                'two' => $two,
                'one' => $one,
                'total' => $nbr,
            ]
        ];
    }

    
}
