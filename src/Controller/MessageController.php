<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\BoutiqueRepository;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\CheckConversationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/messages", name="message.")
 */
class MessageController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALISE = ['id', 'content', 'createdAt', 'my', "times"];
    private $entityManager;
    private $messageRepository;
    public function __construct(MessageRepository $messageRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;
        $this->messageRepository = $messageRepository;
    }
    /**
     * @Route("/{id}", name="getMessage", methods={"GET"})
     */
    public function index(BoutiqueRepository $boutiqueRepository, UserRepository $userRepository, int $id, CheckConversationService $checkConversationService): Response
    {

        //  $this->denyAccessUnlessGranted('view', $conversation);

        //   $message = $conversation->getMessage();

        $conversation = $checkConversationService->checkConversation($id);
        $conversationId = $conversation['conversation_id'];

        if ($conversationId != -1) {

            $messages = $this->messageRepository->findMessageByConversationId(
                $conversationId
            );

            array_map(function ($message) {
                $message->setMy(
                    $message->getUser()->getId() === $this->getUser()->getId() ? true : false
                );
                $message->setTimes($message->getCreatedAt()->getTimesTamp());
            }, $messages);


            return $this->json([
                'id' => $conversation['user_id'],
                'name' => $conversation['user_name'],
                'image' => $conversation['images'],
                'id_conversation' => $conversationId,
                'messages' => $messages
            ], Response::HTTP_OK, [], ['attributes' => self::ATTRIBUTES_TO_SERIALISE]);
        } else {
            return $this->json(['status' => "KO"], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @Route("/{id}", name="newMessage", methods={"POST"})
     */

    public function newMessage(Request $request, Conversation $conversation)
    {


        $user = $this->getUser();
        $content = $request->get('content', null);
        $message = new Message();
        $message->setContent($content);
        $message->setUser($user);
        $message->setMy(true);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->entityManager->persist($message);
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();


        return $this->json($message, Response::HTTP_CREATED, [], ['attributes' => self::ATTRIBUTES_TO_SERIALISE]);
    }
    // /**
    //  * @Route("/new/{id}", name="message_new", methods={"GET","POST"})
    //  */
    // public function new(int $id,Request $request): Response
    // {
    //     $message = new Message();
    //     $message->setIdReceved($id);
    //     $message->setIdSender($this->getUser()->getId());
    //     $message->setMessage($request->request->get('message'));
    //     if($this->getUser()){
    //              return new JsonResponse(['status'=>"success",'id_receved'=>$id, 'id_sender'=>$this->getUser()->getId(),'id_message'=>$message->getId(),
    //              'message'=>$message->getMessage(),]);
    //     }
    //     else{
    //         return new JsonResponse([
    //             'status'=>"error"
    //         ],406);
    //     }
    // }

    // /**
    //  * @Route("/{id}", name="message_show", methods={"GET"})
    //  */
    // public function show(Message $message): Response
    // {
    //     return $this->render('message/show.html.twig', [
    //         'message' => $message,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Message $message): Response
    // {
    //     $form = $this->createForm(MessageType::class, $message);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('message_index');
    //     }

    //     return $this->render('message/edit.html.twig', [
    //         'message' => $message,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="message_delete", methods={"DELETE"})
    //  */
    // public function delete(Request $request, Message $message): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($message);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('message_index');
    // }

    /**
     * @Route("/last/{id}", name="getLastMessage", methods={"GET"})
     */
    public function getLastMessage($id, ConversationRepository $conversationRepository)
    {

        $conversation = $conversationRepository->findConvesationsByUserAndMe($this->getUser()->getId(), $id);
        $newConversations = [];

        //  $conversation['createdAt']->getTimestamp();
        if ($conversation['createdAt'] != null) {

            $conversation = array_merge($conversation, [
                "times" => $conversation['createdAt']->getTimesTamp()
            ]);
            array_push($newConversations, $conversation);
        }

        return $this->json($newConversations);
    }
}
