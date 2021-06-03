<?php

namespace App\Service;

use App\Repository\NotificationRepository;
use App\Repository\NotifyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationService extends AbstractController
{

     private $notificationRepository;
     public function __construct(NotificationRepository $notificationRepository)
     {
          $this->notificationRepository = $notificationRepository;
     }
     public function getNotification()
     {
          if ($this->getUser()) {
               return  $this->notificationRepository->findNotificationBy($this->getUser());
          }
          return [];
     }

     public function getNbrNotificationNotViewByUser()
     {
          $nbr = 0;

          if ($this->getUser()) {
               $notifications =  $this->getNotification();
               foreach ($notifications as  $notification) {
                    if (!in_array($this->getUser()->getId(), $notification->getView())) {
                         $nbr = $nbr + 1;
                    }
               }
          }

          return $nbr;
     }

     public function getMessageNotification()
     {
          $notifications = [];

          foreach ($this->getNotification() as $notification) {
               if ($notification->getSubject() == "APPROUVED_IN_HOME_PAGE") {
                    array_push($notifications, [
                         "message" => "Votre blog est ajouté dans le home pages avec success",
                         'idNotification' => (int)$notification->getId(),
                         "idBlog" => (int)$notification->getDescription(),
                         'status' => 'APPROUVED_BLOG',
                         'createdat' => $notification->getCreatedAt()->getTimesTamp(),
                         'isView' => in_array($this->getUser()->getId(), $notification->getView())
                    ]);
               } else if ($notification->getSubject() == "APPROUVED") {
                    array_push($notifications, [
                         "message" => "Votre blog est approuver avec success",
                         "idBlog" => (int)$notification->getDescription(),
                         'idNotification' => (int)$notification->getId(),
                         'status' => 'APPROUVED_BLOG',
                         'createdat' => $notification->getCreatedAt()->getTimesTamp(),
                         'isView' => in_array($this->getUser()->getId(), $notification->getView())
                    ]);
               } else if ($notification->getSubject() == "REQUEST_APPROVAL_BLOG") {
                    array_push($notifications, [
                         "message" => "Une demande d'approuver un blog a été posté",
                         "idBlog" => (int)$notification->getDescription(),
                         'status' => 'REQUEST_APPROVAL_BLOG',
                         'idNotification' => (int)$notification->getId(),
                         'createdat' => $notification->getCreatedAt()->getTimesTamp(),
                         'isView' => in_array($this->getUser()->getId(), $notification->getView())
                    ]);
               } else if ($notification->getSubject() == "NEW USER") {
                    array_push($notifications, [
                         "message" => $notification->$notification->getDescription() . "  nous a rejoint",
                         "idBlog" => "",
                         'status' => 'NEW USER',
                         'idNotification' => (int)$notification->getId(),
                         'createdat' => $notification->getCreatedAt()->getTimesTamp(),
                         'isView' => in_array($this->getUser()->getId(), $notification->getView())
                    ]);
               }
          }
          return $notifications;
     }
}
