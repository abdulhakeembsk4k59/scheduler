<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    /**
     * Bulk import events from JSON.
     */
    public function importSchedule(Request $request): JsonResponse
    {
        $request->validate([
            'schedule' => 'required|array|min:1',
            'schedule.*.title' => 'required|string|min:1|max:255',
        ]);

        $schedule = $request->input('schedule');
        $imported = [];
        $errors = [];

        foreach ($schedule as $idx => $item) {
            try {
                // Support both camelCase and snake_case keys
                $title = $item['title'];
                $description = $item['description'] ?? null;
                $startDate = $item['startDate'] ?? $item['start_date'] ?? null;
                $endDate = $item['endDate'] ?? $item['end_date'] ?? null;
                $category = $item['category'] ?? 'work';
                $priority = $item['priority'] ?? 'medium';
                $isRecurring = $item['isRecurring'] ?? $item['is_recurring'] ?? false;
                $subtasks = $item['subtasks'] ?? [];
                $timingMode = $item['timingMode'] ?? $item['timing_mode'] ?? 'specific';
                $dailyStartTime = $item['dailyStartTime'] ?? $item['daily_start_time'] ?? null;
                $dailyEndTime = $item['dailyEndTime'] ?? $item['daily_end_time'] ?? null;

                if (!$startDate || !$endDate) {
                    $errors[] = [
                        'index' => $idx,
                        'title' => $title,
                        'error' => 'Missing start_date or end_date',
                    ];
                    continue;
                }

                // Validate end > start
                if (strtotime($endDate) <= strtotime($startDate)) {
                    $errors[] = [
                        'index' => $idx,
                        'title' => $title,
                        'error' => 'End time must be after start time',
                    ];
                    continue;
                }

                // Generate subtask IDs if missing
                $processedSubtasks = [];
                foreach ($subtasks as $subtask) {
                    $processedSubtasks[] = [
                        'id' => $subtask['id'] ?? Str::uuid()->toString(),
                        'title' => $subtask['title'],
                        'completed' => $subtask['completed'] ?? false,
                    ];
                }

                $event = Event::create([
                    'title' => $title,
                    'description' => $description,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'category' => $category,
                    'priority' => $priority,
                    'is_recurring' => $isRecurring,
                    'subtasks' => $processedSubtasks,
                    'timing_mode' => $timingMode,
                    'daily_start_time' => $dailyStartTime,
                    'daily_end_time' => $dailyEndTime,
                ]);

                $imported[] = $event;
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $idx,
                    'title' => $item['title'] ?? null,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'imported' => $imported,
            'errors' => $errors,
            'total_received' => count($schedule),
            'total_imported' => count($imported),
            'total_errors' => count($errors),
        ], 201);
    }
}
