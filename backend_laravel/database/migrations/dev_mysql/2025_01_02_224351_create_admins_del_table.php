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
        Schema::connection('dev_mysql')->create('admins_del', function (Blueprint $table) {
            $table->bigIncrements('del_id')->comment('删除id');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
            $table->unsignedBigInteger('admins_id')->comment('账号id');
            $table->longText('data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('admins_del');
    }
};
