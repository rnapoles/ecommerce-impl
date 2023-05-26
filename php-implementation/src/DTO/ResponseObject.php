<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ResponseObject
{

    public bool $success = true;

    public string $message = ""; 

    public array $errors = [];

    public object|array|null $payload = null;   
}
