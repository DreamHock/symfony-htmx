<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessagesController extends AbstractController
{
    public function __construct(
        private MessageRepository $messageRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/', name: 'app_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('messages/index.html.twig');
    }

    #[Route('/messages', name: 'app_messages_get', methods:['GET'])]
    public function messages(): Response
    {
        return $this->render('messages/messagesList.html.twig', [
            'messagesArray' => $this->messageRepo->findAll()
        ]);
    }

    #[Route('/messages', name: 'app_message_post', methods:['POST'])]
    public function messagePost(Request $request): Response
    {
        $newMessage = (new Message())->setMessage(
            $request->getPayload()->get("message")
        );

        $this->em->persist($newMessage);
        $this->em->flush();

        return $this->render('messages/messageItem.html.twig', ['message' => $newMessage]);
    }

    #[Route('/messages/{id}', name: 'app_message_delete', methods:['DELETE'])]
    public function messageDelete(Request $request, Message $message): Response
    {

        $this->em->remove($message);
        $this->em->flush();

        return new Response('');
    }
}
