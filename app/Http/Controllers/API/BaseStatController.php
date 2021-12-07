<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MatchStat;
use Illuminate\Http\Request;

class BaseStatController extends Controller
{
    protected function createMatchStats($matchId, $halftime, $teamId, $isHome)
    {
        $teamHome = MatchStat::create([
            'match_id' => $matchId,
            'halftime' => $halftime,
            'team_id' => $teamId,
            'is_home' => $isHome,
        ])->id;

        $this->createMatchPlayerStats();

        return $teamHome;
    }

    protected function createMatchPlayerStats()
    {
        //
    }
}
