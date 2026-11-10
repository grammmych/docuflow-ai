<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserRequest
{
    #[Assert\Sequentially([
        new Assert\NotBlank(),
        new Assert\Type('string'),
        new Assert\Email(),
    ])]
    public mixed $email = null;

    #[Assert\Sequentially([
        new Assert\NotBlank(),
        new Assert\Type('string'),
        new Assert\Length(min: 8, max: 72),
    ])]
    public mixed $password = null;
}