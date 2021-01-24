<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{   
     /**
     * @Route("/inscription", name="registration")
     */
    public function registration(HttpFoundationRequest $request,UserRepository $userRepository, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {    
        $user = new User();
        $boutique = new Boutique();



        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allUsers = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($allUsers == NULL) {
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $user->setRoles(["ROLE_ADMIN"]);

                $boutique->setName('myBoutiqueName')
                    ->setType("SuperAdmin")
                    ->setAddress('myBoutiqueAdress')
                    ->setLink('myLinkForSiteWeb')
                    ->setMail('myBoutiqueAdressMailOf')
                    ->setContact('myAdressContact')
                    ->setApropos('DescriptionOfMyBoutque')
                    ->setUser($user);
                $manager->persist($user);
                $manager->persist($boutique);

                $manager->flush();
            } else {

                return new Response(json_encode(['status' => 'ko', 'msg' => 'Adresse mail existe']), 200, [
                    'Content-Type' => 'application/json'
                ]);
            }

            return new Response(json_encode(['status' => 'ok', 'msg' => 'Enregistrer avec succée']), 200, [
                'Content-Type' => 'application/json'
            ]);
        }

        return $this->render('security/inscription.html.twig', [
            'forms' => $form->createView(),
        ]);
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
