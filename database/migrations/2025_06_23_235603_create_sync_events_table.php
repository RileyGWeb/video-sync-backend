<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sync_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_session_id')->constrained()->cascadeOnDelete();
            $table->enum('event_type', ['play', 'pause', 'seek', 'speed_change', 'buffer', 'error', 'viewer_join', 'viewer_leave']);
            $table->decimal('timestamp', 10, 3); // Video timestamp when event occurred
            $table->decimal('playback_rate', 3, 2)->default(1.00); // Playback speed
            $table->string('user_id')->nullable(); // User who triggered the event
            $table->string('user_name')->nullable();
            $table->json('event_data')->nullable(); // Additional event-specific data
            $table->string('client_id')->nullable(); // Client/browser identifier
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
            
            $table->index(['video_session_id', 'event_type']);
            $table->index(['video_session_id', 'occurred_at']);
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_events');
    }
};
