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
        Schema::connection('dev_mysql')->table('menu_auth_api', function (Blueprint $table) {
            $table->foreign(['auth_id'], 'dev_menu_auth_api_ibfk_1')->references(['auth_id'])->on('menu_auth')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['menu_id'], 'dev_menu_auth_api_ibfk_2')->references(['menu_id'])->on('menu')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->table('menu_auth_api', function (Blueprint $table) {
            $table->dropForeign('dev_menu_auth_api_ibfk_1');
            $table->dropForeign('dev_menu_auth_api_ibfk_2');
        });
    }
};
