<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameForeignKey extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game', function (Blueprint $table) {
            $table->integer('fk_map')->unsigned()->nullable();
            $table->foreign('fk_map')->references('id')->on('map');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game', function (Blueprint $table) {
            $table->dropForeign('game_fk_map_foreign');
            $table->dropColumn('fk_map');
        });
    }
}
