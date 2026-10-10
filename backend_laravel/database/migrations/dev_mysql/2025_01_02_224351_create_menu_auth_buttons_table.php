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
        Schema::connection('dev_mysql')->create('menu_auth_buttons', function (Blueprint $table) {
            $table->bigIncrements('buttons_id');
            $table->string('buttons')->comment('按钮权限表示');
            $table->unsignedBigInteger('auth_id')->default(0)->index('auth')->comment('权限id');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('menu_auth_buttons');
    }
};
