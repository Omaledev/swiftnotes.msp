<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;

class JoinTeamController extends Controller
{
    public function joinTeam()
    {
        return view('teams.join');
    }

    public function joinTeams(Request $request)
    {
        try {
            $validated = $request->validate([
                'invite_code' => 'required|string|exists:teams,invite_code'
            ]);

            $userId = Auth::id();

            $team = DB::transaction(function () use ($validated, $userId) {
                $team = Team::where('invite_code', $validated['invite_code'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($team->members()->where('user_id', $userId)->exists()) {
                    throw new \Exception('You are already a member of this team', 409);
                }

                $team->members()->attach($userId, ['role' => 'member']);
                return $team;
            });

            return redirect()->route('dashboard')
                ->with('success', "Successfully joined team: {$team->name}!");

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            $statusCode = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
}
