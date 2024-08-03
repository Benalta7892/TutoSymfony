<?php

namespace App\MessageHandler;

use App\Message\RecipePDFMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RecipePDFMessageHandler
{
    public function __invoke(RecipePDFMessage $message)
    {
        // do something with your message
    }
}
