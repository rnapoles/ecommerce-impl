<?php

declare(strict_types=1);

namespace App\DTO\Product;

use Symfony\Component\Validator\Constraints as Assert;

class ResponseSearchTotal
{
    public int $total = 0;
}
