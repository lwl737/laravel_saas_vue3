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
        Schema::connection('dev_mysql')->create('menu_relation', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_ancestor_id')->comment('祖先id');
            $table->unsignedBigInteger('menu_descendant_id')->index('descendant_id')->comment('子孙id');
            $table->unsignedBigInteger('level')->default(0)->comment('层级');

            $table->primary(['menu_ancestor_id', 'menu_descendant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('menu_relation');
    }
};
