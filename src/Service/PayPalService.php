<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PayPal\Rest\ApiContext;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;

class PayPalService extends AbstractController
{
    private $ids = [
        'id' => "AXf3VcjO9dU6jLz1DyjZAXfvvHuWhsbZOEMnQMzJMwKVnwpj22CDnc5ayhutoD1nKf8SnVb18r___shW",
        'secret' => "EHV7UbwhL9ggzlfollPmjmqTb3DgMI1xzGMNrfDGerObIm6iWkzb74Bws2kX9DIQxhNwO5paFYNPR_r0"
    ];


    public function payment($price)
    {

        $apiContext = new ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $this->ids['id'],
                $this->ids['secret']
            )
        );

        $list = new \PayPal\Api\ItemList();
        $item  = (new \PayPal\Api\Item())
            ->setName("Changement d'offre")
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
            ->setCustom("offre");

        $payment = new \PayPal\Api\Payment();

        $payment->setTransactions([$trasaction]);
        $payment->setIntent('sale');

        $redirectUrls = (new \PayPal\Api\RedirectUrls())
            ->setReturnUrl("http://127.0.0.1:8000/pay")
            ->setCancelUrl("http://127.0.0.1:8000");

        $payment->setRedirectUrls($redirectUrls);
        $payment->setPayer((new Payer())->setPaymentMethod('paypal'));

        try {
            $payment->create($apiContext);
            return $this->redirect($payment->getApprovalLink());
        } catch (PayPalConnectionException $e) {
            dd(json_decode($e->getData()));
        }
    }

    public function getToken()
    {
        return $this->ids;
    }
}
