<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\User;
use App\Repository\BoutiqueRepository;
use App\Service\CheckConversationService;
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

    public function getOneMessage(BoutiqueRepository $boutiqueRepository, int $id, CheckConversationService $checkConversationService): Response
    {
        $boutique = $boutiqueRepository->findOneBy(['id' => $id]);
        if (is_null($boutique)) {
            return  new JsonResponse([
                'status' => 'ko',
                'message' => 'shop undefind'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $conversation = $checkConversationService->checkConversation($boutique->getUser()->getId());

        return  new JsonResponse([
            'id' => $boutique->getId(),
            'name' => $boutique->getName(),
            'image' => $boutique->getLogo(),
            'id_conversation' => 1,
            'data' => []
        ]);
    }
}
