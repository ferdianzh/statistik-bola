<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();
        return ModelResource::collection($teams);
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
            'name' => 'required|unique:teams',
            'logo' => 'image|mimes:jpeg,png,jpg|max:1024',
            'status' => 'boolean',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        if ( isset($data['logo']) ) {
            $logoName = time().Str::random(5).'.'.$data['logo']->extension();
            $request->file('logo')->storeAs('images/team', $logoName);
            $data['logo'] = $logoName;
        }

        $create = Team::create($data);

        return response([
            'data' => new ModelResource($create),
            'success' => 'create success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return response([
            'data' => new ModelResource($team),
            'success' => 'retrieve success',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        // localhost:8000/api/team/2?_method=PUT
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'unique:teams',
            'logo' => 'image|mimes:jpeg,png,jpg|max:1024',
            'status' => 'boolean',
        ]);

        if ( $validator->fails() ) {
            return response([
                'message' => $validator->errors()
            ], 401);
        }

        if ( isset($data['logo']) ) {
            $logoName = time().Str::random(5).'.'.$data['logo']->extension();
            $request->file('logo')->storeAs('images/team', $logoName);
            $data['logo'] = $logoName;
            
            Storage::delete('images/team/'.$team['logo']);
        }

        $team->update($data);

        return response([
            'data' => new ModelResource($team),
            'success' => 'update success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        if ( $team['id'] != 1 ) {
            Storage::delete('images/team/'.$team['logo']);
        }

        $team->delete();

        return response([
            'success' => 'delete success',
        ]);
    }
}
