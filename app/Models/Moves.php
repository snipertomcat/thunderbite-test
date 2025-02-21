<?php

namespace App\Models;

use App\Exceptions\NoTurnsLeftException;
use Illuminate\Database\Eloquent\Model;

class Moves extends Model
{
    public $table = "moves";

    protected $fillable = ['game_id', 'board_index', 'prize_id', 'turn'];

    public static function booted(): void
    {
        static::creating(function (Moves $move) {
            $game = $move->game;
            $game->checkAndUpdateGamePrize();
        });

        static::created(function (Moves $move) {
            $game = $move->game;
            $game->checkAndUpdateGamePrize();
        });
    }

    public static function getLastTurn($gameId)
    {
        $turn = Moves::query()->where('game_id', $gameId)->max('turn');
        if ($turn === 0) {
            return 1;
        } else {
            return $turn;
        }
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }


}
