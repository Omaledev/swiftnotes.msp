<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Events\UserOnlineStatusUpdated;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\Cache;


class UserStatusController extends Controller
{
    public function updateOnlineStatus(Request $request)
    {
        $request->validate([
            'is_online' => 'required|boolean',
            'team_id' => 'required|exists:teams,id'
        ]);

        $user = $request->user();
        $team = Team::findOrFail($request->team_id);

        // Update cache
        $key = 'user-is-online-' . $user->id;
        if ($request->is_online) {
            Cache::put($key, true, now()->addMinutes(5));
        } else {
            Cache::forget($key);
        }

        try {
             broadcast(new UserOnlineStatusUpdated($user, $team, $request->is_online))->toOthers();
              return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
             return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
