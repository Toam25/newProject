<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InsertFileServices extends AbstractController
{

     public function insertFile($file, array $type = [], $path = null)
     {
          $path = $path != null ? $path : $this->getParameter('images_directory');
          if (sizeof($type) > 0) {
               $extension = $file->guessExtension();

               if (in_array(strtolower($extension), $type)) {
                    $newFile = md5(uniqid()) . '.' . $extension;
                    $file->move(
                         $path,
                         $newFile
                    );
                    return $newFile;
               } else {
                    return new JsonResponse(['status' => 'error', "message" => "Fichier no valide"], Response::HTTP_NOT_ACCEPTABLE);
               }
          } else {
               $extension = $file->guessExtension();
               $newFile = md5(uniqid()) . '.' . $extension;
               $file->move(
                    $path,
                    $newFile
               );
               return $newFile;
          }
     }
}
