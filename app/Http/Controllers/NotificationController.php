<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            // ✅ Admin เห็นทุกการแจ้งเตือน
            $notifications = DB::table('notifications')
                ->latest()
                ->get();
        } elseif ($user->role === 'subadmin') {
            // ✅ SubAdmin เห็นเฉพาะ booking ที่อยู่ใน building_id ที่ตัวเองดูแล
            $notifications = DB::table('notifications')
                ->where('data->building_id', $user->building_id)
                ->latest()
                ->get();
        } else {
            // ✅ User เห็นเฉพาะ booking ของตัวเอง
            $notifications = DB::table('notifications')
                ->where('data->user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json($notifications);
    }

    public function clear(Request $request)
    {
        $user = $request->user();

        // ลบแจ้งเตือนทั้งหมดของ user
        $user->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'ล้างการแจ้งเตือนเรียบร้อยแล้ว'
        ]);
    }
}
