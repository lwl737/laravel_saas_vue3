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
        Schema::create('organi', function (Blueprint $table) {
            $table->bigIncrements('organi_id')->comment('部门id');
            $table->unsignedBigInteger('organi_pid')->nullable()->index('organi_pid')->comment('部门pid NULL为无上级（根）');
            $table->char('organi_name', 50)->comment('部门名称');
            $table->unsignedInteger('organi_sort')->default(50)->comment('排序从大到小');
            $table->timestamp('created_time')->nullable();
            $table->timestamp('updated_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organi');
    }
};
