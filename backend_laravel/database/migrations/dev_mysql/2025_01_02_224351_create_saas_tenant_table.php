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
        Schema::connection('dev_mysql')->create('saas_tenant', function (Blueprint $table) {
            $table->bigIncrements('tenant_id');
            $table->char('tenant_name', 50)->nullable()->comment('租户名称');
            $table->unsignedTinyInteger('status')->nullable()->default(1)->comment('1启用 0禁用');
            $table->unsignedBigInteger('tenant_sort')->nullable()->comment('排序');
            $table->unsignedTinyInteger('creating')->default(0)->comment('0创建租户中 1创建完成');
            $table->dateTime('created_time')->nullable();
            $table->dateTime('updated_time')->nullable();
            $table->dateTime('deleted_time')->nullable();
            $table->unsignedBigInteger('insert_admins_id');
            $table->unsignedBigInteger('insert_organi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('saas_tenant');
    }
};
