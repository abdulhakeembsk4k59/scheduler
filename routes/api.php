<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PomodoroController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ImportController;

/*
|--------------------------------------------------------------------------
| API Routes - Scheduler Assistant
|--------------------------------------------------------------------------
*/

// Root & Health
Route::get('/', fn () => response()->json([
    'message' => 'Scheduler Assistant API',
    'version' => '1.0.0',
]));

Route::get('/health', fn () => response()->json(['status' => 'healthy']));

// Events CRUD
Route::delete('/events/bulk', [EventController::class, 'bulkDelete']);
Route::patch('/events/{id}/toggle-complete', [EventController::class, 'toggleComplete']);
Route::apiResource('events', EventController::class);

// Pomodoro
Route::get('/pomodoro/sessions', [PomodoroController::class, 'sessions']);
Route::post('/pomodoro/sessions', [PomodoroController::class, 'createSession']);
Route::get('/pomodoro/stats', [PomodoroController::class, 'stats']);

// Session Attendance (nested under events)
Route::get('/events/{eventId}/sessions', [SessionController::class, 'index']);
Route::get('/events/{eventId}/sessions/stats', [SessionController::class, 'stats']);
Route::get('/events/{eventId}/sessions/pending', [SessionController::class, 'pending']);
Route::post('/events/{eventId}/sessions', [SessionController::class, 'store']);
Route::patch('/events/{eventId}/sessions/{sessionDate}', [SessionController::class, 'update']);

// Bulk Import
Route::post('/import/schedule', [ImportController::class, 'importSchedule']);
