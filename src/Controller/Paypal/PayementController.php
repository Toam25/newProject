<?php

namespace App\Controller\Paypal;

use App\Repository\BoutiqueRepository;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use PayPal\Rest\ApiContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PayementController extends AbstractController
{


    /**
     * @Route("/payment/{offer}", name="payment")
     */

    public function index(string $offer)
    {
        if ($this->getUser() == null) {
            $this->addFlash("warning", "Veillez vous connecter avec votre boutique");

            return $this->redirectToRoute('my_offer');
        }

        $returnUrl = $this->generateUrl('pay', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->generateUrl('index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        if ($offer == "premium") {
            $price = intval(150);
        } else if ($offer == "vip") {
            $price = intval(25);
        }
        $apiContext = new ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                "AXf3VcjO9dU6jLz1DyjZAXfvvHuWhsbZOEMnQMzJMwKVnwpj22CDnc5ayhutoD1nKf8SnVb18r___shW",
                "EHV7UbwhL9ggzlfollPmjmqTb3DgMI1xzGMNrfDGerObIm6iWkzb74Bws2kX9DIQxhNwO5paFYNPR_r0"
            )
        );

        $list = new \PayPal\Api\ItemList();
        $item  = (new \PayPal\Api\Item())
            ->setName($offer)
            ->setPrice($price)
            ->setCurrency("EUR")
            ->setQuantity(1);

        $list->addItem($item);

        $detail = (new \PayPal\Api\Details())
            ->setSubtotal($price);


        $amout = (new \PayPal\Api\Amount())
            ->setTotal($price)
            ->setCurrency("EUR")
            ->setDetails($detail);


        $trasaction = (new \PayPal\Api\Transaction())
            ->setItemList($list)
            ->setDescription("Changement d'offre dans notre site toutenone.com")
            ->setAmount($amout)
            ->setCustom("Teste");

        $payment = new \PayPal\Api\Payment();

        $payment->setTransactions([$trasaction]);
        $payment->setIntent('sale');

        $redirectUrls = (new \PayPal\Api\RedirectUrls())
            ->setReturnUrl($returnUrl)
            ->setCancelUrl($cancelUrl);

        $payment->setRedirectUrls($redirectUrls);
        $payment->setPayer((new Payer())->setPaymentMethod('paypal'));

        try {
            $payment->create($apiContext);
            return $this->redirect($payment->getApprovalLink());
        } catch (PayPalConnectionException $e) {
            dd(json_decode($e->getData()));
        }



        return $this->redirectToRoute('my_offer');
    }

    /**
     * @Route("/pay", name="pay")
     */

    public function pay(Request $request, BoutiqueRepository $boutiqueRepository)
    {

        $apiContext = new ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                "AXf3VcjO9dU6jLz1DyjZAXfvvHuWhsbZOEMnQMzJMwKVnwpj22CDnc5ayhutoD1nKf8SnVb18r___shW",
                "EHV7UbwhL9ggzlfollPmjmqTb3DgMI1xzGMNrfDGerObIm6iWkzb74Bws2kX9DIQxhNwO5paFYNPR_r0"
            )
        );


        $payment = Payment::get($request->query->get('paymentId'), $apiContext);

        $name = $payment->getTransactions()[0]->getItemList()->getItems()[0]->getName();
        $price = $payment->getTransactions()[0]->getItemList()->getItems()[0]->getPrice();

        $execution = (new PaymentExecution())
            ->setPayerId($request->query->get('PayerID'))
            ->setTransactions($payment->getTransactions());

        try {
            $payment->execute($execution, $apiContext);
            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $offer = "";
            if ($name == "vip" and $price == 10) {
                $offer = "Vip";
            } else if ($name == "premium" and $price == 20) {
                $offer = "Premium";
            }
            $boutique->setOffer($offer);
            $boutique->setOfferCreatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Votre offre est change en : ' . $offer);
        } catch (PayPalConnectionException $e) {
            dd(json_decode($e->getData()));
        }

        return $this->redirectToRoute('home');
    }
}
