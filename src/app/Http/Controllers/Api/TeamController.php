<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\TeamCreateRequest;
use App\Http\Requests\Team\TeamDeleteRequest;
use App\Http\Requests\Team\TeamJoinRequest;
use App\Http\Requests\Team\TeamUpdateRequest;
use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    public function create(TeamCreateRequest $request)
    {
        $input = $request->all();
        $team = Team::create($input);

        $user = auth()->user();

        if ($user->is_lead) {
            // delete old team
            Team::where('id', $user->team_id)->delete();
            User::where('team_id', $user->team_id)->update(['team_id' => null]);
        }

        $user->team_id = $team->id;
        $user->is_lead = true;
        $user->save();

        return response()->json([
            'data' =>
            ['message' => 'Create a successful team']
        ]);
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

        if ($user->is_lead) {
            // delete old team
            Team::where('id', $user->team_id)->delete();
            User::where('team_id', $user->team_id)->update(['team_id' => null]);
        }

        $user->team_id = null;
        $user->is_lead = false;
        $user->save();
        return response()->json(['data' => 
            ['message' => 'Successfully left the team']
        ]);
    }

    public function joinTeam(TeamJoinRequest $request)
    {
        $team = Team::find($request->team_id);
        if ($team) {
            $user = auth()->user();

            if ($user->is_lead) {
                // delete old team
                Team::where('id', $user->team_id)->delete();
                User::where('team_id', $user->team_id)->update(['team_id' => null]);
            }
            $user->team_id = $request->team_id;
            $user->is_lead = false;
            $user->save();
            return response()->json([
                'data' => [
                    'message' => 'Join the team successfully',
                    'dataSync' => Team::find($user->team_id)
                ]
            ]);
        } else {
            return response()->json(['message' => 'The team has been deleted!'], 403);
        }

    }

    public function update(TeamUpdateRequest $request)
    {
        $user = auth()->user();
        if ($user->team_id == $request->id && $user->is_lead) {
            $team = Team::find($request->id);
            $team->name = $request->name;
            $team->description = $request->description;
            $team->data = $request->data;
            $team->save();
            return response()->json(['data' =>
                ['message' => 'Team information updated successfully']
            ]);
        }
        return response()->json(['message' => 'You do not have access!'], 403);
    }

    public function delete(TeamDeleteRequest $request)
    {
        $user = auth()->user();
        if ($user->team_id == $request->id && $user->is_lead) {
            Team::where('id', $request->id)->delete();

            $user->is_lead = false;
            $user->save();

            User::where('team_id', $request->id)->update(['team_id' => null]);

            return response()->json([
                'data' =>
                ['message' => 'team deleted successfully']
            ]);
        }
        return response()->json(['message' => 'You do not have access!'], 403);
    }

    public function getMyTeam()
    {
        $user = auth()->user();
        if ($user->team_id) {
            $team = Team::find($user->team_id);
            if ($team) {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Get Team successfully!',
                    ],
                    'data' => [
                        'team' => $team
                    ],
                ]);
            }
        }
        return response()->json(['message' => 'An error has occurred!'], 403);
    }
}
