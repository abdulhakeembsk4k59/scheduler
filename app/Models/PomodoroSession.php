<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PomodoroSession extends Model
{
    use HasUuids;

    protected $table = 'pomodoro_sessions';

    public $timestamps = false;

    protected $fillable = [
        'mode',
        'duration',
        'completed',
    ];

    protected $casts = [
        'duration' => 'integer',
        'completed' => 'boolean',
        'created_at' => 'datetime',
    ];
}
