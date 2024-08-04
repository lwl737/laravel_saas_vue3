<?php

declare(strict_types=1);

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
        Schema::create('admins_operation_log', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->longText('params')->comment('请求参数 序列化');
            $table->string('router_path')->comment('前端路由路径');
            $table->string('api')->comment('请求路径');
            $table->string('auth_name')->comment('操作名称');
            $table->longText('sql')->comment('sql 语句 序列化 数组 Array<string>');
            $table->string('ip')->default('')->comment('ip地址');
            $table->dateTime('created_time')->nullable()->comment('生成时间');
            $table->dateTime('updated_time')->nullable();
            $table->dateTime('operation_time')->nullable()->comment('操作时间');
            $table->string('menu_title')->default('')->comment('栏目名称');
            $table->string('enum')->comment('枚举名称');
            $table->unsignedBigInteger('insert_organi_id')->default(0);
            $table->bigInteger('insert_admins_id')->index('admins_id')->comment('管理员id');
            $table->char('method', 10)->default('')->comment('请求方法');
            $table->longText('menu_title_full')->comment('完整栏目名称');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins_operation_log');
    }
};
