<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ModelResource;
use App\Models\Matches;
use App\Models\MatchStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchStatController extends BaseStatController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matchStats = MatchStat::all();
        return ModelResource::collection($matchStats);
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
            'match_id' => 'required',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        $isFinish = Matches::select('is_finish')->where('id', '=', $data['match_id'])->first()->is_finish;
        if ( $isFinish ) {
            return response([
                'message' => 'match finished'
            ], 401);
        }

        $latestHalf = MatchStat::select('halftime')->where('match_id', '=', $data['match_id'])->max('halftime');
        ( isset($latestHalf) ) ? $data['halftime'] = $latestHalf + 1 : $data['halftime'] = 1;

        $matchStat = MatchStat::create($data);

        return response([
            'data' => $matchStat,
            'success' => 'create success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stat = MatchStat::find($id);
        return response([
            'data' => new ModelResource($stat),
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MatchStat::find($id)->delete();

        return response([
            'success' => 'delete success',
        ]);
    }
}
