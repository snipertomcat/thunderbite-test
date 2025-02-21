<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\GameStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Campaigns\UpdateRequest;
use App\Models\Campaign;
use App\Models\Game;
use App\Models\Moves;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameController extends Controller
{
    public function flipTile(Request $request) {
        $game = Game::find($request->gameId);
        if (!$game) return response()->json(['message' => 'Invalid game.'], 400);

        $availablePrizes = Prize::where('segment', $game->segment)
            ->whereRaw('-LOG(1.0 - RAND()) / weight')
            ->get();

        if ($availablePrizes->isEmpty()) {
            return response()->json(['message' => 'No available prizes.'], 400);
        }

        $selectedPrize = $availablePrizes->first();

        $move = Moves::create([
            'game_id' => $game->id,
            'tileImage' => $request->tileIndex,
            'board_index' => $selectedPrize->id,
            'prize_id' => $selectedPrize->id,
        ]);

        $matchingTiles = $game->tiles->where('prize_id', $selectedPrize->id);
        $won = $matchingTiles->count() >= 3;

        if ($won) {
            $game->update(['completed' => true]);
            return response()->json([
                'tileImage' => asset($selectedPrize->image),
                'message' => 'You won a prize!',
            ]);
        }

        return response()->json([
            'tileImage' => asset($selectedPrize->image),
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        return view('backstage.games.index');
    }
}
