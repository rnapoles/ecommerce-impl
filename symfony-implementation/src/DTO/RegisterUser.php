<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUser
{

    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $password = '';

    #[Assert\NotNull]
    public array $roles = ['ROLE_USER'];
   
}
