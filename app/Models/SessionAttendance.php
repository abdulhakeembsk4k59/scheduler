<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SessionAttendance extends Model
{
    use HasUuids;

    protected $table = 'session_attendance';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'session_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
