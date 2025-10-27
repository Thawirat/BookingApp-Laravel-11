<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBookingPaymentRequest;
use App\Models\Booking;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingRejectedMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\BookingStatusNotification;

class Booking_dbController extends Controller
{
    public function moveToHistory($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // ป้องกันการบันทึกซ้ำ
            $alreadyMoved = DB::table('booking_history')->where('booking_id', $booking->id)->exists();
            if ($alreadyMoved) {
                Log::info("Booking {$booking->id} already exists in history. Skipping.");
                return;
            }
            Log::info("Preparing to copy booking {$booking->id} to history");

            DB::table('booking_history')->insert([
                'booking_id' => $booking->id,
                'ref_number' => $booking->booking_id,
                'user_id' => $booking->user_id,
                'external_name' => $booking->external_name,
                'external_email' => $booking->external_email,
                'external_phone' => $booking->external_phone,
                'building_id' => $booking->building_id,
                'building_name' => $booking->building_name,
                'room_id' => $booking->room_id,
                'room_name' => $booking->room_name,
                'booking_start' => $booking->booking_start,
                'booking_end' => $booking->booking_end,
                'status_id' => $booking->status_id,
                'status_name' => $booking->status_name,
                'reason' => $booking->reason,
                'participant_count' => $booking->participant_count,
                'booker_info' => $booking->booker_info,
                'approver_name' => $booking->approver_name,
                'approver_position' => $booking->approver_position,
                'total_price' => $booking->total_price,
                'payment_status' => $booking->payment_status,
                'is_external' => $booking->is_external,
                'created_at' => $booking->created_at,
                'updated_at' => now(),
                'moved_to_history_at' => now(),
                'title' => $booking->title ?? null,
                'setup_date' => $booking->setup_date ?? null,
                'teardown_date' => $booking->teardown_date ?? null,
                'additional_equipment' => $booking->additional_equipment ?? null,
                'coordinator_name' => $booking->coordinator_name ?? null,
                'coordinator_phone' => $booking->coordinator_phone ?? null,
                'coordinator_department' => $booking->coordinator_department ?? null,
            ]);

            Log::info("Booking {$booking->id} copied to history successfully.");

            // ❌ ไม่ลบจาก bookings แล้ว
            $booking->delete();
        } catch (\Exception $e) {
            Log::error('Failed to copy booking to history: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user(); // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่

        $this->autoCompletePastBookings(); // เรียกใช้งานระบบ auto-complete

        // ✅ เริ่ม query ด้วย Eloquent + load ความสัมพันธ์ room และ status
        $query = Booking::with(['room', 'status', 'user'])
            ->whereNull('deleted_at');

        // กรอง sub-admin
        if ($user->hasRole('sub-admin')) {
            $buildingIds = $user->buildings()->pluck('buildings.id')->toArray();
            $query->whereIn('building_id', $buildingIds);
        }

        // ไม่แสดงสถานะ ยกเลิก(5) และเสร็จสิ้น(6)
        $query->whereNotIn('status_id', [5, 6]);

        // 🔍 ค้นหา
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'like', "%{$search}%")
                    ->orWhere('external_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('room', function ($q2) use ($search) {
                        $q2->where('room_name', 'like', "%{$search}%");
                    });
            });
        }

        // กรองตามวันที่
        if ($request->filled('booking_date')) {
            $query->whereDate('created_at', $request->booking_date);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('booking_start', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('booking_end', '<=', $request->date_to);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $sort = $request->get('sort', 'desc');
        $query->orderBy('created_at', $sort);

        $bookings = $query->paginate(50)->appends($request->all());

        // 👉 สถิติ
        $totalBookings = Booking::whereNull('deleted_at')->count();
        $pendingBookings = Booking::where('status_id', 3)->whereNull('deleted_at')->count();
        $confirmedBookings = Booking::where('status_id', 4)->whereNull('deleted_at')->count();

        // ดึงข้อมูลสถานะทั้งหมด
        $statuses = DB::table('status')->get();

        return view('dashboard.booking_db', compact(
            'bookings',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'statuses'
        ));
    }


    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $status = Status::findOrFail($request->status_id);

        $booking->status_id = $status->status_id;
        // เพิ่มชื่อผู้อนุมัติ
        $booking->approver_name = Auth::user()->name;
        $booking->approver_position = Auth::user()->position;
        $booking->save();

        event(new \App\Events\BookingStatusUpdated($booking));

        // if ($booking->user) {
        //     $booking->user->notify(new BookingStatusNotification($booking, $status));
        // }

        if ($booking->external_email) {
            if ($status->status_id == 4) { // อนุมัติ
                Mail::to($booking->external_email)->send(new BookingApprovedMail($booking));
            } elseif ($status->status_id == 5) { // ไม่อนุมัติ / ปฏิเสธ
                Mail::to($booking->external_email)->send(new BookingRejectedMail($booking));
            }
        }

        // ตรวจสอบว่าสถานะเป็น 6 และเรียกใช้ moveToHistory
        if (in_array($status->status_id, [5, 6])) {
            $this->moveToHistory($id);
            Log::info("Booking {$id} moved to history."); // ล็อกข้อความเพื่อตรวจสอบ
        }

        return redirect()->route('booking_db')->with('success', "การจองถูกเปลี่ยนสถานะเป็น {$status->status_name} เรียบร้อยแล้ว");
    }

    /**
     * ตรวจสอบและอัปเดตสถานะการจองที่สิ้นสุดไปแล้วโดยอัตโนมัติ
     */
    private function autoCompletePastBookings()
    {
        $now = Carbon::now();

        $pastBookings = Booking::where('booking_end', '<', $now)
            ->whereNotIn('status_id', [5, 6])
            ->get();

        foreach ($pastBookings as $booking) {
            // ปรับสถานะ
            if ($booking->status_id == 3) {
                $booking->status_id = 5; // ยกเลิก
            } elseif ($booking->status_id == 4) {
                $booking->status_id = 6; // เสร็จสิ้น
            } else {
                continue; // ถ้าไม่เข้าเงื่อนไข ข้าม
            }
            $booking->save();

            // ย้ายไปยังประวัติการจอง
            $this->moveToHistory($booking->id);
            $historyController = new BookingHistoryController;
            $historyController->addBookingToHistory($booking);
        }
    }

    // public function confirmPayment(UpdateBookingPaymentRequest $request, $id)
    // {
    //     $booking = Booking::findOrFail($id);

    //     if ($request->hasFile('payment_slip')) {
    //         $booking->payment_slip = $request->file('payment_slip')->store('payment_slips', 'public');
    //     }

    //     $booking->payment_status = $request->payment_status;
    //     $booking->verified_at = now();
    //     $booking->save();

    //     return redirect()->route('booking_db')
    //         ->with('success', 'สถานะการชำระเงินถูกอัปเดตเรียบร้อยแล้ว');
    // }
}
