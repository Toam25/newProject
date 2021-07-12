<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Participant;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\checkConversationService;
use App\Service\CheckConversationService as ServiceCheckConversationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

/**
 * @Route("/conversations",name="conversations.")
 */
class ConversationController extends AbstractController
{
    /**
     *
     * @var UserRepository
     */
    private $userRepository;
    /**
     *
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * Undocumented variable
     *
     * @var ConversationRepository
     */
    private $conversationRepository;
    /**
     *@param ConversationRepository $conversationRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManagerInterface
     */
    public function __construct(ConversationRepository $conversationRepository, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManagerInterface;
        $this->conversationRepository = $conversationRepository;
    }
    /**
     * @Route("/", name="newConversations", methods={"POST"})
     */

    public function newConversations(Request $request, CheckConversationService  $checkConversationService)
    {
        $checkConversationService->checkConversation($request->get('otheUser', 0));
    }

    /**
     * @Route("/", name ="getConversations", methods={"GET"})
     */

    public function getConversations(Request $request)
    {
        if ($this->getUser() != null) {

            $conversations = $this->conversationRepository->findConvesationsByUser($this->getUser()->getId());
            $newConversations = [];

            $hubUrl = $this->getParameter('mercure.default_hub');
            $this->addLink($request, new Link('mercure ', $hubUrl));

            foreach ($conversations as $conversation) {
                //  $conversation['createdAt']->getTimestamp();
                if ($conversation['createdAt'] != null) {


                    $conversation = array_merge($conversation, [
                        "times" => $conversation['createdAt']->getTimesTamp()
                    ]);
                }
                array_push($newConversations, $conversation);
            }

            return $this->json($newConversations);
        }

        return $this->json(['status' => 'ko', 'message' => "vous êtes deconnecter, connecter à nouveau"], Response::HTTP_UNAUTHORIZED);
    }
}
