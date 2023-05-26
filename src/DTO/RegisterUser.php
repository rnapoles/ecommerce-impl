<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUser
{

    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $password; 
   
}
