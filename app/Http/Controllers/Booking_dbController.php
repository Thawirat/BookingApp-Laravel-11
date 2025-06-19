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
                'total_price' => $booking->total_price,
                'payment_status' => $booking->payment_status,
                'is_external' => $booking->is_external,
                'created_at' => now(),
                'updated_at' => now(),
                'moved_to_history_at' => now(),
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

        // เรียกใช้งานระบบ auto-complete สำหรับจองที่หมดอายุ
        $this->autoCompletePastBookings();

        // เริ่มสร้าง query
        $query = DB::table('bookings')
            ->whereNull('deleted_at')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.room_id')
            ->select(
                'bookings.*',
                'status.status_name',
                'users.name as user_name',
                'rooms.room_name'
            );

        // ถ้าเป็น sub-admin ให้กรองเฉพาะอาคารที่ดูแล
        if ($user->hasRole('sub-admin')) {
            $buildingIds = $user->buildings()->pluck('buildings.id')->toArray();
            $query->whereIn('bookings.building_id', $buildingIds);
        }

        // ไม่แสดงการจองที่เสร็จสิ้น (6) และยกเลิก (5)
        $query->whereNotIn('bookings.status_id', [5, 6]);

        // 🔍 ค้นหาจาก booking_id, ชื่อผู้จอง, หรือชื่อ user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bookings.booking_id', 'like', "%{$search}%")
                    ->orWhere('bookings.external_name', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('rooms.room_name', 'like', "%{$search}%");
            });
        }

        // 📆 กรองตามวันจอง
        if ($request->filled('booking_date')) {
            $query->whereDate('bookings.created_at', $request->booking_date);
        }

        // 📆 กรองตามวันที่เริ่มต้น
        if ($request->filled('date_from')) {
            $query->whereDate('bookings.booking_start', '>=', $request->date_from);
        }

        // 📆 กรองตามวันที่สิ้นสุด
        if ($request->filled('date_to')) {
            $query->whereDate('bookings.booking_end', '<=', $request->date_to);
        }

        // ✅ กรองตามสถานะ
        if ($request->filled('status_id')) {
            $query->where('bookings.status_id', $request->status_id);
        }

        // 🔃 เรียงลำดับตามวันที่จอง
        $sort = $request->get('sort', 'desc');
        $query->orderBy('bookings.created_at', $sort);

        // 👉 ดึงรายการ bookings พร้อม paginate
        $bookings = $query->paginate(10)->appends($request->all());

        // 👉 คำนวณสถิติต่าง ๆ
        $totalBookings = DB::table('bookings')->whereNull('deleted_at')->count();
        $pendingBookings = DB::table('bookings')->where('status_id', 3)->whereNull('deleted_at')->count(); // รอดำเนินการ
        $confirmedBookings = DB::table('bookings')->where('status_id', 4)->whereNull('deleted_at')->count(); // อนุมัติแล้ว

        // 👉 ดึงข้อมูลสถานะทั้งหมด (หากต้องการใช้ใน dropdown)
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
        $booking->save();

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

        // ค้นหาการจองที่สิ้นสุดแล้วแต่ยังไม่ได้ทำเครื่องหมายว่าเสร็จสิ้น
        $pastBookings = Booking::where('booking_end', '<', $now)
            ->whereNotIn('status_id', [5, 6]) // ไม่รวมที่ยกเลิกหรือเสร็จสิ้นแล้ว
            ->get();

        foreach ($pastBookings as $booking) {
            // อัปเดตสถานะเป็น "ดำเนินการเสร็จสิ้น" (status_id = 6)
            $booking->status_id = 6;
            $booking->save();

            // ย้ายไปยังประวัติการจอง
            $this->moveToHistory($booking->id);
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
