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
        Schema::connection('dev_mysql')->table('organi_admins_bind', function (Blueprint $table) {
            $table->foreign(['organi_id'], 'organi_del')->references(['organi_id'])->on('organi')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->table('organi_admins_bind', function (Blueprint $table) {
            $table->dropForeign('organi_del');
        });
    }
};
