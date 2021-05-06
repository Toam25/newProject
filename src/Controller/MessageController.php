<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
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
    public function index(Request $request, Conversation $conversation): Response
    {

        $this->denyAccessUnlessGranted('view', $conversation);

        //   $message = $conversation->getMessage();

        $messages = $this->messageRepository->findMessageByConversationId(
            $conversation->getId()
        );

        dd($messages);
        return $this->render('base.html.twig', []);
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
}
