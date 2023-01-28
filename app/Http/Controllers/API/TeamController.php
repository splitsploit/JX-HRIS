<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;

class TeamController extends Controller
{
    public function fetch()
    {

    }

    public function create(CreateTeamRequest $request)
    {
        try {

            if($request->hasFile('icon'))
            {
                $path = $request->file('icon')->store('public/icons');
            }

            $team = Team::create([
                'name' => $request->name,
                'icon' => $path
            ]);

            if(!$team)
            {
                throw new Exception('Team Not Found!');
            }

            return ResponseFormatter::success($team, 'Team Created');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),500 );
        }
    }
}
