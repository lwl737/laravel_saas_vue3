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
        Schema::table('organi_relation', function (Blueprint $table) {
            $table->foreign(['organi_ancestor_id'], 'ancestor_id')->references(['organi_id'])->on('organi')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['organi_descendant_id'], 'descendant_id')->references(['organi_id'])->on('organi')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organi_relation', function (Blueprint $table) {
            $table->dropForeign('ancestor_id');
            $table->dropForeign('descendant_id');
        });
    }
};
