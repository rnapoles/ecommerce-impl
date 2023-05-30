<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Product;
use App\Entity\User;
use App\DTO\User\User as UserDTO;
use App\DTO\Product\CreateProduct;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\MappingOperation\Operation;
use AutoMapperPlus\DataType;

class AutoMapperConfig implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
      $config->registerMapping(User::class, UserDTO::class)
        ->forMember('password', Operation::ignore());
      ;
      $config->registerMapping(Product::class, CreateProduct::class);
      $config->registerMapping(DataType::ARRAY, CreateProduct::class);
    }
}