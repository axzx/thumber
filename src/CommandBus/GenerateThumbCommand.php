<?php

namespace App\CommandBus;

use App\CommandBus\Handler\GenerateThumbHandler;

/** @see GenerateThumbHandler */
class GenerateThumbCommand
{
    public function __construct(
        public readonly string $filepath,
        public readonly string $filter,
    ) {
    }
}
