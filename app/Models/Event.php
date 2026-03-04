<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Event extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'category',
        'priority',
        'is_recurring',
        'is_completed',
        'subtasks',
        'timing_mode',
        'resolution',
        'reschedule_count',
        'original_start_date',
        'daily_start_time',
        'daily_end_time',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'original_start_date' => 'datetime',
        'is_recurring' => 'boolean',
        'is_completed' => 'boolean',
        'subtasks' => 'array',
        'reschedule_count' => 'integer',
    ];

    public function sessionAttendances()
    {
        return $this->hasMany(SessionAttendance::class);
    }
}
