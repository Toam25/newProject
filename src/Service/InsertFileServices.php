<?php 
  namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InsertFileServices extends AbstractController{
       
       public function insertFile($file,array $type=[]){
            if(sizeof($type)>0){
               $extension = $file->guessExtension();

               if(in_array(strtolower($extension),$type)){
                    $newFile = md5(uniqid()).'.'.$extension;
                    $file->move($this->getParameter('images_directory'),
                    $newFile);
                    return $newFile;
               }
               else{
                    return new JsonResponse(['status'=>'error'],Response::HTTP_NOT_ACCEPTABLE);
               }
               
            }
            else{
               $extension = $file->guessExtension();
               $newFile = md5(uniqid()).'.'.$extension;
               $file->move($this->getParameter('images_directory'),
               $newFile);
               return $newFile;
            }
            
       }
  
  }