<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Status;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Building;
use Illuminate\Support\Facades\Log;
use App\Models\BookingHistory;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Get view type (month, week, day, list, table)
        $view = $request->get('view', 'month');

        // Get the current date or the date from request
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now();
        $currentDate = $date->format('Y-m-d');

        // Get the status list with colors from config
        $statusList = Status::all()->map(function ($status) {
            return [
                'status_id' => $status->status_id,
                'status_name' => $status->status_name,
                'color' => config('status.colors.' . $status->status_id, '#607D8B'),
            ];
        });

        // Data for navigation
        $prevMonth = $date->copy()->subMonth()->format('Y-m-d');
        $nextMonth = $date->copy()->addMonth()->format('Y-m-d');
        $currentMonth = $date->locale('th')->translatedFormat('F') . ' ' . ($date->year + 543);

        switch ($view) {
            case 'month':
                return $this->monthView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            case 'week':
                return $this->weekView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            case 'day':
                return $this->dayView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            case 'list':
                return $this->listView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            case 'table':
                return $this->tableView($request, $date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            default:
                return $this->monthView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
        }
    }

    private function monthView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        $firstDay = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $lastDay = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        $period = CarbonPeriod::create($firstDay, $lastDay);

        // ────────────────────────
        // ดึงจาก bookings
        $bookings = Booking::where(function ($query) use ($firstDay, $lastDay) {
            $query->whereBetween('booking_start', [$firstDay, $lastDay])
                ->orWhereBetween('booking_end', [$firstDay, $lastDay])
                ->orWhere(function ($query) use ($firstDay, $lastDay) {
                    $query->where('booking_start', '<', $firstDay)
                        ->where('booking_end', '>', $lastDay);
                });
        })
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->get();

        // ────────────────────────
        // ดึงจาก booking_history
        $histories = BookingHistory::where(function ($query) use ($firstDay, $lastDay) {
            $query->whereBetween('booking_start', [$firstDay, $lastDay])
                ->orWhereBetween('booking_end', [$firstDay, $lastDay])
                ->orWhere(function ($query) use ($firstDay, $lastDay) {
                    $query->where('booking_start', '<', $firstDay)
                        ->where('booking_end', '>', $lastDay);
                });
        })
            ->select('booking_history.*', 'status.status_name', 'booking_history.status_id')
            ->leftJoin('status', 'booking_history.status_id', '=', 'status.status_id')
            ->get();

        // ────────────────────────
        // รวม bookings + history เข้าด้วยกัน
        $allBookings = $bookings->concat($histories)->map(function ($booking) {
            $booking->statusColor = config('status.colors.' . $booking->status_id, '#607D8B');
            return $booking;
        });

        Log::info('Bookings + History:', ['count' => $allBookings->count()]);

        // ────────────────────────
        // สร้างโครงสร้างปฏิทิน
        $calendarData = [];
        $currentWeek = [];

        foreach ($period as $day) {
            $dayFormat = $day->format('Y-m-d');

            $dayData = [
                'day' => $day->format('j'),
                'date' => $dayFormat,
                'currentMonth' => $day->format('m') === $date->format('m'),
                'today' => $day->isToday(),
            ];

            $currentWeek[] = $dayData;

            if ($day->dayOfWeek === Carbon::SATURDAY) {
                $calendarData[] = $currentWeek;
                $currentWeek = [];
            }
        }

        if (! empty($currentWeek)) {
            $calendarData[] = $currentWeek;
        }

        return view('calendar.index', compact(
            'calendarData',
            'statusList',
            'prevMonth',
            'nextMonth',
            'currentMonth',
            'currentDate',
            'view',
            'allBookings'
        ));
    }

    private function weekView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        $startOfWeek = $date->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SATURDAY);

        $weekDays = [];
        $dayNames = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'date' => $day->format('Y-m-d'),
                'dayName' => $dayNames[$i],
                'today' => $day->isToday(),
            ];
        }

        $bookings = Booking::whereBetween('booking_start', [$startOfWeek, $endOfWeek])
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->get()
            ->map(function ($booking) {
                $booking->statusColor = config('status.colors.' . $booking->status_id, '#607D8B');
                return $booking;
            });

        $timeSlots = $this->getTimeSlots();

        $bookingsByDay = [];
        foreach ($bookings as $booking) {
            $bookingDate = Carbon::parse($booking->booking_start)->format('Y-m-d');
            $bookingTime = Carbon::parse($booking->booking_start)->format('H:00');
            $bookingsByDay[$bookingDate][$bookingTime][] = $booking;
        }

        return view('calendar.index', compact(
            'weekDays',
            'timeSlots',
            'bookingsByDay',
            'statusList',
            'prevMonth',
            'nextMonth',
            'currentMonth',
            'currentDate',
            'view'
        ));
    }

    private function dayView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        $dayViewDate = $date->locale('th')->translatedFormat('วัน l ที่ j F พ.ศ.') . ' ' . ($date->year + 543);

        $bookings = Booking::whereDate('booking_start', $date->format('Y-m-d'))
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->get()
            ->map(function ($booking) {
                $booking->statusColor = config('status.colors.' . $booking->status_id, '#607D8B');
                return $booking;
            });

        $timeSlots = $this->getTimeSlots();

        $bookingsByTime = [];
        foreach ($bookings as $booking) {
            $bookingTime = Carbon::parse($booking->booking_start)->format('H:00');
            $bookingsByTime[$bookingTime][] = $booking;
        }

        return view('calendar.index', compact(
            'dayViewDate',
            'timeSlots',
            'bookingsByTime',
            'statusList',
            'prevMonth',
            'nextMonth',
            'currentMonth',
            'currentDate',
            'view'
        ));
    }

    private function listView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $listBookings = Booking::whereBetween('booking_start', [$startOfMonth, $endOfMonth])
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->get()
            ->map(function ($booking) {
                $booking->statusColor = config('status.colors.' . $booking->status_id, '#607D8B');
                return $booking;
            });

        return view('calendar.index', compact(
            'listBookings',
            'statusList',
            'prevMonth',
            'nextMonth',
            'currentMonth',
            'currentDate',
            'view'
        ));
    }

    private function tableView($request, $date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        $building_id = $request->get('building_id');
        $startDate = $date->format('Y-m-d');
        $endDate = $date->copy()->addDays(6)->format('Y-m-d');

        // สร้างข้อมูลวันที่ 7 วัน
        $tableDates = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDay = $date->copy()->addDays($i);
            $tableDates[] = [
                'date' => $currentDay->format('Y-m-d'),
                'day_th' => $this->getDayThai($currentDay->format('l')),
                'day_full' => $currentDay->locale('th')->translatedFormat('j M'),
                'is_holiday' => in_array($currentDay->format('w'), [0, 6]),
                'is_today' => $currentDay->isToday(),
            ];
        }

        // ดึงข้อมูลอาคารทั้งหมด
        $buildings = Building::select('id as building_id', 'building_name')->get();

        // ดึงข้อมูลห้อง
        $roomsQuery = Room::with('building:id,building_name');
        if ($building_id) {
            $roomsQuery->where('building_id', $building_id);
        }
        $tableRooms = $roomsQuery->get();

        // ดึงข้อมูลการจองที่อาจจะเริ่มก่อนหน้าหรือสิ้นสุดหลังจากช่วงที่แสดง
        $bookingsQuery = Booking::with(['status:status_id,status_name', 'user:id,name'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('booking_start', [$startDate, $endDate])
                    ->orWhereBetween('booking_end', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('booking_start', '<=', $startDate)
                            ->where('booking_end', '>=', $endDate);
                    });
            })
            ->select('id', 'room_id', 'building_id', 'booking_start', 'booking_end', 'status_id', 'user_id', 'external_name');

        if ($building_id) {
            $bookingsQuery->where('building_id', $building_id);
        }

        $bookings = $bookingsQuery->get()->map(function ($booking) {
            $booking->statusColor = config('status.colors.' . $booking->status_id, '#607D8B');
            return $booking;
        });

        // จัดกลุ่มข้อมูลการจองตาม room_id
        $tableBookingData = [];
        foreach ($bookings as $booking) {
            $bookingStart = Carbon::parse($booking->booking_start);
            $bookingEnd = Carbon::parse($booking->booking_end);

            // หาวันที่เริ่มต้นและสิ้นสุดในช่วงที่แสดง
            $displayStartDate = max($bookingStart->format('Y-m-d'), $startDate);
            $displayEndDate = min($bookingEnd->format('Y-m-d'), $endDate);

            // คำนวณ colspan
            $startIndex = null;
            $endIndex = null;

            foreach ($tableDates as $index => $dateInfo) {
                if ($dateInfo['date'] === $displayStartDate) {
                    $startIndex = $index;
                }
                if ($dateInfo['date'] === $displayEndDate) {
                    $endIndex = $index;
                }
            }

            if ($startIndex !== null && $endIndex !== null) {
                $colspan = $endIndex - $startIndex + 1;

                $tableBookingData[$booking->room_id][] = [
                    'id' => $booking->id,
                    'start_index' => $startIndex,
                    'colspan' => $colspan,
                    'time' => $bookingStart->format('H:i') . ' - ' . $bookingEnd->format('H:i'),
                    'date_range' => $bookingStart->locale('th')->translatedFormat('j M') .
                        ($bookingStart->format('Y-m-d') !== $bookingEnd->format('Y-m-d') ?
                            ' - ' . $bookingEnd->locale('th')->translatedFormat('j M') : ''),
                    'user_name' => $booking->user->name ?? $booking->external_name ?? 'ไม่ระบุ',
                    'status_name' => $booking->status->status_name ?? 'ไม่ทราบ',
                    'status_id' => $booking->status_id ?? 0,
                    'statusColor' => $booking->statusColor,
                    'booking_start' => $booking->booking_start,
                    'booking_end' => $booking->booking_end,
                ];
            }
        }

        return view('calendar.index', compact(
            'tableDates',
            'buildings',
            'tableRooms',
            'tableBookingData',
            'building_id',
            'statusList',
            'prevMonth',
            'nextMonth',
            'currentMonth',
            'currentDate',
            'view'
        ));
    }

    // Helper Methods
    private function getTimeSlots($start = 8, $end = 22)
    {
        $timeSlots = [];
        for ($hour = $start; $hour <= $end; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }
        return $timeSlots;
    }

    private function getDayThai($englishDay)
    {
        $days = [
            'Sunday' => 'อา.',
            'Monday' => 'จ.',
            'Tuesday' => 'อ.',
            'Wednesday' => 'พ.',
            'Thursday' => 'พฤ.',
            'Friday' => 'ศ.',
            'Saturday' => 'ส.',
        ];

        return $days[$englishDay] ?? $englishDay;
    }

    public function getBookingDetails($id)
    {
        $booking = Booking::where('bookings.id', $id)
            ->select('bookings.*', 'status.status_name', 'rooms.room_name', 'buildings.building_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('buildings', 'bookings.building_id', '=', 'buildings.id')
            ->leftJoin('rooms', function ($join) {
                $join->on('bookings.building_id', '=', 'rooms.building_id')
                    ->on('bookings.room_id', '=', 'rooms.room_id');
            })
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->first();

        if (! $booking) {
            return response()->json(['error' => 'ไม่พบข้อมูลการจอง'], 404);
        }

        // Add status color from config
        $booking->statusColor = config('status.colors.' . $booking->status_id, '#607D8B');

        // Get booking history
        $history = DB::table('booking_histories')
            ->where('booking_id', $id)
            ->select('booking_histories.*', 'status.status_name')
            ->leftJoin('status', 'booking_histories.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'booking_histories.changed_by', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, "ระบบ") as changed_by_name')
            ->orderBy('changed_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->statusColor = config('status.colors.' . $item->status_id, '#607D8B');
                return $item;
            });

        $booking->history = $history;

        return response()->json($booking);
    }
}
