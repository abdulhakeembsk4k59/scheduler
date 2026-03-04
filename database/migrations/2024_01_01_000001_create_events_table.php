<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->string('description', 1000)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('category', ['work', 'personal', 'health', 'learning', 'finance', 'social'])->default('work');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->boolean('is_recurring')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->json('subtasks')->nullable()->default('[]');
            $table->enum('timing_mode', ['specific', 'anytime', 'deadline'])->default('specific');
            $table->enum('resolution', ['pending', 'completed', 'missed', 'rescheduled'])->default('pending');
            $table->integer('reschedule_count')->default(0);
            $table->dateTime('original_start_date')->nullable();
            $table->string('daily_start_time', 5)->nullable(); // "HH:mm"
            $table->string('daily_end_time', 5)->nullable();   // "HH:mm"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
