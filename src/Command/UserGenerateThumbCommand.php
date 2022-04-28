<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:user:generate-thumb',
    description: 'Generate thumb for users',
)]
class UserGenerateThumbCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $count = 0;
        foreach ($this->userRepository->findAllWithPhotoIterable() as $user) {
            $this->messageBus->dispatch(new \App\CommandBus\UserGenerateThumbCommand($user));
            ++$count;
        }

        $io->info(sprintf('Processed Records: %s', $count));
        $io->success('Done');

        return Command::SUCCESS;
    }
}
