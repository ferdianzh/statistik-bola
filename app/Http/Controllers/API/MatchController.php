<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\Matches;
use App\Models\MatchGoal;
use App\Models\MatchPlayerStat;
use App\Models\MatchStat;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matches = Matches::all();
        return ModelResource::collection($matches);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'team_id' => 'required',
            'enemy_id' => 'required|different:team_id',
            'site_id' => 'required',
            'datetime' => 'required|date_format:Y-m-d H:i',
            'is_league' => 'boolean',
            'is_away' => 'boolean',
            'is_finish' => 'boolean',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        $match = Matches::create($data);

        return response([
            'data' => $match,
            'success' => 'create success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Matches $match)
    {
        return response([
            'data' => new ModelResource($match),
            'success' => 'retrieve success',
        ], 200);
    }

    public function showDetailed($id)
    {
        $match = MatchStat::
                    selectRaw('match_id, max(halftime) as halftime')
                    ->where('match_id', $id)
                    ->groupBy('match_id');

        $detailMatch = Matches::joinSub($match, 'match', function($join) {
            $join->on('matches.id', '=', 'match.match_id');
        })->get();

        $matchStatId = MatchStat::select('id')->where('match_id', $id)->get();
        $statId = [];
        foreach ( $matchStatId as $id ) {
            array_push($statId, $id['id']);
        }

        $goals = MatchGoal::whereIn('match_stat_id', $statId)->where('match_player_id', '!=', 1);
        
        $goals = MatchPlayerStat::joinSub($goals, 'goals', function($join){
                        $join->on('match_player_stats.id', '=', 'goals.match_player_id');
                    })->select([
                        'match_player_stats.player_id',
                        'match_player_stats.id as player_stats_id',
                        'goals.minute',
                    ]);
        $detailGoals = Player::joinSub($goals, 'goals', function($join){
                            $join->on('players.id', '=', 'goals.player_id');
                        })->select([
                            'goals.player_id',
                            'players.number as player_number',
                            'players.name',
                            'goals.minute',
                        ])->get()->sortBy('minute');

        $enemyGoals = MatchGoal::whereIn('match_stat_id', $statId)->where('match_player_id', '=', 1)->get()->sortBy('minute');

        return response([
            'data' => [
                'match' => $detailMatch,
                'goals' => $detailGoals,
                'enemyGoals' => $enemyGoals,
            ],
            'success' => 'retrieve success',
        ], 200);
    }

    public function showDetailedByTeam($id)
    {
        $matchesId = Matches::select('id')->where('team_id', $id)->get();
        $matchId = [];
        foreach ( $matchesId as $id ) {
            array_push($matchId, $id['id']);
        }

        $match = MatchStat::
                    selectRaw('match_id, max(halftime) as halftime')
                    ->whereIn('match_id', $matchId)
                    ->groupBy('match_id');

        $matches = Matches::joinSub($match, 'match', function($join) {
            $join->on('matches.id', '=', 'match.match_id');
        })->select(['match_id', 'datetime', 'site_id', 'team_id', 'enemy_id', 'is_away', 'is_league', 'is_finish'])->get();

        $detailMatch = [];
        foreach ($matches as $dmatch) {
            $matchStatId = MatchStat::select('id')->where('match_id', $dmatch['match_id'])->get();
            $statId = [];
            foreach ( $matchStatId as $id ) {
                array_push($statId, $id['id']);
            }
            $dmatch['team_goals'] = MatchGoal::whereIn('match_stat_id', $statId)->where('match_player_id', '!=', 1)->count();
            $dmatch['enemy_goals'] = MatchGoal::whereIn('match_stat_id', $statId)->where('match_player_id', '=', 1)->count();

            array_push($detailMatch, $dmatch);
        }

        return response([
            'data' => $detailMatch,
            'success' => 'retrieve success',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matches $match)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'enemy_id' => 'different:team_id',
            'datetime' => 'date_format:Y-m-d H:i',
            'is_league' => 'boolean',
            'is_away' => 'boolean',
            'is_finish' => 'boolean',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        $match->update($data);

        return response([
            'data' => new ModelResource($match),
            'success' => 'update success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Matches $match)
    {
        $match->delete();

        return response([
            'success' => 'delete success',
        ]);
    }
}
