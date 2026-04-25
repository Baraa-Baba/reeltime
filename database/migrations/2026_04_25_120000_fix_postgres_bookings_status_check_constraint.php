<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        // Laravel maps enum to a CHECK constraint on PostgreSQL. Rebuild it to include watched.
        DB::statement('ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check');
        DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_check CHECK (status IN ('pending', 'confirmed', 'watched', 'cancelled'))");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check');
        DB::statement("UPDATE bookings SET status = 'confirmed' WHERE status = 'watched'");
        DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_check CHECK (status IN ('pending', 'confirmed', 'cancelled'))");
    }
};
