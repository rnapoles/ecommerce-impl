<?php

declare(strict_types=1);

namespace App\DTO\Product;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProduct
{

    public ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $name = '';

    #[Assert\Regex('/^\d+.\d+/')] 
    public float $price = 0.0;

    #[Assert\PositiveOrZero]
    public int $unitsInStock = 0;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $category = '-';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $description = '';

    public ?string $aditionalInfo = '';

    public ?string $sku = null;

    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Valoration value must be between {{ min }} and {{ max }}',
    )]
    public int $valoration = 1;

    #[Assert\NotNull]
    #[Assert\All([
        new Assert\NotNull,
        new Assert\NotBlank,
        new Assert\Length(min: 3),
    ])]
    public array $tags = [];

    #[Assert\NotNull]
    #[Assert\All([
        new Assert\NotNull,
        new Assert\NotBlank,
        new Assert\Url,
    ])]
    #[Assert\Count(
        min: 1,
        max: 5,
        minMessage: 'You must specify at least one image',
        maxMessage: 'You cannot specify more than {{ limit }} images',
    )]
    public array $images = [];
}
