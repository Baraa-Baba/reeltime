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
            // Check if the type already exists
            $typeExists = DB::selectOne("SELECT 1 FROM pg_type WHERE typname = 'booking_status'");

            if ($typeExists) {
                // Type exists, just add the new value
                DB::statement("ALTER TYPE booking_status ADD VALUE IF NOT EXISTS 'watched'");
            } else {
                // Type doesn't exist — create it and update the column
                DB::statement("CREATE TYPE booking_status AS ENUM ('pending', 'confirmed', 'watched', 'cancelled')");
                DB::statement("ALTER TABLE bookings ALTER COLUMN status TYPE booking_status USING status::booking_status");
                DB::statement("ALTER TABLE bookings ALTER COLUMN status SET DEFAULT 'pending'");
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE bookings SET status = 'confirmed' WHERE status = 'watched'");
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending'");
        } else {
            DB::statement("UPDATE bookings SET status = 'confirmed' WHERE status = 'watched'");
            DB::statement("ALTER TABLE bookings ALTER COLUMN status TYPE text");
            DB::statement("DROP TYPE IF EXISTS booking_status");
            DB::statement("CREATE TYPE booking_status AS ENUM ('pending', 'confirmed', 'cancelled')");
            DB::statement("ALTER TABLE bookings ALTER COLUMN status TYPE booking_status USING status::booking_status");
            DB::statement("ALTER TABLE bookings ALTER COLUMN status SET DEFAULT 'pending'");
        }
    }
};