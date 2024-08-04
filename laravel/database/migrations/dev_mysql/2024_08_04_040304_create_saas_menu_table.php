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
        Schema::connection('dev_mysql')->create('saas_menu', function (Blueprint $table) {
            $table->bigIncrements('menu_id');
            $table->unsignedBigInteger('menu_pid')->nullable()->index('menu')->comment('父id');
            $table->string('path')->default('')->unique('only_one_path')->comment('网站路径');
            $table->string('name')->default('')->comment('路由name');
            $table->string('redirect')->default('')->comment('重定向不填到子级第一个');
            $table->string('component')->default('')->comment('所属组件 文件路径');
            $table->unsignedInteger('menu_sort')->default(50)->comment('排序越大越往前显示');
            $table->char('icon', 20)->default('')->comment('图标');
            $table->char('title', 20)->default('')->comment('标题');
            $table->string('isLink', 512)->default('')->comment('是否外链');
            $table->unsignedTinyInteger('isHide')->default(0)->comment('是否隐藏');
            $table->unsignedTinyInteger('isFull')->default(0)->comment('是否全屏');
            $table->unsignedTinyInteger('isAffix')->default(0)->comment('是否在头部tabs显示');
            $table->unsignedTinyInteger('isKeepAlive')->default(1)->comment('是否缓存');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
            $table->unsignedBigInteger('auth_id')->nullable()->index('auth_id')->comment('权限id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('saas_menu');
    }
};
