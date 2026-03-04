<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SessionAttendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SessionController extends Controller
{
    /**
     * Get all session attendance records for an event.
     */
    public function index(string $eventId): JsonResponse
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }

        $sessions = SessionAttendance::where('event_id', $eventId)
            ->orderBy('session_date', 'desc')
            ->get();

        return response()->json($sessions);
    }

    /**
     * Get session attendance statistics for an event.
     */
    public function stats(string $eventId): JsonResponse
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }

        $sessions = SessionAttendance::where('event_id', $eventId)->get();

        $totalSessions = $this->calculateTotalSessions($event);
        $attended = $sessions->where('status', 'attended')->count();
        $missed = $sessions->where('status', 'missed')->count();
        $skipped = $sessions->where('status', 'skipped')->count();
        $pending = $totalSessions - $attended - $missed - $skipped;

        $markedSessions = $attended + $missed;
        $attendanceRate = $markedSessions > 0 ? round(($attended / $markedSessions) * 100, 1) : 0;

        $streak = $this->calculateStreak($sessions);

        return response()->json([
            'total_sessions' => $totalSessions,
            'attended' => $attended,
            'missed' => $missed,
            'skipped' => $skipped,
            'pending' => max(0, $pending),
            'attendance_rate' => $attendanceRate,
            'current_streak' => $streak,
        ]);
    }

    /**
     * Create or update a session attendance record.
     */
    public function store(Request $request, string $eventId): JsonResponse
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }

        $validated = $request->validate([
            'session_date' => 'required|string',
            'status' => 'required|in:pending,attended,missed,skipped',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if record already exists for this date
        $existing = SessionAttendance::where('event_id', $eventId)
            ->where('session_date', $validated['session_date'])
            ->first();

        if ($existing) {
            $existing->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? $existing->notes,
            ]);
            return response()->json($existing->fresh());
        }

        $session = SessionAttendance::create([
            'event_id' => $eventId,
            'session_date' => $validated['session_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json($session, 201);
    }

    /**
     * Update a specific session attendance record.
     */
    public function update(Request $request, string $eventId, string $sessionDate): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,attended,missed,skipped',
            'notes' => 'nullable|string|max:500',
        ]);

        $session = SessionAttendance::where('event_id', $eventId)
            ->where('session_date', $sessionDate)
            ->first();

        if (!$session) {
            $event = Event::find($eventId);
            if (!$event) {
                return response()->json(['detail' => 'Event not found'], 404);
            }

            $session = SessionAttendance::create([
                'event_id' => $eventId,
                'session_date' => $sessionDate,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json($session, 201);
        }

        $session->status = $validated['status'];
        if (isset($validated['notes'])) {
            $session->notes = $validated['notes'];
        }
        $session->save();

        return response()->json($session->fresh());
    }

    /**
     * Get dates of pending sessions that need user action.
     */
    public function pending(string $eventId): JsonResponse
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }

        if (!$event->daily_start_time || !$event->daily_end_time) {
            return response()->json([]);
        }

        $markedDates = SessionAttendance::where('event_id', $eventId)
            ->pluck('session_date')
            ->toArray();

        $start = Carbon::parse($event->start_date)->startOfDay();
        $end = Carbon::parse($event->end_date)->startOfDay();
        $today = Carbon::today();

        [$endHour, $endMin] = explode(':', $event->daily_end_time);
        $now = Carbon::now();
        $todaySessionEnded = $now->hour > (int) $endHour ||
            ($now->hour === (int) $endHour && $now->minute >= (int) $endMin);

        $pendingDates = [];
        $current = $start->copy();

        while ($current->lte($end) && $current->lte($today)) {
            $dateStr = $current->format('Y-m-d');

            if (!in_array($dateStr, $markedDates)) {
                if ($current->isSameDay($today)) {
                    if ($todaySessionEnded) {
                        $pendingDates[] = $dateStr;
                    }
                } else {
                    $pendingDates[] = $dateStr;
                }
            }

            $current->addDay();
        }

        return response()->json($pendingDates);
    }

    /**
     * Calculate total number of sessions for an event.
     */
    private function calculateTotalSessions(Event $event): int
    {
        if (!$event->daily_start_time || !$event->daily_end_time) {
            return 0;
        }

        $start = Carbon::parse($event->start_date)->startOfDay();
        $end = Carbon::parse($event->end_date)->startOfDay();

        return $start->diffInDays($end) + 1;
    }

    /**
     * Calculate current streak of consecutive attended sessions.
     */
    private function calculateStreak($sessions): int
    {
        if ($sessions->isEmpty()) {
            return 0;
        }

        $sorted = $sessions->sortByDesc('session_date');
        $streak = 0;

        foreach ($sorted as $session) {
            if ($session->status === 'attended') {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
