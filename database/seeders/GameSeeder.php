<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use App\Enums\GameStatus;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.d
     */
    public function run(): void
    {
        Game::factory()->count(10000)->create([
            'status' => 2,
            'revealed_at' => now()->toDateTimeString()
        ]);
    }
}
