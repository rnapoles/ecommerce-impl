<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Roles;
use Symfony\Component\Validator\Constraints as Assert;

class SimpleUpdateUser
{

    #[Assert\PositiveOrZero]
    public int $id = -1;

    #[Assert\Email]
    public string $email = '';

    public ?string $password = null;

}
