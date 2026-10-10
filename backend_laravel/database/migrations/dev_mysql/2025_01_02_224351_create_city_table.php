<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('dev_mysql')->create('city', function (Blueprint $table) {
            $table->bigIncrements('city_id');
            $table->char('city_name', 50)->default('')->index('city_name')->comment('城市名称');
            $table->char('adcode', 10)->default('')->index('adcode')->comment('高德地图adcode');
            $table->char('citycode', 10)->default('')->comment('高德地图citycode');
            $table->unsignedBigInteger('city_pid')->nullable()->comment('城市上级');
            $table->unsignedTinyInteger('city_level')->comment('城市级别 1 => 省 2 => 市 3 =>区 ');
            $table->char('as_name', 50)->default('')->comment('城市别名 查询时显示这个');
            $table->char('city_link', 50)->default('')->index('city_link')->comment('城市上下级链');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('city');
    }
};
