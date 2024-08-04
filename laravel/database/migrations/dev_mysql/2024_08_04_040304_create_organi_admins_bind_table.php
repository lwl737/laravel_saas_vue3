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
        Schema::connection('dev_mysql')->create('organi_admins_bind', function (Blueprint $table) {
            $table->bigIncrements('bind_id');
            $table->bigInteger('admins_id')->comment('用户id');
            $table->unsignedBigInteger('organi_id')->index('organi_del')->comment('组织id');
            $table->unsignedTinyInteger('data_permission')->default(1)->comment('暂未开发 数据级别 1只看自己+下级  2自己+下级+同级   3自己');
            $table->timestamp('created_time')->nullable();
            $table->timestamp('updated_time')->nullable();

            $table->unique(['admins_id', 'organi_id'], 'bind only');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('organi_admins_bind');
    }
};
