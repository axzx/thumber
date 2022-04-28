<?php

namespace App\Command;

use App\Dto\GenerateThumbRequestDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:generate-thumb',
    description: 'Generate thumb for file',
)]
class GenerateThumbCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filepath', InputArgument::REQUIRED, '')
            ->addArgument('filter', InputArgument::OPTIONAL, '', 'min')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $dto = new GenerateThumbRequestDto();
        $dto->filepath = is_string($input->getArgument('filepath')) ? $input->getArgument('filepath') : '';
        $dto->filter = is_string($input->getArgument('filter')) ? $input->getArgument('filter') : '';

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $result = [];
            foreach ($errors as $error) {
                $result[] = $error->getPropertyPath().': '.$error->getMessage();
            }

            $io->error($result);

            return Command::FAILURE;
        }

        $io->writeln(sprintf('<info>Filepath: %s</info>', $dto->filepath));
        $io->writeln(sprintf('<info>Filter: %s</info>', $dto->filter));

        $this->messageBus->dispatch(new \App\CommandBus\GenerateThumbCommand($dto->filepath, $dto->filter));

        $io->success('Done');

        return Command::SUCCESS;
    }
}
