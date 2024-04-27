<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ProductsRepositoryInterface
{
    public function findAll(): Collection;
}
