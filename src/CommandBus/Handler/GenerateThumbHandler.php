<?php

namespace App\CommandBus\Handler;

use App\CommandBus\GenerateThumbCommand;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Liip\ImagineBundle\Exception\Imagine\Filter\NonExistingFilterException;
use Liip\ImagineBundle\Service\FilterService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenerateThumbHandler
{
    public function __construct(
        private readonly FilterService $liipImagineServiceFilter,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(GenerateThumbCommand $command): void
    {
        try {
            $this->liipImagineServiceFilter->getUrlOfFilteredImage($command->filepath, $command->filter);
        } catch (NotLoadableException|NonExistingFilterException $ex) {
            $this->logger->error($ex->getMessage());
        }
    }
}
