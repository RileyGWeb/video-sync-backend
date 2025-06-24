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
        Schema::create('bits_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_session_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique(); // Twitch transaction ID
            $table->string('user_id'); // Twitch user ID who sent bits
            $table->string('user_name'); // Twitch username
            $table->string('user_login'); // Twitch login name
            $table->integer('bits_used'); // Amount of bits used
            $table->decimal('total_bits_used', 10, 0); // Total bits used by this user in the session
            $table->text('message')->nullable(); // Chat message sent with bits
            $table->boolean('is_anonymous')->default(false);
            $table->json('context'); // Chat context data
            $table->string('product_type')->nullable(); // Type of bits product
            $table->string('product_sku')->nullable(); // SKU of bits product
            $table->decimal('video_timestamp', 10, 3)->nullable(); // When in the video this occurred
            $table->timestamp('twitch_timestamp'); // When Twitch says this occurred
            $table->json('raw_data')->nullable(); // Full webhook payload for debugging
            $table->timestamps();
            
            $table->index(['video_session_id', 'twitch_timestamp']);
            $table->index(['user_id', 'video_session_id']);
            $table->index('twitch_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bits_transactions');
    }
};
