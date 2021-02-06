<?php 
  namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryOptionService extends AbstractController{
       
   
     public  function getListPerCategory(array $listCategories){
       
         $list=[];
         foreach ($listCategories as $key => $listCategory) {
               if(key_exists($listCategory->getSousCategory(),$list)){
                    array_push($list[$listCategory->getSousCategory()],$listCategory);
               }
               else{
                   $list[$listCategory->getSousCategory()]=[
                        $listCategory
                   ];
               }
               
         }
     
        return $list;
    }
  
  }