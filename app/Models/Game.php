<?php

namespace App\Models;

use App\Enums\GameStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'prize_id', 'account', 'status', 'revealed_at'];

    protected function casts(): array
    {
        return [
            'revealed_at' => 'datetime'
        ];
    }

    public static function boot(): void
    {
        static::updating(function (Game $game) {
            $campaignId = session('activeCampaign');
            $campaign = Campaign::find($campaignId);

            if (!empty($game->prize_id)) {
                $game->revealedAt = Carbon::tz($campaign->timezone)->now()->toDateTimeString();
                $game->save();
            }
        });
    }

    public static function filter(?string $account = null, ?int $prizeId = null, ?string $fromDate = null, ?string $tillDate = null)
    {
        $query = self::query();
        $campaign = Campaign::find(session('activeCampaign'));

        // When filtering by dates, keep in mind `revealed_at` should be stored in Campaign timezone

        return $query;
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }

    public function moves(): HasMany
    {
        return $this->hasMany(Moves::class);
    }

    public function getStatusAttribute(): string
    {
        return GameStatus::from($this->attributes['status'])->name;
    }

    public static function startOrResumeGame($account, $campaignId): Game|array
    {
        $game = Game::where('account', $account)->where('status', GameStatus::IN_PROGRESS)->with('moves.prize')->first();
        $campaign = Campaign::find($campaignId);

        if (!$game) {
            $game = Game::create([
                'campaign_id' => $campaignId,
                'account' => $account,
                'status' => GameStatus::IN_PROGRESS,
                'prize_id' => null,
                'revealed_at' => null,
            ]);
        }

        if ($game->moves->isEmpty()) {
            return [$game, []];
        }

        $moveHistory = $game->moves;
        $revealedTiles = [];
        //game is in progress, re-create board from history
        foreach ($moveHistory as $move) {
            $revealedTiles[] = [
                'index' => $move->board_index,
                'image' => $move->prize->getImage()
            ];
        }

        session()->put('gameId', $game->id);

        return [$game, $revealedTiles];
    }

    public function checkAndUpdateGamePrize(): void
    {
        $prizeWonCheck = Moves::selectRaw('prize_id')
            ->where('game_id', $this->id)
            ->groupBy('moves.prize_id')
            ->havingRaw("count(*) > 2")
            ->pluck('prize_id')
            ->first();

        if ($prizeWonCheck) {
            $timezone = $this->campaign->timezone;
            //update the associated game's prize_id
            $this->update([
                'prize_id' => $prizeWonCheck,
                'status' => GameStatus::FINISHED_WON,
                'revealed_at' => Carbon::now()->tz($timezone)->toDateTimeString(),
            ]);
            $this->save();
        }
    }
}
