<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Product\Image;

interface ImageRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Image;
    public function create(array $data): Image;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
