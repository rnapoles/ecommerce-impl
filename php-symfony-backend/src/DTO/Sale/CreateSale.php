<?php

declare(strict_types=1);

namespace App\DTO\Sale;

use Symfony\Component\Validator\Constraints as Assert;

class CreateSale
{

    public ?int $id = null;

    #[Assert\PositiveOrZero]
    public int $productId = -1;

    #[Assert\PositiveOrZero]
    public int $userId = -1; 

}
