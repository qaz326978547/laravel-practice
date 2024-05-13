<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Log;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 每半小時執行一次 檢查當前時間是否大於特賣結束時間，如果是，則特賣結束,反之特賣開始
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::where('is_enabled', 1)->get();
        foreach ($products as $product) {
            if (now()->greaterThan($product->on_sale_end)) {
                $product->update(['is_on_sale' => 0]);
                $this->info('Product ' . $product->id . '已經結束特賣.');
            } elseif (now()->greaterThanOrEqualTo($product->on_sale_start)) {
                $product->update(['is_on_sale' => 1]);
                $this->info('Product ' . $product->id . '已經開始特賣.');
            }
            Log::channel('sale_status')->info('限時特賣商品狀態' . $product->id . '特賣狀態' . $product->is_on_sale . '開始特賣' . $product->on_sale_start . '結束特賣' . $product->on_sale_end);
        }
    }
}