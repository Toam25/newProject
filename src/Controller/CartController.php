<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\BoutiqueRepository;
use App\Repository\CartRepository;
use App\Service\Cart\CartService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{

    /**
     * @Route("/validate" , name="validate", methods={"POST"})
     */


    public function vaidate(CartRepository $cartRepository, MailerInterface $mailer)
    {

        $carts = $cartRepository->findBy(['user' => $this->getUser()]);
        $user = $this->getUser();
        if ($user) {
            foreach ($carts as $key => $cart) {
                $boutique = $cart->getBoutique();
                $email = (new TemplatedEmail())
                    ->from($user->getEmail())
                    ->to($boutique->getMail())
                    ->subject('Merçi d\'être parmi nous')
                    ->htmlTemplate('email/cart.html.twig')
                    ->context([
                        'boutique' => $boutique,
                        'cart' => $cart
                    ]);
                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    return new JsonResponse("Erreur de connexion au serveur", HttpFoundationResponse::HTTP_UNAUTHORIZED);
                }
            }
            return new JsonResponse(['status' => "ok", "message" => "Enregistrer avec success"]);
        }
        return new JsonResponse(['status' => "ko", "message" => "Enregistrer avec success"], HttpFoundationResponse::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/add/{id}" , name="add_cart")
     */


    public function add(CartService $cartService,  Article $article, Request $request)
    {

        $type = $request->get('type');
        $status = $request->get('status');

        return new HttpFoundationResponse($cartService->addCart($article, $this->getUser(), $type, $status), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/show/{type}" , name="show_cart")
     */
    public function show($type, CartService $cart, CartRepository $cartRepository, BoutiqueRepository $boutiqueRepository)
    {

        if ($this->getUser()) {

            // $carts = $cart->getCartPerBoutique($cartRepository->findAllByUser($this->getUser(), $type));

            return $this->render("boutique/cart.html.twig", [
                'carts' => $cartRepository->findAllByUser($this->getUser(), $type),
                'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
                'total' => $cart->getTotal($cartRepository->findAllByUser($this->getUser(), $type)),
                'type' => $type
            ]);
        } else {
            return $this->render("security/login.html.twig", []);;
        }
    }
    /**
     * @Route("/remove/{id}",name="remove_cart")
     */
    public function remove(CartService $cartService, $id)
    {

        return new HttpFoundationResponse($cartService->removeCart($this->getUser(), $id));
    }
    /**
     * @Route("/update/{id}-{type}", name="uptatetype")
     */

    public function updateTypeCart(CartService $cartService, $type, $id)
    {

        return new HttpFoundationResponse($cartService->updateTypeCart($this->getUser(), $type, $id));
    }
    /**
     * @Route("/removeOneItems/{id}",name="removeOneItems_cart")
     */
    public function removeOneItems(CartService $cartService, $id)
    {

        return new HttpFoundationResponse($cartService->removeOneItem($this->getUser(), $id));
    }
}
