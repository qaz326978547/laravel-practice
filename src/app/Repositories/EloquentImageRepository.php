<?php
namespace App\Repositories;

use App\Models\Product\Image;
use Illuminate\Support\Collection;

class EloquentImageRepository implements ImageRepositoryInterface
{
    private $image_model;

    public function __construct(Image $image_model)
    {
        $this->image_model = $image_model;
    }
    public function getAll(): Collection
    {
        return $this->image_model->all();
    }

    public function getById(int $id): ?Image 
    {
        return $this->image_model->find($id);
    }

    public function create(array $data): Image
    {
        return $this->image_model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $image = $this->image_model->getById($id);

        if ($image) {
            return $image->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $image = $this->image_model->getById($id);

        if ($image) {
            return $image->delete();
        }

        return false;
    }
}