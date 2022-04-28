<?php

namespace App\Controller\Api\V1;

use App\CommandBus\GenerateThumbCommand;
use App\Dto\GenerateThumbRequestDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GenerateThumbController extends AbstractController
{
    #[Route('/generate/thumb', methods: ['POST'])]
    public function index(
        Request $request,
        ValidatorInterface $validator,
        MessageBusInterface $messageBus,
    ): Response {
        /** @var array<string, string> $requestData */
        $requestData = json_decode((string) $request->getContent(), true);
        $dto = new GenerateThumbRequestDto();
        $dto->filepath = $requestData['filepath'] ?? '';
        $dto->filter = $requestData['filter'] ?? '';

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $result = [];
            foreach ($errors as $error) {
                $result[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json($result, Response::HTTP_BAD_REQUEST);
        }

        $messageBus->dispatch(new GenerateThumbCommand($dto->filepath, $dto->filter));

        return $this->json([]);
    }
}
