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

            $team = DB::transaction(function () use ($validated, $userId) {
                $team = Team::create([
                    'name' => $validated['name'],
                    'invite_code' => 'TEAM-' . Str::upper(Str::random(6)),
                    'created_by' => $userId
                ]);

                $team->members()->attach($userId, ['role' => 'owner']);
                return $team;
            });

            return redirect()
                ->route('dashboard')
                ->with([
                    'success' => 'Team "'.$team->name.'" created successfully!',
                    'invite_code' => $team->invite_code,
                    'current_team' => $team
            ]);

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to create team: '.$e->getMessage())
                ->withInput();
        }
    }
}
