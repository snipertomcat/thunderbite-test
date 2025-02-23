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
        Schema::create('games_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games', 'id');
            $table->integer('board_index')->unsigned();
            $table->string('tileImage');
            $table->integer('turn')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_histories');
    }
};
