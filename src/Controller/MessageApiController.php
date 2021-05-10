<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/message", name="api.message.")
 */
class MessageApiController extends AbstractController
{
    /**
     * @Route("/{id}", name="one_message", methods = {"GET"})
     */

    public function getOneMessage(Boutique $boutique): Response
    {

        return  new JsonResponse([
            'id' => $boutique->getId(),
            'name' => $boutique->getName(),
            'image' => $boutique->getLogo(),
            'data' => []
        ]);
    }
}
