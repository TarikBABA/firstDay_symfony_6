<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ListAllEvent extends Event
{
    const LIST_PEOPLE_EVENT = 'people';

    public function __construct(private int $nbPerson)
    {
    }

    public function getNbPeople(): int
    {
        return $this->nbPerson;
    }
}