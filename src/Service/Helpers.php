<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Helpers
{
    public function __construct(private LoggerInterface $logger)
    {
    }
    public function sayHello(): string
    {
        $this->logger->info('hola');
        return 'Hello World!';
    }
}