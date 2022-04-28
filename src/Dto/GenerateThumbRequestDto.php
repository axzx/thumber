<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class GenerateThumbRequestDto
{
    #[Assert\Sequentially([
        new Assert\NotBlank(),
        new Assert\Type('string'),
    ])]
    public ?string $filepath = null;

    #[Assert\Sequentially([
        new Assert\NotBlank(),
        new Assert\Type('string'),
        new Assert\Choice(choices: ['min', 'min_premium']),
    ])]
    public ?string $filter = null;
}
