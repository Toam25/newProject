<?php 
  namespace App\EventListener;

use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class  RequestListener {

     private $tokenStorage;
     private $entityManagerInterface;
     private $boutiqueRepository;

     public function __construct(TokenStorageInterface $tokenStorageInterface, EntityManagerInterface $entityManagerInterface, BoutiqueRepository $boutiqueRepository )
     {
         $this->tokenStorage= $tokenStorageInterface;
         $this->entityManagerInterface =$entityManagerInterface;
         $this->boutiqueRepository=$boutiqueRepository;
     }

     public function onKernelRequest(RequestEvent $requestEvent){
        /* if(!$requestEvent->isMasterRequest()){
             return ;
         }
         else {*/
          
           if($this->tokenStorage->getToken()){
                $user= $this->tokenStorage->getToken()->getUser();
                if ($user instanceof UserInterface){
                    $boutique = $this->boutiqueRepository->findOneBy(['user'=>$user]);
                    $boutique->setLastActivityAt(new \DateTime());
                    $user->setLastActivityAt(new \DateTime());
                    $this->entityManagerInterface->flush($user);
                    $this->entityManagerInterface->flush($boutique);
    
                }   
           }
            
           /* if ($user instanceof UserInterface){
                $boutique = $this->boutiqueRepository->findOneBy(['user'=>$user]);
                $boutique->setLastActivityAt(new \DateTime());
               // $user->setLastActivityAt(new \DateTime());
               // $this->entityManagerInterface->flush($user);
                $this->entityManagerInterface->flush($boutique);

            }*/
           /* dd($user);
            
            */
            
       //  }
     }
  }