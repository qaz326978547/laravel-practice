<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Repositories\Eloquent\EloquentProductRepository;
use Carbon\Carbon;

class UpdateProductSaleStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update-sale-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新限時特賣商品狀態';

    protected $productRepository;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 每半小時執行一次 檢查當前時間是否大於特賣結束時間，如果是，則特賣結束,反之特賣開始
     *
     * @return mixed
     */
    public function handle(EloquentProductRepository $productRepository)
    {
        $products = $productRepository->getAll();

        foreach ($products as $product) {
            $now = now();
            $onSaleStart = Carbon::parse($product['on_sale_start']);
            $onSaleEnd = Carbon::parse($product['on_sale_end']);

            //如果當前時間小於特賣結束時間，且大於特賣開始時間，則特賣開始
            if ($now->greaterThanOrEqualTo($onSaleStart) && $now->lessThan($onSaleEnd)) {
                $productRepository->updateOnSaleStatus($product['id'], 1);
            // $this->info('Product ' . $product['id'] . '已經開始特賣.');
            } else {
                $productRepository->updateOnSaleStatus($product['id'], 0);
                // $this->info('Product ' . $product['id'] . '已經結束特賣.');
            }
            Log::channel('sale_status')->info('限時特賣商品狀態' . $product['id'] . '特賣狀態' . $product['is_on_sale'] . '開始特賣' . $onSaleStart . '結束特賣' . $onSaleEnd);
        }
    }
}
