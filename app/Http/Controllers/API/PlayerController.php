<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $player = Player::all();
        return ModelResource::collection($player);
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
            'number' => 'required',
            'name' => 'required',
            'position' => 'required',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        $create = Player::create($data);

        return response([
            'data' => new ModelResource($create),
            'success' => 'create success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return response([
            'data' => new ModelResource($player),
            'success' => 'retrieve success',
        ], 200);
    }

    public function showByTeam($team_id)
    {
        $player = Player::where('team_id', '=', $team_id)->get();
        return response([
            'data' => $player,
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
    public function update(Request $request, Player $player)
    {
        // $data = $request->all();
        // foreach ( $data as $dKey => $dvalue ) {
        //     if ( is_null($dvalue) ) {
        //         $data[$dKey] = $player[$dKey];
        //     }
        // }

        $player->update($request->all());

        return response([
            'data' => new ModelResource($player),
            'success' => 'update success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->delete();

        return response([
            'success' => 'delete success',
        ]);
    }
}
