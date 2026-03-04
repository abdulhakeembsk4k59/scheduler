<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * List events with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Event::query();

        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->input('end_date'));
        }
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }
        if ($request->has('completed')) {
            $query->where('is_completed', filter_var($request->input('completed'), FILTER_VALIDATE_BOOLEAN));
        }

        $skip = max(0, (int) $request->input('skip', 0));
        $limit = min(5000, max(1, (int) $request->input('limit', 1000)));

        $events = $query->orderBy('start_date')
            ->skip($skip)
            ->take($limit)
            ->get();

        return response()->json($events);
    }

    /**
     * Get a single event by ID.
     */
    public function show(string $id): JsonResponse
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    /**
     * Create a new event.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|min:1|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'category' => 'nullable|in:work,personal,health,learning,finance,social',
            'priority' => 'nullable|in:high,medium,low',
            'is_recurring' => 'nullable|boolean',
            'subtasks' => 'nullable|array',
            'subtasks.*.id' => 'nullable|string',
            'subtasks.*.title' => 'required|string',
            'subtasks.*.completed' => 'nullable|boolean',
            'timing_mode' => 'nullable|in:specific,anytime,deadline',
            'daily_start_time' => 'nullable|string|regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
            'daily_end_time' => 'nullable|string|regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        // Validate: End date must be after start date
        if ($endDate->lte($startDate)) {
            return response()->json(['detail' => 'End time must be after start time'], 400);
        }

        // Validate: Can't schedule in the past (allow same day)
        $today = Carbon::today();
        if ($startDate->startOfDay()->lt($today)) {
            return response()->json(['detail' => 'Cannot schedule events in the past'], 400);
        }

        // Set defaults
        $validated['category'] = $validated['category'] ?? 'work';
        $validated['priority'] = $validated['priority'] ?? 'medium';
        $validated['is_recurring'] = $validated['is_recurring'] ?? false;
        $validated['timing_mode'] = $validated['timing_mode'] ?? 'specific';
        $validated['subtasks'] = $validated['subtasks'] ?? [];

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    /**
     * Update an existing event.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|min:1|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category' => 'nullable|in:work,personal,health,learning,finance,social',
            'priority' => 'nullable|in:high,medium,low',
            'is_recurring' => 'nullable|boolean',
            'is_completed' => 'nullable|boolean',
            'subtasks' => 'nullable|array',
            'timing_mode' => 'nullable|in:specific,anytime,deadline',
            'resolution' => 'nullable|in:pending,completed,missed,rescheduled',
            'daily_start_time' => 'nullable|string|regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
            'daily_end_time' => 'nullable|string|regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
        ]);

        $event->update($validated);

        return response()->json($event->fresh());
    }

    /**
     * Delete a single event.
     */
    public function destroy(string $id): JsonResponse
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }

        $event->delete();
        return response()->json(null, 204);
    }

    /**
     * Delete all events, optionally filtered by category.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $query = Event::query();
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }
        $count = $query->count();
        $query->delete();

        return response()->json(['deleted' => $count]);
    }

    /**
     * Toggle the completion status of an event.
     */
    public function toggleComplete(string $id): JsonResponse
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['detail' => 'Event not found'], 404);
        }

        // Validate: Can only mark complete if event has started
        if (!$event->is_completed && Carbon::parse($event->start_date)->isFuture()) {
            return response()->json([
                'detail' => "Cannot mark as complete - event hasn't started yet"
            ], 400);
        }

        $event->is_completed = !$event->is_completed;
        $event->save();

        return response()->json($event->fresh());
    }
}
