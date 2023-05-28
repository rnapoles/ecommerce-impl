<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PaginateResponseObject extends ResponseObject
{
    public int $total = 0;
}
