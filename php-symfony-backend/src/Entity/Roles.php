<?php

declare(strict_types=1);

namespace App\Entity;

enum Roles: string {
  case ROLE_USER = 'ROLE_USER';
  case ROLE_EDITOR = 'ROLE_EDITOR';
  case ROLE_ADMIN = 'ROLE_ADMIN';
  case ROLE_DEV = 'ROLE_DEV';
}