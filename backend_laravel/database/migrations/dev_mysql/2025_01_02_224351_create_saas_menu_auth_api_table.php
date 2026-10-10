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
        Schema::connection('dev_mysql')->create('saas_menu_auth_api', function (Blueprint $table) {
            $table->bigIncrements('api_id');
            $table->string('api')->default('')->comment('请求api作为主键');
            $table->unsignedBigInteger('menu_id')->index('menu_del')->comment('栏目id');
            $table->unsignedTinyInteger('add_log')->default(1)->comment('1加入日志 0不加入');
            $table->unsignedBigInteger('auth_id')->nullable()->index('auth del')->comment('权限id');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
            $table->string('api_name')->default('')->comment('接口名称 公共接口时调用');

            $table->unique(['api', 'menu_id'], 'api menu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('saas_menu_auth_api');
    }
};
