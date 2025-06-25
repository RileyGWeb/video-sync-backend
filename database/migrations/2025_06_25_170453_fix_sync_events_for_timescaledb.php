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
        // Drop the existing primary key constraint
        Schema::table('sync_events', function (Blueprint $table) {
            $table->dropPrimary();
        });
        
        // Create a new composite primary key that includes occurred_at
        // This is required for TimescaleDB hypertables
        Schema::table('sync_events', function (Blueprint $table) {
            $table->primary(['id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original single-column primary key
        Schema::table('sync_events', function (Blueprint $table) {
            $table->dropPrimary(['id', 'occurred_at']);
        });
        
        Schema::table('sync_events', function (Blueprint $table) {
            $table->primary('id');
        });
    }
};
