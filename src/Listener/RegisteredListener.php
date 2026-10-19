<?php

namespace App\Listener;

use AllowDynamicProperties;
use App\Entity\User;
use App\Event\CustomerStatusChangedEvent;
use App\Event\UserEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
    event: UserEvents::USER_REGISTERED,
    method: 'onRegistered'
)]
class RegisteredListener
{
    public function __construct(
        private readonly LoggerInterface $logger
    ){}
    public function onRegistered(CustomerStatusChangedEvent $event)
    {
        $registered = $event->getUser();
        $this->logger->info('Nouvel utilisateur inscrit', [
            'userId' => $registered->getId(),
        ]);
    }
}
