<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/message", name="api.message.")
 */
class MessageApiController extends AbstractController
{
    /**
     * @Route("/{id}", name="one_message", methods = {"GET"})
     */

    public function getOneMessage()
    {
    }
}
