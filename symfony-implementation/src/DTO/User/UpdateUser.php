<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\Entity\Roles;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUser extends SimpleUpdateUser
{

    #[Assert\Choice(callback: 'getRoles', multiple: true)]
    public array $roles = [];

    public static function getRoles()
    {
      
        $roles = [];
        foreach(Roles::cases() as $case) {
            $roles[] = $case->value;
        }
      
        return $roles;
    }

}
