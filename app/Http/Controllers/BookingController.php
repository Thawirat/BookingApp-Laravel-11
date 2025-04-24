<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
            $room = Room::with('building')->findOrFail($id);

            // Get booked time slots
            $bookedTimeSlots = Booking::where('room_id', $id)
                ->whereIn('status_id', [1, 2, 3])
                ->get(['booking_start', 'booking_end', 'external_name'])
                ->map(function($booking) {
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

                $bookingInfo = 'จองโดย: '.mb_substr($booking->external_name, 0, 1).'xxx';

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
            Log::error('Booking form error: '.$e->getMessage());

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
                'booking_start' => 'required|date|after:today',
                'booking_end' => 'required|date|after:booking_start',
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
            ]);

            // Combine date and time for booking start and end
            $bookingStart = Carbon::parse($validated['booking_start'])->format('Y-m-d') . ' ' . $validated['check_in_time'];
            $bookingEnd = Carbon::parse($validated['booking_end'])->format('Y-m-d') . ' ' . $validated['check_out_time'];

            // Check if the time slot is available
            $existingBooking = Booking::where('room_id', $validated['room_id'])
                ->where(function($query) use ($bookingStart, $bookingEnd) {
                    $query->whereBetween('booking_start', [$bookingStart, $bookingEnd])
                        ->orWhereBetween('booking_end', [$bookingStart, $bookingEnd])
                        ->orWhere(function($q) use ($bookingStart, $bookingEnd) {
                            $q->where('booking_start', '<=', $bookingStart)
                              ->where('booking_end', '>=', $bookingEnd);
                        });
                })
                ->exists();

            if ($existingBooking) {
                return back()->withErrors(['message' => 'ช่วงเวลาที่เลือก已被预订']);
            }

            // Check for overlapping bookings with time slots
            $existingBooking = Booking::where('room_id', $validated['room_id'])
                ->where(function($query) use ($bookingStart, $bookingEnd) {
                    $query->where(function($q) use ($bookingStart, $bookingEnd) {
                        // Check if new booking overlaps with existing booking
                        $q->where(function($inner) use ($bookingStart, $bookingEnd) {
                            $inner->where('booking_start', '<', $bookingEnd)
                                  ->where('booking_end', '>', $bookingStart);
                        });
                    });
                })
                ->exists();

            if ($existingBooking) {
                return back()->withErrors(['message' => 'ช่วงเวลาที่เลือกมีการจองแล้ว'])->withInput();
            }

            // คำนวณราคารวม
            $room = Room::find($validated['room_id']);
            $start = new \DateTime($validated['booking_start']);
            $end = new \DateTime($validated['booking_end']);
            $days = $end->diff($start)->days;
            // ถ้าจองวันเดียว ต้องคิดเป็น 1 วัน
            $days = $days > 0 ? $days : 1;
            $totalPrice = $room->service_rates * $days;

            // สร้างข้อมูลการจอง
            $booking = new Booking;
            $booking->fill($validated);
            $booking->status_id = 3; // สถานะรอการยืนยัน
            $booking->is_external = true;
            $booking->total_price = $totalPrice;
            $booking->payment_status = 'pending';
            $booking->booking_start = $bookingStart;
            $booking->booking_end = $bookingEnd;

            // กรณีผู้ใช้ที่ login แล้ว
            if (auth()->check()) {
                $booking->user_id = auth()->id();
            }

            // จัดการกับไฟล์อัปโหลด - แก้ไขเพื่อตรวจสอบและ debug
            if ($request->hasFile('payment_slip')) {
                try {
                    $file = $request->file('payment_slip');
                    if ($file->isValid()) {
                        $filePath = $file->store('payment_slips', 'public');
                        $booking->payment_slip = $filePath;
                        Log::info('Payment slip saved successfully: '.$filePath);
                    } else {
                        Log::warning('Invalid payment slip file.');
                    }
                } catch (\Exception $e) {
                    Log::error('Error uploading payment slip: '.$e->getMessage());
                }
            } else {
                Log::info('No payment slip provided in the request.');
            }

            $booking->save();

            // ส่งอีเมลแจ้งยืนยันการจอง...

            return redirect()->route('booking.index')->with('success', 'การจองห้องสำเร็จ! กรุณาตรวจสอบอีเมลของคุณเพื่อยืนยันการจอง');

        } catch (\Exception $e) {
            Log::error('Booking failed: '.$e->getMessage(), ['request' => $request->all()]);

            return back()->with('error', 'เกิดข้อผิดพลาดในการจอง: '.$e->getMessage())
                ->withInput();
        }
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

    // ยกเลิกการจอง
    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // ตรวจสอบว่าผู้ใช้มีสิทธิ์ยกเลิกการจองนี้หรือไม่
            if (auth()->check() && $booking->user_id == auth()->id()) {
                $booking->status_id = 4; // สถานะยกเลิก
                $booking->payment_status = 'cancelled';
                $booking->save();

                return back()->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
            } else {
                return back()->with('error', 'คุณไม่มีสิทธิ์ยกเลิกการจองนี้');
            }
        } catch (\Exception $e) {
            Log::error('Cancel booking failed: '.$e->getMessage());

            return back()->with('error', 'เกิดข้อผิดพลาดในการยกเลิกการจอง');
        }
    }
}

