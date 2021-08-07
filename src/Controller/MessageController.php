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
use App\Service\InsertFileServices;
use App\Service\MercureService;
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
    public function index(int $id, CheckConversationService $checkConversationService, UserRepository $userRepository): Response
    {

        //  $this->denyAccessUnlessGranted('view', $conversation);

        //   $message = $conversation->getMessage();
        if ($this->getUser()) {


            $conversation = $checkConversationService->checkConversation($id);
            $conversationId = $conversation['conversation_id'];

            $blockedMe  = $this->getUser()->getBlocked();
            $blocked = false;

            if (isset($blockedMe[$conversationId])) {
                $blocked = true;
            } else {
                $blockedOther = $userRepository->findOneBy(['id' => $id]);
                $blockedMe =  $blockedOther->getBlocked();
                if (isset($blockedMe[$conversationId])) {
                    $blocked = true;
                }
            }

            if (intval($conversation) !== -1) {

                $messages = $this->messageRepository->findMessageByConversationId(
                    $conversationId
                );

                $newMessage = [];

                foreach ($messages as $key => $message) {
                    $deleteFrom = $message->getDeleteFrom() != null ? $message->getDeleteFrom() : [];
                    if (!in_array($this->getUser()->getId(), $deleteFrom)) {
                        array_push($newMessage, $message);
                    }
                }
                $messages = $newMessage;
                // $messages = array_filter($messages, function ($message, $key) {
                //     $deleteFrom = $message->getDeleteFrom() != null ? $message->getDeleteFrom() : [];
                //     return in_array($this->getUser()->getId(), $deleteFrom) ? false : true;
                //     //return true;
                // }, ARRAY_FILTER_USE_BOTH);
                // dump($messages);
                array_map(function ($message) {
                    // $deleteFrom = $message->getDeleteFrom() != null ? $message->getDeleteFrom() : [];
                    // if (in_array($this->getUser()->getId(), $deleteFrom)) {
                    //     $message->setContent("<i>Message supprimé</i>");
                    // }
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
                    'link' => $conversation['link'],
                    'blocked' => $blocked,
                    'messages' => $messages
                ], Response::HTTP_OK, [], ['attributes' => self::ATTRIBUTES_TO_SERIALISE]);
            }
        }
        return $this->json(['status' => "KO"], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/{id}", name="newMessage", methods={"POST"})
     */

    public function newMessage(Request $request, int $id, MercureService $mercureService, Conversation $conversation, InsertFileServices $insertFileServices)
    {


        $user = $this->getUser();
        $content_img = "";
        if ($request->files->get('image_content')) {
            $files = $insertFileServices->insertFile($request->files->get('image_content'));
            $content_img = "<div class='container_image_message'><img src='/images/" . $files . "' alt='image_message'/></div>";
        }

        $content = $request->get('content', null);
        $message = new Message();
        $message->setContent($content_img . $content);
        $message->setUser($user);
        $message->setMy(true);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->entityManager->persist($message);
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        //  $topic = "https://intro-mercure.test/users/message/" . $conversation->getId();

        $data = [
            "user" => $user->getId(),
            "type" => "newMessage",
            "message" => [
                "content" => $content_img . $content,
                "times" => $message->getCreatedAt()->getTimesTamp(),
                "id" => $message->getId()

            ]
        ];

        //    $mercureService->mercurePost($topic, $data);

        return $this->json($message, Response::HTTP_CREATED, [], ['attributes' => self::ATTRIBUTES_TO_SERIALISE]);

        // return $this->json(['status' => "ko"], Response::HTTP_NOT_ACCEPTABLE, []);
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

        $conversation = array_merge($conversation, [
            "times" => $conversation['createdAt']->getTimesTamp()
        ]);
        return $this->json($conversation);
    }

    /**
     * @Route("/delete/{id}", name="deleteMessage", methods={"POST"})
     */
    public function deleteMessage($id, MessageRepository $messageRepository)
    {

        $message = $messageRepository->findOneBy(['id' => $id]);
        $deleteFrom = $message->getDeleteFrom();
        $deleteFrom = ($deleteFrom != null) ? $deleteFrom : [];

        //$message->setCreatedAt(new \DateTime());

        if (in_array($this->getUser()->getId(), $deleteFrom)) {
            return $this->json(['status' => 'ok', 'message' => 'delete yet']);
        } else {

            array_push($deleteFrom, $this->getUser()->getId());
            $message->setDeleteFrom($deleteFrom);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
        }

        return $this->json(['status' => 'ok']);
    }

    /**
     * @Route("/deleteAll/{id}", name="deleteAllMessage", methods={"POST"})
     */
    public function deleteAllMessage($id, CheckConversationService $checkConversationService)
    {

        //  $this->denyAccessUnlessGranted('view', $conversation);

        //   $message = $conversation->getMessage();
        if ($this->getUser()) {


            $conversation = $checkConversationService->checkConversation($id);
            $conversationId = $conversation['conversation_id'];

            if (intval($conversation) !== -1) {

                $messages = $this->messageRepository->findMessageByConversationId(
                    $conversationId
                );
                $em = $this->getDoctrine()->getManager();

                foreach ($messages as $key => $message) {
                    $deleteFrom = $message->getDeleteFrom();
                    $deleteFrom = ($deleteFrom != null) ? $deleteFrom : [];
                    if (!in_array($this->getUser()->getId(), $deleteFrom)) {

                        array_push($deleteFrom, $this->getUser()->getId());
                        $message->setDeleteFrom($deleteFrom);
                        $em->persist($message);
                    }
                }


                $em->flush();

                return $this->json([], Response::HTTP_OK, [], ['attributes' => self::ATTRIBUTES_TO_SERIALISE]);
            }
        }
        return $this->json(['status' => "KO"], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/blocked/{id}", name="blockedMessage", methods={"POST"})
     */
    public function blocked(int $id, CheckConversationService $checkConversationService, UserRepository $userRepository)
    {

        $conversation = $checkConversationService->checkConversation($id);
        $conversationId = $conversation['conversation_id'];
        $blocked = $this->getUser()->getBlocked() != null ? $this->getUser()->getBlocked() : [];
        $otherUser = $userRepository->findOneBy(['id' => $id]);
        $name = $otherUser->getBoutiques()[0] != null ? $otherUser->getBoutiques()[0]->getName() : $otherUser->getName() . " " . $otherUser->getFirstName();
        $logo = ($otherUser->getBoutiques()[0] != null) ? $otherUser->getBoutiques()[0]->getLogo() : $otherUser->getAvatar();

        if (!isset($blocked[$conversationId])) {
            $blocked[$conversationId] = [
                'id_blocker' => $this->getUser()->getId(),
                'id_blocked' => $id,
                'id_conversation' => $conversationId,
                'name' => $name,
                'logo' => $logo
            ];
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->setBlocked($blocked);
        $em->flush();
        return $this->json(['status' => 'ok']);
    }

    /**
     * @Route("/deblocked/{id}", name="deblockedMessage", methods={"POST"})
     */
    public function deblocked(int $id)
    {

        $blocked = $this->getUser()->getBlocked();
        unset($blocked[intVal($id)]);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->setBlocked($blocked);
        $em->flush();
        return $this->json(['status' => 'ok']);
    }
    /**
     * @Route("/lastest/{id}-{last_id_message}", name="getLastTestMessages", methods={"GET"})
     */

    public function getLastMessages(string $id, string $last_id_message, UserRepository $userRepository, CheckConversationService $checkConversationService)
    {
        //  $this->denyAccessUnlessGranted('view', $conversation);

        //   $message = $conversation->getMessage();
        if ($this->getUser()) {


            if (!is_numeric($id)) {
                return $this->json(
                    [
                        'nbr_notification' => $this->getUser()->getNbrNotification() != null ? $this->getUser()->getNbrNotification() : 0,
                        'nbr_message' => $this->getUser()->getNbrMessage() != null ? $this->getUser()->getNbrMessage() : 0,
                        'messages' => [],
                        'blocked' => "",
                    ],
                    Response::HTTP_OK,
                    [],
                    ['attributes' => self::ATTRIBUTES_TO_SERIALISE]
                );
            }
            $conversation = $checkConversationService->checkConversation(intVal($id));
            $conversationId = $conversation['conversation_id'];

            if (intval($conversation) !== -1) {

                $messages = $this->messageRepository->findLastMessageByConversationId(
                    $conversationId,
                    intVal($last_id_message)
                );

                $newMessage = [];

                foreach ($messages as $key => $message) {
                    $deleteFrom = $message->getDeleteFrom() != null ? $message->getDeleteFrom() : [];
                    if (!in_array($this->getUser()->getId(), $deleteFrom)) {
                        array_push($newMessage, $message);
                    }
                }
                $messages = $newMessage;
                // $messages = array_filter($messages, function ($message, $key) {
                //     $deleteFrom = $message->getDeleteFrom() != null ? $message->getDeleteFrom() : [];
                //     return in_array($this->getUser()->getId(), $deleteFrom) ? false : true;
                //     //return true;
                // }, ARRAY_FILTER_USE_BOTH);
                // dump($messages);
                array_map(function ($message) {
                    // $deleteFrom = $message->getDeleteFrom() != null ? $message->getDeleteFrom() : [];
                    // if (in_array($this->getUser()->getId(), $deleteFrom)) {
                    //     $message->setContent("<i>Message supprimé</i>");
                    // }
                    $message->setMy(
                        $message->getUser()->getId() === $this->getUser()->getId() ? true : false
                    );
                    $message->setTimes($message->getCreatedAt()->getTimesTamp());
                }, $messages);

                $blockedMe  = $this->getUser()->getBlocked();
                $blocked = false;

                if (isset($blockedMe[$conversationId])) {
                    $blocked = true;
                } else {
                    $blockedOther = $userRepository->findOneBy(['id' => $id]);
                    $blockedMe =  $blockedOther->getBlocked();
                    if (isset($blockedMe[$conversationId])) {
                        $blocked = true;
                    }
                }

                return $this->json(
                    [
                        'nbr_notification' => $this->getUser()->getNbrNotification() != null ? $this->getUser()->getNbrNotification() : 0,
                        'nbr_message' => $this->getUser()->getNbrMessage() != null ? $this->getUser()->getNbrMessage() : 0,
                        'messages' => $messages,
                        'blocked' => $blocked,
                    ],
                    Response::HTTP_OK,
                    [],
                    ['attributes' => self::ATTRIBUTES_TO_SERIALISE]
                );
            }
        }
    }
}
