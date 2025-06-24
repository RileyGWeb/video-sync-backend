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
        Schema::create('video_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('platform', ['twitch', 'youtube']);
            $table->string('platform_video_id');
            $table->string('streamer_name');
            $table->string('streamer_id');
            $table->enum('status', ['active', 'paused', 'ended', 'error'])->default('active');
            $table->integer('viewer_count')->default(0);
            $table->decimal('current_timestamp', 10, 3)->default(0); // Video timestamp in seconds
            $table->boolean('is_live')->default(true);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->json('metadata')->nullable(); // Additional platform-specific data
            $table->timestamps();
            
            $table->index(['platform', 'status']);
            $table->index(['streamer_id', 'status']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_sessions');
    }
};
