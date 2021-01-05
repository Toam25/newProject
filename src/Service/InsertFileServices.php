<?php 
  namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
                    return false;
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