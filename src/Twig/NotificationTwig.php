<?php 
namespace App\Twig;

use App\Service\NotificationService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class NotificationTwig extends AbstractExtension{
       
    private $twig;
    private $notificationService;

    public function __construct(  Environment $twig, NotificationService $notificationService)
    {
        
        $this->twig = $twig;
        $this->notificationService=$notificationService;
        

    }
    public function getFunctions()
    {
        return [
             new TwigFunction('notification',[$this, 'getNotification'],['is_safe'=>['html']])
        ];
    }
    public function getNotification(String $type = 'nbr'){
        
        return $this->twig->render('partials/notification.html.twig', [
            'notifications' => $this->notificationService->getNotification(), 
            'type'=> $type
        ]);
    }
}