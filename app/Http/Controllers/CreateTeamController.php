<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;

class CreateTeamController extends Controller
{
    public function createTeam()
    {
        return view('teams.create');
    }

    public function createTeams(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:teams,name',
            ]);

            $userId = Auth::id();

            $result = DB::transaction(function () use ($validated, $userId) {
                $team = Team::create([
                    'name' => $validated['name'],
                    'invite_code' => 'TEAM-' . Str::upper(Str::random(6)),
                    'created_by' => $userId
                ]);

                $team->members()->attach($userId, ['role' => 'owner']);

                return [
                    'team' => $team,
                    'redirect' => route('dashboard')
                ];
            });

            return response()->json([
                'success' => true,
                'team' => $result['team'],
                'redirect' => $result['redirect']
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Team creation failed',
                'message' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }
}
