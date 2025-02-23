<?php

namespace App\Enums;

enum GameResultMessage: string
{
    case CAMPAIGN_ENDED = "Sorry, the Campaign Ended";
    case CAMPAIGN_NOT_STARTED = "This Campaign has Not Started Yet";
    case GAME_LOST = "Sorry, You Lost the Game";
    case GAME_WON = "Congrats! You've Won the Game";
}
