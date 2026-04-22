<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'watched', 'cancelled') NOT NULL DEFAULT 'pending'");
        } else {
            // Add 'watched' to the existing enum type
            DB::statement("ALTER TYPE booking_status ADD VALUE IF NOT EXISTS 'watched'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE bookings SET status = 'confirmed' WHERE status = 'watched'");
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending'");
        } else {
            // PostgreSQL can't remove enum values directly, so we recreate the type
            DB::statement("UPDATE bookings SET status = 'confirmed' WHERE status = 'watched'");
            DB::statement("ALTER TABLE bookings ALTER COLUMN status TYPE text");
            DB::statement("DROP TYPE IF EXISTS booking_status");
            DB::statement("CREATE TYPE booking_status AS ENUM ('pending', 'confirmed', 'cancelled')");
            DB::statement("ALTER TABLE bookings ALTER COLUMN status TYPE booking_status USING status::booking_status");
            DB::statement("ALTER TABLE bookings ALTER COLUMN status SET DEFAULT 'pending'");
        }
    }
};