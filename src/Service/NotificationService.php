<?php 
  namespace App\Service;

use App\Repository\NotificationRepository;
use App\Repository\NotifyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationService extends AbstractController{
       
    private $notificationRepository;
     public function __construct(NotificationRepository $notificationRepository)
     {
          $this->notificationRepository=$notificationRepository;
         
     }
     public function getNotification(){
          if($this->getUser()){
               return  $this->notificationRepository->findNotificationBy($this->getUser());
          }
          return [];
     }

     public function getMessageNotification(){
          $notification=[];
          foreach ($this->getNotification() as $key => $value) {
               if($value->getSubject()=="APROUVED_IN_HOME_PAGE"){
                    array_push($notification,[ 
                         "message"=>"Votre blog est ajouté dans le home pages avec success",
                         "id_blog"=>(int)$value->getDescription(),
                         ]);
               }
               else if($value->getSubject()=="APROUVED"){
                    array_push($notification,["message"=>"Votre blog est approuver avec success","id_blog"=>(int)$value->getDescription()]);

               }
          }
          return $notification;
     }
}