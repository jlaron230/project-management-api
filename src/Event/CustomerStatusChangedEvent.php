<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

final class CustomerStatusChangedEvent extends Event
{
    public function __construct(
        private User $user,
    ) {
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
