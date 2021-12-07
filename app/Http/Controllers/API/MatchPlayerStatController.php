<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\MatchPlayerStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchPlayerStatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matchPlayerStats = MatchPlayerStat::all();
        return ModelResource::collection($matchPlayerStats);
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
            'match_stat_id' => 'required',
            'player_id' => 'required|array|min:11',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        $playerStat = [];
        foreach ( $data['player_id'] as $player ) {
            $newStat = MatchPlayerStat::create([
                'match_stat_id' => $data['match_stat_id'],
                'player_id' => $player,
            ]);
            array_push($playerStat, $newStat['id']);
        }

        return response([
            'data' => $playerStat,
            'success' => 'create success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stat = MatchPlayerStat::find($id);
        return response([
            'data' => new ModelResource($stat),
            'success' => 'retrieve success',
        ], 200);
    }

    public function showByMatch($id)
    {
        $stats = MatchPlayerStat::where('match_stat_id', '=', $id)->get();
        return response([
            'data' => $stats,
            'success' => 'retrive success',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // data = column name and boolean decrease
        $data = $request->all();
        $column = array_key_first($data);
        $value = $data[$column];

        if ( !isset($data['decrease']) || !$data['decrease'] ) {
            MatchPlayerStat::where('id', $id)->increment($column, $value);
            $operator = '+';
        } else {
            MatchPlayerStat::where('id', $id)->decrement($column, $value);
            $operator = '-';
        }

        return response([
            'data' => $column.' '.$operator.' '.$value,
            'success' => 'update success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MatchPlayerStat::find($id)->delete();

        return response([
            'success' => 'delete success',
        ]);
    }
}
