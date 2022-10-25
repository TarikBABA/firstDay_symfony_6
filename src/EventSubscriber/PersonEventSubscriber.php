<?php

namespace App\EventSubscriber;

use App\Event\AddPersonEvent;
use App\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerService $mailer)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [AddPersonEvent::ADD_PERSON_EVENT => ['onAddPersonEvent', 3000]];
    }

    public function onAddPersonEvent(AddPersonEvent $event)
    {
        $person = $event->getPerson();
        $mailMessage = $person->getName() . ' ' . $person->getFirstname();

        $this->mailer->send(content: $mailMessage, subject: "Mail sent from EventSuscribe");
    }
}