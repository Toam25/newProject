<?php 
  namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticlePerShopService extends AbstractController{
       
     private $categoryOptionService;
     public function __construct(CategoryOptionService $categoryOptionService)
     {
         $this->categoryOptionService=$categoryOptionService;
     }
     public  function getListArticlePerShop(array $articles){
       
         $list=[];
        
        foreach ($articles as $key => $article) {
            
            $isExiste = $this->categoryOptionService->in_array_type($article->getBoutique()->getName(),$list);
            if($isExiste!=-1){
                    array_push($list[$isExiste]['article'],[
                        'id'=> $article->getId(),
                        'images'=>$article->getImages()[0]->getName(),
                        'price'=>$article->getPrice(),
                        'referency'=> $article->getReferency(),
                        'vote'=>$article->getVotes()->getValues(),
                        'article'=>$article
                    ]);
            }
            else{
                array_push($list,[
                            'name' =>$article->getBoutique()->getName(),
                           'id'=> $article->getBoutique()->getId(),
                           'type'=>$article->getBoutique()->getType(),
                            'article'=>[ 
                                [
                                    'id'=> $article->getId(),
                                    'images'=>$article->getImages()[0]->getName(),
                                    'price'=>$article->getPrice(),
                                    'referency'=> $article->getReferency(),
                                    'vote'=>$article->getVotes()->getValues(),
                                    'article'=>$article
                                ]
                                  
                            ]
                ]);
            }
                // $list[$key]=[
                //       'boutique'=>[
                //            'name' =>$article->getBoutique()->getName(),
                //            'id'=> $article->getBoutique()->getId(),
                //            'type'=>$article->getBoutique()->getType(),
                //            'articles'=>[
                               
                //            ]
                //       ]
                // ];
        }
     
        return $list;
    }

  }