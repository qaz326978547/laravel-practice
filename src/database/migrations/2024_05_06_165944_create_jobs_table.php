<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * 主要是紀錄Queue待處理的工作
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload'); //工作內容
            $table->unsignedTinyInteger('attempts'); //嘗試次數
            $table->unsignedInteger('reserved_at')->nullable(); //保留時間
            $table->unsignedInteger('available_at'); //可用時間
            $table->unsignedInteger('created_at'); //建立時間
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
