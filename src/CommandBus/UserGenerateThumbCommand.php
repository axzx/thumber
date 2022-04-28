<?php

namespace App\CommandBus;

use App\CommandBus\Handler\UserGenerateThumbHandler;
use App\Entity\User;

/** @see UserGenerateThumbHandler */
class UserGenerateThumbCommand
{
    public function __construct(
        public readonly User $user,
    ) {
    }
}
