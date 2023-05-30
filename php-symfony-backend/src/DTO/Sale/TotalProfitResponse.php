<?php

declare(strict_types=1);

namespace App\DTO\Sale;

use Symfony\Component\Validator\Constraints as Assert;

class TotalProfitResponse
{
    public float $profit = -1;
}
