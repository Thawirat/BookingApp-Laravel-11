<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class BookingStatusController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ดึงรายการจองของผู้ใช้คนปัจจุบัน
        $bookings = Booking::with(['room', 'building']) // สมมุติว่า booking มีความสัมพันธ์กับ room & building
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('booking-status.index', compact('bookings'));
    }
}
