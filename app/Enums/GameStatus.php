<?php

namespace App\Enums;

enum GameStatus: int
{
    case IN_PROGRESS = 1;
    case FINISHED_WON = 2;
    case FINISHED_LOST = 3;
    case FINISHED_EXPIRED = 4;
    case FINISHED_NOT_STARTED = 5;
}
