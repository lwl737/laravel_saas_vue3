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
        Schema::table('organi', function (Blueprint $table) {
            $table->foreign(['organi_pid'], 'organi_id_del')->references(['organi_id'])->on('organi')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organi', function (Blueprint $table) {
            $table->dropForeign('organi_id_del');
        });
    }
};
