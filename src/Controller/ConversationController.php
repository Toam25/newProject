<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Participant;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function newConversations(Request $request)
    {
        $otherUser = $request->get('otheUser', 0);
        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new \Exception("the user was not found ");
        }
        if ($otherUser->getId() === $this->getUser()->getId()) {
            throw new \Exception("You can create conversation with yourself");
        }

        $conversation = $this->conversationRepository->findConversationsByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if (count($conversation)) {
            throw new \Exception("Conversation existe");
        }

        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        try {
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw $e;
        }
        return $this->json([
            'id' => $conversation->getId()
        ], 200);
    }

    /**
     * @Route("/", name ="getConversations", methods={"GET"})
     */

    public function getConversations()
    {
        $conversations = $this->conversationRepository->findConvesationsByUser($this->getUser()->getId());
        return $this->json([]);
    }
}
