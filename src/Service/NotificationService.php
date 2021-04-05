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
          return  $this->notificationRepository->findNotificationBy($this->getUser());
     }
}