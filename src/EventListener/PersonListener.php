<?php

namespace App\EventListener;

use App\Event\AddPersonEvent;
use App\Event\ListAllEvent;
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

    public function onListAllPerson(ListAllEvent $event)
    {
        $this->logger->debug("number of people in list is " . $event->getNbPeople());
    }

    public function onListAllPerson2(ListAllEvent $event)
    {
        $this->logger->debug("2nd listener number of people in list is " . $event->getNbPeople());
    }
}