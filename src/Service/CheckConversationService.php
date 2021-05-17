<?php

namespace App\Service;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CheckConversationService extends AbstractController
{

    private $userRepository;

    private $conversationRepository;

    private $entityManager;

    public function __construct(UserRepository $userRepository, ConversationRepository $conversationRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->userRepository = $userRepository;
        $this->conversationRepository = $conversationRepository;
        $this->entityManager = $entityManagerInterface;
    }

    public function checkConversation(int $otherUser)
    {

        $otherUser = $this->userRepository->findOneByUser($otherUser);

        if (is_null($otherUser)) {

            return -1;
            //return $this->json(['status' => "ko", "message" => "unfind user"], Response::HTTP_NOT_ACCEPTABLE);
            // throw new \Exception("the user was not found ");
        }
        if ($otherUser->getId() === $this->getUser()->getId()) {

            return -1;
            // return $this->json(['status' => "ko", "message" => "You can create conversation with yourself"], Response::HTTP_NOT_ACCEPTABLE);

            //throw new \Exception("You can create conversation with yourself");
        }

        $conversation = $this->conversationRepository->findConversationsByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if (count($conversation)) {


            return [
                'conversation_id' => $conversation[0]->getId(),
                'user_id' => $otherUser->getId(),
                'user_name' => ($otherUser->getBoutiques()[0] != null) ? $otherUser->getBoutiques()[0]->getName() : $otherUser->getName() . " " . $otherUser->getFirstName(),
                'images' => ($otherUser->getBoutiques()[0] != null) ? $otherUser->getBoutiques()[0]->getLogo() : $otherUser->getAvatar()
            ];
            return $this->json($this->conversationRepository->findConvesationsByUser($this->getUser()->getId()), 200);
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

        return  [
            'conversation_id' => $conversation->getId(),
            'user_id' => $otherUser->getId(),
            'user_name' => ($otherUser->getBoutiques()->getName() != null) ? $otherUser->getBoutiques()->getName() : $otherUser->getName()

        ];
    }
}
