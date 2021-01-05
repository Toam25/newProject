<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {    
       $user= new User();

        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(["ROLE_USERS"]);
            $manager->persist($user);
            $manager->flush();
           return $this->redirectToRoute('home');
        }

        return $this->render('security/inscription.html.twig', [
            'forms' => $form->createView(),
        ]);
    }
   /**
    * @Route("/login", name="login")
    */
    public function login(AuthenticationUtils $authenticationUtils, Request $request){
        $error= $authenticationUtils->getLastAuthenticationError();
       // dd($error->getMessageKey());
        return $this->render('security/login.html.twig',[
            'error'=>$error
        ]); //new Response(json_encode(['error'=>$error]));
        
    }
    /**
    * @Route("/logout", name="logout")
    */
    public function logout(){
        
    }
}
