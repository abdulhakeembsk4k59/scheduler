<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PomodoroSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PomodoroController extends Controller
{
    /**
     * Get recent pomodoro sessions.
     */
    public function sessions(Request $request): JsonResponse
    {
        $limit = min(100, max(1, (int) $request->input('limit', 50)));

        $sessions = PomodoroSession::orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        return response()->json($sessions);
    }

    /**
     * Record a new pomodoro session.
     */
    public function createSession(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mode' => 'required|in:work,shortBreak,longBreak',
            'duration' => 'required|integer|min:1',
            'completed' => 'nullable|boolean',
        ]);

        $validated['completed'] = $validated['completed'] ?? false;

        $session = PomodoroSession::create($validated);

        return response()->json($session, 201);
    }

    /**
     * Get pomodoro statistics.
     */
    public function stats(): JsonResponse
    {
        $sessions = PomodoroSession::where('mode', 'work')->get();

        $totalSessions = $sessions->count();
        $completedSessions = $sessions->where('completed', true)->count();
        $totalWorkTime = $sessions->where('completed', true)->sum('duration') / 60;
        $avgLength = $completedSessions > 0 ? round($totalWorkTime / $completedSessions, 1) : 0;

        return response()->json([
            'total_sessions' => $totalSessions,
            'total_work_time' => (int) $totalWorkTime,
            'completed_sessions' => $completedSessions,
            'average_session_length' => $avgLength,
        ]);
    }
}
