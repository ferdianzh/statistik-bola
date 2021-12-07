<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\MatchGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goals = MatchGoal::all();
        return ModelResource::collection($goals);
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
            'minute' => 'required',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }
        
        $goal = MatchGoal::create($data);

        return response([
            'data' => $goal,
            'success' => 'create success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MatchGoal $goal)
    {
        return response([
            'data' => new ModelResource($goal),
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
    public function update(Request $request, MatchGoal $goal)
    {
        $data = $request->all();
        $goal->update($data);
        return response([
            'data' => new ModelResource($goal),
            'success' => 'update success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MatchGoal $goal)
    {
        $goal->delete();

        return response([
            'success' => 'delete success',
        ]);
    }
}
