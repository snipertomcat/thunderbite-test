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
        Schema::table('games', function (Blueprint $table) {
            $table->integer('status')->after('account')->nullable();
        });

        //set the previous games to have status FINISHED_WON
        $games = \App\Models\Game::get();
        foreach ($games as $game) {
            $game->status = \App\Enums\GameStatus::FINISHED_WON;
            $game->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
