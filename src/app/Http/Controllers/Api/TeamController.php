<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\TeamCreateRequest;
use App\Http\Requests\Team\TeamJoinRequest;
use App\Models\Team;

class TeamController extends Controller
{
    public function create(TeamCreateRequest $request)
    {
        $input = request->all();
        $team = Team::create($input);
    }

    public function getList()
    {
        $list = Team::select('id', 'name', 'description', 'data')->withCount('users')->get();
        $user = auth()->user();
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Get All Team successfully!',
            ],
            'data' => [
                'teams' => $list,
                'team_id' => $user->team_id,
                'is_lead' => $user->is_lead
            ],
        ]);
    }

    public function leaveTeam()
    {
        $user = auth()->user();
        $user->team_id = null;
        $user->save();
        return response()->json(['data' => 
            ['message' => 'Successfully left the team']
        ]);
    }

    public function joinTeam(TeamJoinRequest $request)
    {
        $user = auth()->user();
        $user->team_id = $request->team_id;
        $user->save();
        return response()->json(['data' =>
            ['message' => 'Join the team successfully']
        ]);
    }
}
