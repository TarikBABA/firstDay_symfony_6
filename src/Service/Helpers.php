<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class Helpers
{
    public function __construct(private LoggerInterface $logger, private Security $security)
    {
    }
    public function sayHello(): string
    {
        $this->logger->info('hola');
        return 'Hello World!';
    }

    public function getUser(): User
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->security->getUser();
        }
    }
}