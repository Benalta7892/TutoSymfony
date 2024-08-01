<?php

namespace App\EventSubscriber;

use App\Event\ContactRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailingSubscriber implements EventSubscriberInterface
{
    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
        ];
    }
}
