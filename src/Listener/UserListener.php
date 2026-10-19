<?php

namespace App\Listener;

use App\Event\CustomerStatusChangedEvent;
use App\Event\UserEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
    event: UserEvents::STATUS_CHANGED,
    method: 'onStatusChanged'
)]
class UserListener
{
    public function onStatusChanged(CustomerStatusChangedEvent $event)
    {
        $user = $event->getUser();
    }
}
