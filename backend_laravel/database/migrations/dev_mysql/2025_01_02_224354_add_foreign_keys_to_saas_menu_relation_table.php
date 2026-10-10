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
        Schema::connection('dev_mysql')->table('saas_menu_relation', function (Blueprint $table) {
            $table->foreign(['menu_ancestor_id'], 'dev_saas_menu_relation_ibfk_1')->references(['menu_id'])->on('saas_menu')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['menu_descendant_id'], 'dev_saas_menu_relation_ibfk_2')->references(['menu_id'])->on('saas_menu')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->table('saas_menu_relation', function (Blueprint $table) {
            $table->dropForeign('dev_saas_menu_relation_ibfk_1');
            $table->dropForeign('dev_saas_menu_relation_ibfk_2');
        });
    }
};
