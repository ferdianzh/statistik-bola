<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\MatchCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $card = MatchCard::all();
        return ModelResource::collection($card);
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
            'match_player_id' => 'required',
            'type' => 'required',
            'minute' => 'required',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }
        
        $card = MatchCard::create($data);

        return response([
            'data' => $card,
            'success' => 'create success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MatchCard $card)
    {
        return response([
            'data' => new ModelResource($card),
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
    public function update(Request $request, MatchCard $card)
    {
        $data = $request->all();
        $card->update($data);
        return response([
            'data' => new ModelResource($card),
            'success' => 'update success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MatchCard $card)
    {
        $card->delete();

        return response([
            'success' => 'delete success',
        ]);
    }
}
