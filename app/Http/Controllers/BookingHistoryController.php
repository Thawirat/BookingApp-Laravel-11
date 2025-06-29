<?php

namespace App\Http\Controllers;

use App\Models\BookingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingHistoryController extends Controller
{
    public function addBookingToHistory($booking)
    {
        // ตรวจสอบว่า booking นี้มีสถานะอะไร
        if ($booking->status_id == 3) {
            $newStatusId = 5; // ยกเลิก
        } elseif ($booking->status_id == 4) {
            $newStatusId = 6; // เสร็จสิ้น
        } else {
            // ถ้าไม่ใช่สถานะที่ต้องย้ายไป history ให้หยุดการทำงาน
            return;
        }

        $bookingHistory = new BookingHistory;
        $bookingHistory->fill([
            'booking_id'        => $booking->id,
            'ref_number'        => $booking->booking_id,
            'user_id'           => $booking->user_id,
            'room_id'           => $booking->room_id,
            'external_name'     => $booking->external_name,
            'external_email'    => $booking->external_email,
            'external_phone'    => $booking->external_phone,
            'booking_date'      => now(),
            'start_time'        => $booking->booking_start,
            'end_time'          => $booking->booking_end,
            'purpose'           => $booking->reason,
            'status_id'         => $newStatusId,
            'payment_status'    => 'completed',
            'amount'            => $booking->total_price,
            'moved_to_history_at' => now(),
            'title' => $booking->title ?? null,
            'setup_date' => $booking->setup_date ?? null,
            'teardown_date' => $booking->teardown_date ?? null,
            'additional_equipment' => $booking->additional_equipment ?? null,
            'coordinator_name' => $booking->coordinator_name ?? null,
            'coordinator_phone' => $booking->coordinator_phone ?? null,
            'coordinator_department' => $booking->coordinator_department ?? null,
        ]);
        $bookingHistory->save();
    }

    public function index(Request $request)
    {
        $query = BookingHistory::with(['room.equipments', 'user', 'status']);
        // 🔍 ค้นหาข้อมูล
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_history.booking_id', 'like', "%{$search}%")
                    ->orWhere('booking_history.external_name', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        // 📆 กรองตามวันที่เริ่มต้น
        if ($request->filled('date_from')) {
            $query->whereDate('booking_history.booking_start', '>=', $request->date_from);
        }

        // 📆 กรองตามวันที่สิ้นสุด
        if ($request->filled('date_to')) {
            $query->whereDate('booking_history.booking_end', '<=', $request->date_to);
        }

        // 📆 กรองตามวันที่จอง (แบบระบุวันเดียว)
        if ($request->filled('booking_date')) {
            $query->whereDate('booking_history.created_at', $request->booking_date);
        }

        // ✅ กรองสถานะ
        if ($request->filled('status_id')) {
            $query->where('booking_history.status_id', $request->status_id);
        }

        // 🔃 เรียงลำดับ
        $sort = $request->get('sort', 'desc');
        $bookingHistory = $query->orderBy('booking_history.created_at', $sort)->paginate(50)->appends($request->all());

        // 🔢 นับจำนวน
        $totalBookings = DB::table('booking_history')->count();
        $completedBookings = DB::table('booking_history')->where('status_id', 6)->count();
        $cancelledBookings = DB::table('booking_history')->where('status_id', 5)->count();

        return view('dashboard.booking_history', [
            'bookings' => $bookingHistory,
            'totalBookings' => $totalBookings,
            'completedBookings' => $completedBookings,
            'cancelledBookings' => $cancelledBookings,
        ]);
    }

    public function history(Request $request)
    {
        // สร้าง query สำหรับประวัติการจอง
        $query = DB::table('booking_history')
            ->leftJoin('status', 'booking_history.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'booking_history.user_id', '=', 'users.id')
            ->select(
                'booking_history.*',
                'status.status_name',
                'users.name as user_name'
            );

        // ค้นหาข้อมูล
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_history.booking_id', 'like', "%{$search}%")
                    ->orWhere('booking_history.external_name', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        // กรองตามวันที่
        if ($request->has('booking_date')) {
            $bookingDate = $request->booking_date;
            $query->where(function ($q) use ($bookingDate) {
                $q->whereDate('booking_history.booking_start', '<=', $bookingDate)
                    ->whereDate('booking_history.booking_end', '>=', $bookingDate);
            });
        }

        // เรียงลำดับและแบ่งหน้า
        $bookingHistories = $query->paginate(50);

        return view('dashboard.booking_history', compact('bookingHistories'));
    }
}
