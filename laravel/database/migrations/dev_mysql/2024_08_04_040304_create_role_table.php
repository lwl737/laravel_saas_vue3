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
        Schema::connection('dev_mysql')->create('role', function (Blueprint $table) {
            $table->bigIncrements('role_id');
            $table->string('role_name')->comment('权限名称');
            $table->text('role_json')->comment('权限序列化格式');
            $table->integer('role_sort')->default(50)->comment('越大越往前显示 并且只能往上升级');
            $table->unsignedTinyInteger('status')->default(1)->comment('1启用0禁用');
            $table->string('role_describe', 512)->default('')->comment('权限描述');
            $table->unsignedBigInteger('insert_admins_id')->nullable()->default(0);
            $table->unsignedBigInteger('insert_organi_id')->nullable()->default(0);
            $table->dateTime('deleted_time')->nullable()->comment('软删除');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('role');
    }
};
