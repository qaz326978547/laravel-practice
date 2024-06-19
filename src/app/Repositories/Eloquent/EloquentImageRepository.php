<?php

namespace App\Repositories\Eloquent;

use App\Models\Product\Image;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
    public function addAWSImage($file): string
    {
        // 上傳圖片到 S3
        $path = Storage::disk('s3')->put('images', $file);

        // 將圖片的路徑存到資料庫
        return $path;
    }

    public function create(array $data): Image
    {
        try {
            // 上傳圖片到 S3
            $path = Storage::disk('s3')->put('images', $data['file']);
            $imageData = [
                'imageUrl' => $path,
                'type' => $data['type']
            ];

            return $this->image_model->create($imageData);
        } catch (\Exception $e) {
            // 處理錯誤，例如記錄錯誤或將錯誤訊息返回給用戶
            Log::error('Failed to upload image to S3: ' . $e->getMessage());

            // 你也可以選擇拋出錯誤，讓上層調用者處理
            throw $e;
        }
    }



    public function update(int $id, array $data): bool
    {
        $image = $this->getById($id);

        if ($image) {
            // 刪除舊的圖片
            Storage::disk('s3')->delete($image->imageUrl);

            // 上傳新的圖片到 S3
            $path = Storage::disk('s3')->put('images', $data['file']); //put(路徑, 檔案)

            // 更新圖片的路徑
            $data['imageUrl'] = $path;

            return $image->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $image = $this->getById($id);

        if ($image) {
            // 刪除 S3 上的圖片
            Storage::disk('s3')->delete($image->imageUrl);

            // 刪除資料庫中的資料
            return $image->delete();
        }

        return false;
    }
}
