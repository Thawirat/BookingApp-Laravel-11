<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

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

        if ($user->hasRole('sub-admin')) {
            $buildingIds = $user->buildings->pluck('id');

            $totalRooms = \App\Models\Room::whereIn('building_id', $buildingIds)->count();
            $totalBookings = Booking::whereIn('building_id', $buildingIds)->count();
            $totalBuildings = count($buildingIds);
            $totalUsers = 1; // หรือกรองเฉพาะ user ที่อยู่ในอาคารนั้น

        } else {
            $totalRooms = \App\Models\Room::count();
            $totalBookings = \App\Models\Booking::count();
            $totalBuildings = \App\Models\Building::count();
            $totalUsers = \App\Models\User::count();
            $totalBuildings = \App\Models\Building::count(); // จำนวนอาคารทั้งหมด
        }

        return view('index', compact('totalRooms', 'totalUsers', 'totalBookings', 'totalBuildings'));
    }
    public function __construct()
    {
        $this->middleware('auth');
    }
}
