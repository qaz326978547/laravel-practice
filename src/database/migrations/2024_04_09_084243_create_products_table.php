<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title'); //標題
            $table->integer('category_id')->nullable(); //分類id 可為空
            $table->string('description'); //描述
            $table->string('content')->nullable(); //商品規格
            $table->decimal('origin_price'); //原價
            $table->decimal('price'); //售價
            $table->integer('quantity'); //數量
            $table->integer('is_enabled'); //是否上架
            $table->string('unit'); //單位
            $table->timestamp('on_sale_start')->nullable(); //優惠開始時間
            $table->timestamp('on_sale_end')->nullable(); //優惠結束時間     
            $table->boolean('is_on_sale')->default(0); //是否優惠
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
