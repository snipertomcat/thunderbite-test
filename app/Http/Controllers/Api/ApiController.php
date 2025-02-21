<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Moves;
use App\Models\Prize;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public function flip()
    {
        /**
         * This is a simplified example to demonstrate interaction with the provided frontend (FE).
         * The game objective is to collect three matching tiles to win a prize. Once three matching tiles are collected:
         *   - The game ends.
         *   - The prize is awarded, and its daily volume limit (defined in the back office) must be updated.
         *
         * Requirements:dayer to store and manage all game-related data, including game state and prize counts.
         * - Cache is used here only for demonstration purposes and should be replaced with proper database storage.
         */

        $gameId = request('gameId');
        $game = Game::findOrFail($gameId);
        $tileIndex = request('tileIndex');
        $campaignId = request('campaign');
        $segment = request('segment');

        $prize = Prize::selectPrizeFromSegment($segment, $campaignId);

        $tileImage = $prize->getImage();
        if ($game->prize_id !== null) {
            $message = "Game Over: YOU WON!";
            return json_encode([
                'message' => $message,
            ]);
        } else {
            $lastTurn = (int)Moves::getLastTurn($gameId);
            $turn = $lastTurn++;

            $currentMove = Moves::create([
                'game_id' => $gameId,
                'board_index' => $tileIndex,
                'prize_id' => $prize->id,
                'turn' => $turn,
            ]);

            $game = Game::find($gameId);
            if ($game->prize_id !== null) {
                $message = "Game Over: YOU WON!";
            }

            if ($turn >= 10) {
                $message = "Game Over: YOU LOST!";
            }
        }
        // Return the next tile and a loss message if the move limit is exceeded.
        return json_encode([
            'tileImage' => $tileImage,
            'message' => $message ?? "",
        ]);
    }
}
