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
        $query = Booking::with(['room.equipments', 'building'])
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
            'booking_id'         => $booking->id,
            'ref_number'         => $booking->booking_id,
            'user_id'            => $booking->user_id,
            'external_name'      => $booking->external_name,
            'external_email'     => $booking->external_email,
            'external_phone'     => $booking->external_phone,
            'building_id'        => $booking->building_id,
            'building_name'      => $booking->building_name,
            'room_id'            => $booking->room_id,
            'room_name'          => $booking->room_name,
            'booking_start'      => $booking->booking_start,
            'booking_end'        => $booking->booking_end,
            'status_id'          => 5, // รหัสสถานะ "ยกเลิก"
            'status_name'        => 'ยกเลิกการจอง',
            'reason'             => $booking->reason,
            'total_price'        => $booking->total_price,
            'payment_status'     => $booking->payment_status,
            'is_external'        => $booking->is_external,
            'created_at'         => $booking->created_at,
            'updated_at'         => $booking->updated_at,
            'moved_to_history_at' => now(),
            'title' => $booking->title ?? null,
            'setup_date' => $booking->setup_date ?? null,
            'teardown_date' => $booking->teardown_date ?? null,
            'additional_equipment' => $booking->additional_equipment ?? null,
            'coordinator_name' => $booking->coordinator_name ?? null,
            'coordinator_phone' => $booking->coordinator_phone ?? null,
            'coordinator_department' => $booking->coordinator_department ?? null,
        ]);

        // ลบการจองจาก booking ปกติ
        $booking->delete();

        return redirect()->route('my-bookings')->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
    }
}
