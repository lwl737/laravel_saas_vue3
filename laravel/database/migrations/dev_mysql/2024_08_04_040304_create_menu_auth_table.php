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
        Schema::connection('dev_mysql')->create('menu_auth', function (Blueprint $table) {
            $table->bigIncrements('auth_id');
            $table->string('auth_name')->comment('权限名称  添加删除修改等等操作');
            $table->unsignedBigInteger('menu_id')->default(0)->index('menu del')->comment('栏目id');
            $table->unsignedInteger('auth_sort')->nullable()->default(50)->comment('权限排序显示');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
            $table->unsignedTinyInteger('common')->default(0)->comment('1公共 0非公共 接口');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('menu_auth');
    }
};
