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
        Schema::connection('dev_mysql')->table('saas_menu', function (Blueprint $table) {
            $table->foreign(['menu_pid'], 'dev_saas_menu_ibfk_1')->references(['menu_id'])->on('saas_menu')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['auth_id'], 'dev_saas_menu_ibfk_2')->references(['auth_id'])->on('saas_menu_auth')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->table('saas_menu', function (Blueprint $table) {
            $table->dropForeign('dev_saas_menu_ibfk_1');
            $table->dropForeign('dev_saas_menu_ibfk_2');
        });
    }
};
