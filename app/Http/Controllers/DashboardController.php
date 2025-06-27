<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;

class DashboardController extends Controller
{


    public function index()
    {
        $user = Auth::user();

        // ถ้าเป็น Sub-admin ให้กรองข้อมูลเฉพาะอาคารที่ตัวเองดูแล
        if ($user->hasRole('sub-admin')) {
            $buildingIds = $user->buildings->pluck('id'); // อาคารที่ดูแล

            $recentBookings = Booking::whereIn('building_id', $buildingIds)
                ->orderBy('created_at', 'desc')->take(10)->get();

            $monthlyBookings = Booking::whereIn('building_id', $buildingIds)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->count();

            $weeklyStats = Booking::selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as total')
                ->whereIn('building_id', $buildingIds)
                ->whereBetween('created_at', [now()->subWeeks(12), now()])
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get();

            $totalRooms = \App\Models\Room::whereIn('building_id', $buildingIds)->count();
            $totalBuildings = count($buildingIds); // จำนวนอาคารที่ผู้ใช้ดูแล
            $totalUsers = 1; // หรือเฉพาะผู้ใช้ในอาคารที่ดูแลถ้าต้องการ
            $totalBookings = Booking::whereIn('building_id', $buildingIds)->count();
        } else {
            // กรณี Admin เห็นทุกอย่าง
            $recentBookings = Booking::orderBy('created_at', 'desc')->take(10)->get();
            $monthlyBookings = Booking::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->count();

            $weeklyStats = Booking::selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as total')
                ->whereBetween('created_at', [now()->subWeeks(12), now()])
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get();

            $totalRooms = \App\Models\Room::count();
            $totalUsers = \App\Models\User::count();
            $totalBookings = \App\Models\Booking::count();
            $totalBuildings = \App\Models\Building::count(); // จำนวนอาคารทั้งหมด
        }

        return view('dashboard.dashboard', compact(
            'recentBookings',
            'monthlyBookings',
            'weeklyStats',
            'totalRooms',
            'totalUsers',
            'totalBookings',
            'totalBuildings'
        ));
    }

    public function showIndex()
    {

        // ดึงจำนวนห้องทั้งหมดจากฐานข้อมูล
        $user = Auth::user();
        $myBookings = collect(); // ✅ ป้องกัน error
        $totalmyBookings = 0;
        $totalbuildingBookings = 0;

        if ($user->hasRole('sub-admin')) {
            $buildingIds = $user->buildings->pluck('id');

            $totalRooms = \App\Models\Room::whereIn('building_id', $buildingIds)->count();
            $totalbuildingBookings = Booking::whereIn('building_id', $buildingIds)->count();
            $totalBookings = \App\Models\Booking::count();
            $totalBuildings = count($buildingIds);
            $totalUsers = 1; // หรือกรองเฉพาะ user ที่อยู่ในอาคารนั้น

        } else {
            $totalRooms = \App\Models\Room::count();
            $totalBookings = \App\Models\Booking::count();
            $totalBuildings = \App\Models\Building::count();
            $totalUsers = \App\Models\User::count();
            $totalBuildings = \App\Models\Building::count(); // จำนวนอาคารทั้งหมด
            $myBookings = Booking::with(['room', 'room.building', 'status'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            $totalmyBookings = Booking::where('user_id', Auth::id())->count();
        }
        foreach ($myBookings as $booking) {
            if ($booking->room) {
                logger("Booking ID {$booking->id} has room with image: " . $booking->room->image);
            } else {
                logger("Booking ID {$booking->id} has no room.");
            }
        }
        // ดึง top room ids
        $topRoomIds = BookingHistory::select('room_id')
            ->where('status_id', 6)
            ->groupBy('room_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->pluck('room_id');
        if ($topRoomIds->isNotEmpty()) {
            // ดึงห้องแนะนำ (ที่อยู่ใน room_id เหล่านั้น)
            $featuredRooms = Room::with('building')
                ->whereIn('room_id', $topRoomIds)
                ->orderByRaw("FIELD(room_id, " . $topRoomIds->implode(',') . ")")
                ->get();
        } else {
            // fallback ถ้าไม่มีการจอง
            $featuredRooms = Room::with('building')->latest()->limit(10)->get();
        }

        return view('index', compact('totalRooms', 'totalUsers', 'totalBookings', 'totalBuildings', 'myBookings', 'totalmyBookings', 'featuredRooms','totalbuildingBookings'));
    }
    public function __construct()
    {
        $this->middleware('auth');
    }
}
