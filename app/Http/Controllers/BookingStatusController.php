<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\BookingHistory;

class BookingStatusController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // รับค่าจากฟอร์มค้นหา/กรอง
        $search = $request->input('search');
        $status = $request->input('status_id');
        $date = $request->input('booking_date');

        // ดึงรายการจองของผู้ใช้พร้อมความสัมพันธ์
        $query = Booking::with(['room', 'building'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // กรองด้วย keyword
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('room', function ($sub) use ($search) {
                    $sub->where('room_name', 'like', '%' . $search . '%');
                })->orWhereHas('building', function ($sub) use ($search) {
                    $sub->where('building_name', 'like', '%' . $search . '%');
                });
            });
        }

        // กรองด้วยสถานะ
        if ($status) {
            $query->where('status_id', $status);
        }

        // กรองด้วยวันที่จอง (start date)
        if ($date) {
            $query->whereDate('booking_start', $date);
        }

        $bookings = $query->paginate(40);

        return view('booking-status.index', compact('bookings', 'search', 'status', 'date'));
    }

    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // ป้องกันการยกเลิกหากได้รับการอนุมัติแล้ว
        if ($booking->status_id == 4) {
            return redirect()->route('my-bookings')->with('error', 'ไม่สามารถยกเลิกการจองที่ได้รับการอนุมัติแล้ว');
        }

        // บันทึกลง booking_histories
        BookingHistory::create([
            'booking_id'    => $booking->id,
            'user_id'       => $booking->user_id,
            'room_id'       => $booking->room_id,
            'building_id'   => $booking->building_id,
            'booking_start' => $booking->booking_start,
            'booking_end'   => $booking->booking_end,
            'booking_date'  => $booking->booking_date,
            'status_id'     => 5, // ยกเลิก
        ]);

        // ลบการจองจาก booking ปกติ
        $booking->delete();

        return redirect()->route('my-bookings')->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
    }
}
