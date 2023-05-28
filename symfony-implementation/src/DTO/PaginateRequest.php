<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PaginateRequest
{
    public int $start = 0;

    public int $total = 20;

    public ?string $query = null;
}
