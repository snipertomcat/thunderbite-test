<?php

namespace App\Http\Controllers;

use App\Enums\GameResultMessage;
use App\Enums\GameStatus;
use App\Exceptions\CampaignNotValidException;
use App\Models\Campaign;
use App\Models\Game;
use App\Models\Moves;
use App\Models\Prize;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontendController extends Controller
{
    protected int $maxTurns;
    public function __construct()
    {
        $this->maxTurns = config('thunderbite.max_turns');
    }

    /**
     * @throws \JsonException
     */
    public function loadCampaign(Campaign $campaign): View
    {
        session()->put('activeCampaign', $campaign->id);
        $startTime = $campaign->starts_at;
        $endTime = $campaign->ends_at;
        $now = Carbon::now();
        $message = "";

        if ($now->gt($endTime)) {
            $message = GameResultMessage::CAMPAIGN_ENDED;
        } elseif ($now->lt($startTime)) {
            $message = GameResultMessage::CAMPAIGN_NOT_STARTED;
        }

        if ($message) {
            throw new CampaignNotValidException($message);
        }

        $account = request()->get('a');
        $segment = request()->get('segment');

        //load game if one exists already in a GameStatus::IN_PROGRESS statuse
        [$loadedGame, $revealedTiles] = Game::startOrResumeGame($account, $campaign->id);

        if (!is_null($revealedTiles)) {

            $gameId = $loadedGame->id;
            $moveHistory = $loadedGame->moves;
        }

        $jsonConfig = json_encode([
            'apiPath' => "/api/flip?segment={$segment}&campaign={$campaign->id}&a={$account}",
            'gameId'  => $gameId,
            'reveledTiles' => $revealedTiles, // Restore clicked tiles for an ongoing game
            'message' => $message, // Popup message when the game changes statuses
            'segment' => $segment,
        ]);

        return view('frontend.index', ['config' => $jsonConfig]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
