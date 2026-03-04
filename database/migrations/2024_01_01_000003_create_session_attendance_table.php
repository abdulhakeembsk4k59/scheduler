<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_attendance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id');
            $table->string('session_date'); // YYYY-MM-DD format
            $table->enum('status', ['pending', 'attended', 'missed', 'skipped'])->default('pending');
            $table->string('notes', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unique(['event_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_attendance');
    }
};
