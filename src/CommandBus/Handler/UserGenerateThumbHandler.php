<?php

namespace App\CommandBus\Handler;

use App\CommandBus\GenerateThumbCommand;
use App\CommandBus\UserGenerateThumbCommand;
use App\Entity\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class UserGenerateThumbHandler
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function __invoke(UserGenerateThumbCommand $command): void
    {
        if (!$command->user->getPhoto()) {
            return;
        }

        $this->messageBus->dispatch(
            new GenerateThumbCommand($command->user->getPhoto(), $this->getFilter($command->user))
        );
    }

    private function getFilter(User $user): string
    {
        if ($user->getIsPremium()) {
            return 'min_premium';
        }

        return 'min';
    }
}
