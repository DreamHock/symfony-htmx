<?php

namespace App\MessageHandler;

use App\Message\MessageNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MessageNotificationHandler
{
    public function __invoke(MessageNotification $message)
    {

    }
}
