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

    public function getCategoryType(array $categories){
          $list=[];
          foreach ($categories as $key => $category) {

               $keylist=$this->in_array_type($category->getSousCategory(),$list);
               if($keylist==-1){
                    array_push($list,[
                                        'type'=>$category->getSousCategory(),
                                        'option'=>[$category->getName()]
          
                                      ]);
                }
                else{
                  array_push($list[$keylist]['option'],$category->getName());
                }
          
          }
         return $list;
        
    }

    public function in_array_type($value,$arr){
     $i=0;
     foreach($arr as $arr1)
         {
             if(in_array($value,$arr1))
             {
                return $i;
             }
        $i++;
         }
     return -1;
     
 }
  
  }