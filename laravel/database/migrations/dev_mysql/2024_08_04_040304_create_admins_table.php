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
        Schema::connection('dev_mysql')->create('admins', function (Blueprint $table) {
            $table->bigIncrements('admins_id')->index('admins_id')->comment('账号id');
            $table->char('username', 20)->unique('username_only')->comment('账号');
            $table->char('password', 32)->comment('密码');
            $table->char('nick_name', 20)->comment('昵称');
            $table->char('real_name', 20)->comment('真实姓名');
            $table->char('phone', 11)->default('')->comment('手机号');
            $table->string('portrait')->default('')->comment('头像');
            $table->unsignedTinyInteger('status')->default(1)->comment('1启用 0禁用');
            $table->integer('role_id')->default(0)->comment('-1开发者 0超级管理员 大于0指定role_id');
            $table->unsignedBigInteger('insert_admins_id')->default(0);
            $table->unsignedBigInteger('insert_organi_id')->default(0);
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
            $table->char('dev_pwd', 20)->default('')->comment('开发者账号密码');

            $table->primary(['admins_id']);
            $table->unique(['password', 'username'], 'user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('admins');
    }
};
