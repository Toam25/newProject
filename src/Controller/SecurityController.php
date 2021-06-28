<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\Notification;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\BoutiqueRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="registration")
     */
    public function registration(HttpFoundationRequest $request, MailerInterface $mailer, HttpClientInterface $httpClient, UserRepository $userRepository, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $boutique = new Boutique();



        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {

            $SECRET_KEY = "0x026EF02b5283Bf6656BFb0D67E06fF70a610395a";
            $VERIFY_URL = "https://hcaptcha.com/siteverify";
            $htoken = $request->request->get('h-captcha-response');

            $data = [
                'secret' => $SECRET_KEY,
                'response' => $htoken
            ];

            $curlconfig = [
                CURLOPT_URL => $VERIFY_URL,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HEADER => false
            ];
            //$response= $httpClient->request('POST',$VERIFY_URL,[
            //  'query'=>$data
            //  ]
            //  );

            $ch = curl_init();
            curl_setopt_array($ch, $curlconfig);
            $response = curl_exec($ch);
            curl_close($ch);

            $responseData = json_decode($response);
            // dd($response);
            // $response_json = json_encode($response);
            // $success=$response_json['success'];

            if ($responseData->success) {
                $allUsers = $userRepository->findOneBy(['email' => $user->getEmail()]);
                if ($allUsers == NULL) {
                    $hash = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($hash);
                    $user->setRoles(["ROLE_USER"]);
                    $user->setConfimation(md5(uniqid('ta')));

                    /* $boutique->setName('myBoutiqueName')
                    ->setType("SuperAdmin")
                    ->setAddress('myBoutiqueAdress')
                    ->setLink('myLinkForSiteWeb')
                    ->setMail('myBoutiqueAdressMailOf')
                    ->setContact('myAdressContact')
                    ->setApropos('DescriptionOfMyBoutque')
                    ->setUser($user);
                $manager->persist($boutique);

                */

                    $email = (new TemplatedEmail())
                        ->from('toutenone@toutenone.com')
                        ->to($user->getEmail())
                        ->subject('Merci d\'être parmi nous')
                        ->htmlTemplate('email/confirmation.html.twig')
                        ->context([
                            'user' => $user
                        ]);
                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        return new JsonResponse("Erreur de connexion au serveur", Response::HTTP_UNAUTHORIZED);
                    }
                    $notification = new Notification();
                    $notification->setSubject('NEW USER');
                    $notification->setDescription($user->getName() . " " . $user->getFirstname());
                    $notification->setFromUser(0);
                    $users = $userRepository->findAllWithRoleSuperAdmin("ROLE_SUPER_ADMIN");
                    foreach ($users as $user) {
                        $notification->addToUser($user);
                    };
                    $manager->persist($notification);
                    $manager->persist($user);
                    $manager->flush();
                } else {

                    return new JsonResponse('Adresse mail existe', Response::HTTP_UNAUTHORIZED);
                }

                return new JsonResponse($user->getEmail(), 200);
            }
            return new JsonResponse('Erreur d\'enregistrement', 301);
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
    /**
     * @Route("/confirm-me/{token}", name="confirmationUser")
     */
    public function confirmationUser($token, UserRepository $userRepository, BoutiqueRepository $boutiqueRepository)
    {
        $user = $userRepository->findOneBy(['confimation' => $token]);
        $status = "error";
        if ($user) {

            $user->setConfimation(Null);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $status = "success";
        }
        return $this->render("boutique/confirmation.html.twig", [
            'status' => $status,
            'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN')
        ]);
    }

    //  /**
    //    *@Route("/testemail", name="testemail")
    //    */
    //   public function testeMail(MailerInterface $mailer, UserRepository //$userRepository){
    //     $user=$userRepository->findOneBy(['id'=>1]);
    //       $user->setConfimation('amkjmlaj');
    //      $email = (new TemplatedEmail())
    //  ->from('toutenone@toutenone.com')
    // ->to("toarymanana@gmail.com")
    //   ->subject('Merçi d\'être parmi nous')
    // ->htmlTemplate('email/confirmation.html.twig')
    //   ->context([
    //       "user"=>$user
    //   ]);
    // $mailer->send($email);

    //   return  new Response('Ok');

    // }
}
