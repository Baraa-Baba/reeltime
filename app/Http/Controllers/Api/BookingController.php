<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Update booking status only
     */
    public function update(Request $request, $booking_id)
    {
        $booking = Booking::where('booking_id', $booking_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Only allow cancellation if status is 'confirmed' or 'pending'
        if (!in_array($booking->status, ['confirmed', 'pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cannot be cancelled.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:cancelled'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Restore seats to showtime
            $showtime = Showtime::lockForUpdate()->findOrFail($booking->showtime_id);
            $showtime->available_seats += $booking->seats;
            $showtime->save();

            // Update booking status
            $booking->status = 'cancelled';
            $booking->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.',
                'booking' => [
                    'id' => $booking->booking_id,
                    'status' => $booking->status,
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($booking_id)
    {
        $booking = Booking::with(['user', 'showtime.movie', 'showtime.cinema'])
                        ->find($booking_id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $booking
        ]);
    }
}