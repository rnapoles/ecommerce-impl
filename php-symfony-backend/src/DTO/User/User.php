<?php

declare(strict_types=1);

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class User extends RegisterUser
{

    #[Assert\PositiveOrZero]
    public int $id = -1; 
   
}
