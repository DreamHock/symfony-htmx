<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Message\MessageNotification;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MessagesController extends AbstractController
{
    public function __construct(
        private MessageRepository $messageRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/', name: 'app_index', methods:['GET'])]
    public function index(Security $security): Response
    {
        return $this->render('pages/home/index.html.twig');
    }

    #[Route('/messages', name: 'app_messages_get', methods:['GET'])]
    public function messages(): Response
    {
        return $this->render('pages/home/components/messagesList.html.twig', [
            'messagesArray' => $this->messageRepo->findAll()
        ]);
    }

    #[Route('/messages', name: 'app_message_post', methods:['POST'])]
    public function messagePost(Request $request, MessageBusInterface $bus): Response
    {
        $newMessage = (new Message())->setMessage(
            $request->getPayload()->get("message")
        );

        $this->em->persist($newMessage);
        $this->em->flush();

        $bus->dispatch(new MessageNotification($newMessage->getMessage(), $request));

        return $this->render('pages/home/components/messageItem.html.twig', ['message' => $newMessage]);
    }

    #[Route('/messages/{id}', name: 'app_message_delete', methods:['DELETE'])]
    public function messageDelete(Request $request, Message $message)
    : Response
    {

        $this->em->remove($message);
        $this->em->flush();

        return new Response();
    }

    #[Route('/messages/{id}/edit', name: 'app_message_edit_get', methods:['GET'])]
    public function messageEditGet(Message $message): Response
    {
        return $this->render('pages/home/components/messageEdit.html.twig', ['message' => $message]);
    }

    #[Route('/messages/{id}', name: 'app_message_edit', methods:['PATCH'])]
    public function messageEdit(Request $request, Message $message): Response
    {
        $message->setMessage($request->getPayload()->get('message'));


        $this->em->persist($message);
        $this->em->flush();

        return $this->render('pages/home/components/messageItem.html.twig', ['message' => $message]);
    }
}
