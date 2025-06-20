<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BookingHistory;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // ข้อมูลวันหยุดประจำปี 2025
    public function booking()
    {
        $buildings = Building::with('rooms')->get();
        $rooms = Room::with('status')->get();

        return view('booking', compact('buildings', 'rooms'));
    }

    private $holidays = [
        '2025-01-01' => 'วันขึ้นปีใหม่',
        '2025-02-10' => 'วันมาฆบูชา',
        '2025-04-06' => 'วันจักรี',
        '2025-04-13' => 'วันสงกรานต์',
        '2025-04-14' => 'วันสงกรานต์',
        '2025-04-15' => 'วันสงกรานต์',
        '2025-05-01' => 'วันแรงงานแห่งชาติ',
        '2025-05-05' => 'วันฉัตรมงคล',
        '2025-05-13' => 'วันพืชมงคล',
        '2025-06-03' => 'วันเฉลิมพระชนมพรรษาสมเด็จพระราชินี',
        '2025-07-11' => 'วันอาสาฬหบูชา',
        '2025-07-12' => 'วันเข้าพรรษา',
        '2025-07-28' => 'วันเฉลิมพระชนมพรรษา ร.10',
        '2025-08-12' => 'วันแม่แห่งชาติ',
        '2025-10-13' => 'วันคล้ายวันสวรรคต ร.9',
        '2025-10-23' => 'วันปิยมหาราช',
        '2025-12-05' => 'วันพ่อแห่งชาติ/วันชาติ',
        '2025-12-10' => 'วันรัฐธรรมนูญ',
        '2025-12-31' => 'วันสิ้นปี',
    ];

    public function index()
    {
        $buildings = Building::with('rooms')->get();
        $rooms = Room::with('status')->get();

        return view('booking', compact('buildings', 'rooms'));
    }

    public function showBookingForm($id)
    {
        try {
            $room = Room::with(['building', 'equipments'])->findOrFail($id);
            // Get booked time slots
            $bookedTimeSlots = Booking::where('room_id', $id)
                ->whereIn('status_id', [1, 2, 3])
                ->get(['booking_start', 'booking_end', 'external_name'])
                ->map(function ($booking) {
                    return [
                        'date' => Carbon::parse($booking->booking_start)->format('Y-m-d'),
                        'start' => Carbon::parse($booking->booking_start)->format('H:i'),
                        'end' => Carbon::parse($booking->booking_end)->format('H:i'),
                        'name' => mb_substr($booking->external_name, 0, 1) . 'xxx'
                    ];
                })
                ->groupBy('date');

            // Get booked dates
            $bookedDates = Booking::where('room_id', $id)
                ->whereIn('status_id', [1, 2, 3])
                ->get(['booking_start', 'booking_end', 'external_name']);

            // Process booked dates
            $bookedDetails = [];
            foreach ($bookedDates as $booking) {
                $start = new \DateTime($booking->booking_start);
                $end = new \DateTime($booking->booking_end);

                $period = new \DatePeriod(
                    $start,
                    new \DateInterval('P1D'),
                    $end
                );

                $bookingInfo = 'จองโดย: ' . mb_substr($booking->external_name, 0, 1) . 'xxx';

                foreach ($period as $date) {
                    $formattedDate = $date->format('Y-m-d');
                    $bookedDetails[$formattedDate] = $bookingInfo;
                }
            }
            // Get holidays
            $holidaysWithNames = $this->holidays;

            // Get all disabled days
            $disabledDays = array_keys($holidaysWithNames);

            return view('partials.booking-form', compact(
                'room',
                'disabledDays',
                'holidaysWithNames',
                'bookedDetails',
                'bookedTimeSlots'
            ));
        } catch (\Exception $e) {
            Log::error('Booking form error: ' . $e->getMessage());

            return back()->with('error', 'ไม่พบห้องที่ต้องการ หรือเกิดข้อผิดพลาดในการแสดงแบบฟอร์ม');
        }
    }
    public function store(Request $request)
    {
        try {
            Log::debug('Incoming booking request:', $request->all());

            // Validate request data
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,room_id',
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'building_name' => 'required|string|max:255',
                'external_name' => 'required|string|max:255',
                'external_email' => 'required|email|max:255',
                'external_phone' => 'required|string|max:20',
                'external_position' => 'required|string|max:255',
                'external_address' => 'required|string|max:255',
                'booking_start' => 'required|date|after_or_equal:today',
                'booking_end' => 'required|date|after_or_equal:booking_start',
                'check_in_time' => [
                    'required',
                    'date_format:H:i',
                    'after_or_equal:08:00',
                    'before:22:00'
                ],
                'check_out_time' => [
                    'required',
                    'date_format:H:i',
                    'after:check_in_time',
                    'before_or_equal:23:00'
                ],
                'reason' => 'nullable|string',
                'payment_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'participant_count' => 'nullable|integer|min:1',
                'purpose' => 'nullable|string',
                'booker_info' => 'nullable|string'
            ]);

            // Combine date and time
            $bookingStart = Carbon::parse($validated['booking_start'])->format('Y-m-d') . ' ' . $validated['check_in_time'];
            $bookingEnd = Carbon::parse($validated['booking_end'])->format('Y-m-d') . ' ' . $validated['check_out_time'];

            // Check for conflict
            $existingBooking = Booking::where('room_id', $validated['room_id'])
                ->where(function ($query) use ($bookingStart, $bookingEnd) {
                    $query->where(function ($q) use ($bookingStart, $bookingEnd) {
                        $q->where('booking_start', '<', $bookingEnd)
                            ->where('booking_end', '>', $bookingStart);
                    });
                })
                ->exists();

            if ($existingBooking) {
                return back()->withErrors(['message' => 'ช่วงเวลาที่เลือก已被预订']);
            }

            // Create booking
            $booking = new Booking;
            $booking->fill($validated);
            $booking->status_id = 3;
            $booking->is_external = true;
            $booking->booking_start = $bookingStart;
            $booking->booking_end = $bookingEnd;
            $booking->total_price = null; // ไม่ใช้ service_rates แล้ว กำหนดเป็น null หรือไม่ต้องเซตก็ได้

            if (auth()->check()) {
                $booking->user_id = auth()->id();
            }

            // Handle file upload
            if ($request->hasFile('payment_slip')) {
                try {
                    $file = $request->file('payment_slip');
                    if ($file->isValid()) {
                        $filePath = $file->store('payment_slips', 'public');
                        $booking->payment_slip = $filePath;
                        $booking->payment_status = 'pending';
                        Log::info('Payment slip saved successfully: ' . $filePath);
                    } else {
                        Log::warning('Invalid payment slip file.');
                        $booking->payment_status = 'unpaid';
                    }
                } catch (\Exception $e) {
                    Log::error('Error uploading payment slip: ' . $e->getMessage());
                    $booking->payment_status = 'unpaid';
                }
            } else {
                Log::info('No payment slip provided in the request.');
                $booking->payment_status = 'unpaid';
            }
            $booking->booking_id = $this->generateBookingId();
            $booking->save();

            return redirect()->route('booking.index')->with('success', 'การจองห้องสำเร็จ! กรุณาตรวจสอบอีเมลของคุณเพื่อยืนยันการจอง');
        } catch (\Exception $e) {
            Log::error('Booking failed: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'เกิดข้อผิดพลาดในการจอง: ' . $e->getMessage())->withInput();
        }
    }

    private function generateBookingId()
    {
        $lastBookingId = Booking::withTrashed()->max('booking_id');

        if (!$lastBookingId) {
            return '000001';
        }

        $lastNumber = intval($lastBookingId);
        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

        return $newNumber;
    }


    public function show($id)
    {
        $booking = Booking::with(['room', 'building', 'status'])->findOrFail($id);

        return view('dashboard.booking_show', compact('booking'));
    }

    // แสดงรายการจองของผู้ใช้ปัจจุบัน
    public function myBookings()
    {
        if (auth()->check()) {
            $bookings = Booking::where('user_id', auth()->id())
                ->with(['room', 'building', 'status'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            return redirect()->route('login')
                ->with('error', 'กรุณาเข้าสู่ระบบเพื่อดูรายการจองของคุณ');
        }

        return view('dashboard.my_bookings', compact('bookings'));
    }
    public function myHistory(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบเพื่อดูประวัติการจองของคุณ');
        }

        $query = BookingHistory::where('user_id', auth()->id());

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('room_name', 'like', '%' . $request->q . '%')
                    ->orWhere('building_name', 'like', '%' . $request->q . '%')
                    ->orWhere('id', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        $sort = $request->input('sort', 'desc');
        $bookings = $query->orderBy('moved_to_history_at', $sort)->paginate(50);

        return view('booking-status.myhistory', compact('bookings'));
    }

    // ยกเลิกการจอง
    public function cancel($id)
    {
        try {
            $booking = Booking::with(['building', 'room', 'status'])->findOrFail($id);
            // ตรวจสอบว่าผู้ใช้มีสิทธิ์ยกเลิกการจองนี้หรือไม่
            if (auth()->check() && $booking->user_id == auth()->id()) {
                // กำหนดสถานะ
                $booking->status_id = 5; // ยกเลิก
                $booking->payment_status = 'cancelled';
                $booking->save();

                // ย้ายข้อมูลไปยัง booking_history
                DB::beginTransaction();

                BookingHistory::create([
                    'booking_id' => $booking->id,
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
                    'status_name' => $booking->status->status_name ?? 'ยกเลิก',
                    'reason' => $booking->reason,
                    'total_price' => $booking->total_price,
                    'payment_status' => $booking->payment_status,
                    'is_external' => $booking->is_external,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                    'moved_to_history_at' => now(),
                ]);

                $booking->delete();
                DB::commit();
                return back()->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว และย้ายไปยังประวัติการจอง');
            } else {
                return back()->with('error', 'คุณไม่มีสิทธิ์ยกเลิกการจองนี้');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel booking failed: ' . $e->getMessage());

            return back()->with('error', 'เกิดข้อผิดพลาดในการยกเลิกการจอง');
        }
    }
    // public function uploadSlip(Request $request, Booking $booking)
    // {
    //     $request->validate([
    //         'payment_slip' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //     ]);

    //     try {
    //         $file = $request->file('payment_slip');
    //         $filePath = $file->store('payment_slips', 'public');

    //         $booking->payment_slip = $filePath;
    //         $booking->payment_status = 'pending'; // เปลี่ยนสถานะตามต้องการ
    //         $booking->save();

    //         Log::info("อัปโหลดสลิปสำเร็จสำหรับ booking ID: {$booking->id}");

    //         return back()->with('success', 'อัปโหลดสลิปสำเร็จ');
    //     } catch (\Exception $e) {
    //         Log::error("อัปโหลดสลิปล้มเหลวสำหรับ booking ID {$booking->id}: " . $e->getMessage());
    //         return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลดสลิป');
    //     }
    // }

    public function downloadBookingPdf($id)
    {
        $booking = Booking::findOrFail($id);

        $pdf = Pdf::loadView('booking-status.pdf-report', compact('booking'))
            ->setPaper('A4', 'portrait'); // กำหนดขนาดและแนวกระดาษ

        return $pdf->stream('บันทึกการจอง' . $booking->room_name . '.pdf');
    }

    // public function downloadSlipPdf($id)
    // {
    //     $booking = Booking::findOrFail($id);
    //     $pdf = Pdf::loadView('booking.slip', compact('booking'));
    //     return $pdf->download('ใบเสร็จรับเงินเลขที่' . $booking->id . '.pdf');
    // }

    public function downloadAllHistoryPdf()
    {
        $bookings = BookingHistory::where('user_id', auth()->id())
            ->orderBy('moved_to_history_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('booking-status.pdf-history', compact('bookings'))->setPaper('A4', 'portrait');
        return $pdf->stream('ประวัติการจองห้อง.pdf');
    }
    public function downloadHistoryPdf($id)
    {
        $booking = BookingHistory::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์ดูข้อมูลนี้');
        }

        $pdf = Pdf::loadView('booking-status.pdf-single-history', compact('booking'))->setPaper('A4', 'portrait');
        return $pdf->stream('การจองห้อง' . $booking->room_name . '.pdf');
    }
}
