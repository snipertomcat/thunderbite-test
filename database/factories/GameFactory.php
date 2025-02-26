<?php

namespace Database\Factories;

use App\Enums\GameStatus;
use App\Models\Campaign;
use App\Models\Prize;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        $campaignId = 1;

        return [
            'campaign_id' => 1,
            'prize_id' => null,
            'account' => 'test',
            'revealed_at' => Carbon::now()->toDateTimeString(),
            'status' => 1,
        ];
    }
}
