<?php

namespace App\EventListener;

use App\Event\AddPersonEvent;
use Psr\Log\LoggerInterface;

class PersonListener
{
    // public function onAddPersonListener()
    // {
    //     dd("je suis entrain d'écouter l'evenement person.add");
    // }

    public function __construct(private LoggerInterface $logger)
    {
    }
    public function onAddPersonListener(AddPersonEvent $event)
    {
        $this->logger->debug("je suis entrain d'écouter l'evenement person.add & person added is " . $event->getPerson()->getName());
    }
}