<?php
namespace App\Repositories;

use App\Models\Product\Image;
use Illuminate\Support\Collection;

class EloquentImageRepository implements ImageRepositoryInterface
{
    public function getAll(): Collection
    {
        return Image::all();
    }

    public function getById(int $id): ?Image 
    {
        return Image::find($id);
    }

    public function create(array $data): Image
    {
        return Image::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $image = $this->getById($id);

        if ($image) {
            return $image->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $image = $this->getById($id);

        if ($image) {
            return $image->delete();
        }

        return false;
    }
}