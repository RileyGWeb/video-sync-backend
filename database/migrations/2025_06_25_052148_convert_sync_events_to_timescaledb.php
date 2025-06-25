<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, we need to modify the primary key to include occurred_at
        // This is required for TimescaleDB hypertables
        DB::statement('ALTER TABLE sync_events DROP CONSTRAINT sync_events_pkey');
        DB::statement('ALTER TABLE sync_events ADD PRIMARY KEY (id, occurred_at)');
        
        // Now create the hypertable
        // 7-day chunk interval keeps partitions manageable
        DB::statement("
            SELECT create_hypertable(
                'sync_events',
                'occurred_at',
                chunk_time_interval => INTERVAL '7 days',
                if_not_exists       => TRUE
            );
        ");

        // Enable native compression (≈90 % disk savings for JSON/text rows)
        DB::statement("ALTER TABLE sync_events SET (timescaledb.compress, timescaledb.compress_segmentby = 'event_type')");

        // Policy: compress chunks older than 1 day
        DB::statement("
            SELECT add_compression_policy('sync_events', INTERVAL '1 day');
        ");

        // Policy: drop chunks older than 90 days (tweak as you like)
        DB::statement("
            SELECT add_retention_policy('sync_events', INTERVAL '90 days');
        ");

    }

    public function down(): void
    {
        // Convert back to a normal table if rolled back
        DB::statement("SELECT drop_chunks(INTERVAL '0', 'sync_events')"); // drop partitions
        DB::statement('ALTER TABLE sync_events SET (timescaledb.hypertable = false)');
        
        // Revert the primary key back to single column
        DB::statement('ALTER TABLE sync_events DROP CONSTRAINT sync_events_pkey');
        DB::statement('ALTER TABLE sync_events ADD PRIMARY KEY (id)');
    }
};
