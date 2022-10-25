<?php

namespace App\Event;

use App\Entity\People;
use Symfony\Contracts\EventDispatcher\Event;

class AddPersonEvent extends Event
{
    const ADD_PERSON_EVENT = 'person.add';

    public function __construct(private People $person)
    {
    }

    public function getPerson(): People
    {
        return $this->person;
    }
}