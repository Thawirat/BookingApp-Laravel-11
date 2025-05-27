<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;

class RoomTypeController extends Controller
{
    /**
     * แสดงรายการประเภทห้องทั้งหมด
     */
    public function index()
    {
        $roomTypes = RoomType::orderBy('name')->get();
        return view('dashboard.room_type', compact('roomTypes'));
    }

    /**
     * แสดงฟอร์มเพิ่มประเภทห้อง (ไม่ได้ใช้เพราะใช้ Modal)
     */
    public function create()
    {
        return redirect()->route('room-types.index');
    }

    /**
     * บันทึกข้อมูลประเภทห้องใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:room_types,name',
        ]);

        RoomType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('room-types.index')->with('success', 'เพิ่มประเภทห้องเรียบร้อยแล้ว');
    }

    /**
     * แสดงฟอร์มแก้ไข (ไม่ได้ใช้เพราะใช้ Modal)
     */
    public function edit(RoomType $roomType)
    {
        return redirect()->route('room-types.index');
    }

    /**
     * อัปเดตข้อมูลประเภทห้อง
     */
    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|unique:room_types,name,' . $roomType->id,
        ]);

        $roomType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('room-types.index')->with('success', 'แก้ไขประเภทห้องเรียบร้อยแล้ว');
    }

    /**
     * ลบประเภทห้อง
     */
    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return redirect()->route('room-types.index')->with('success', 'ลบประเภทห้องเรียบร้อยแล้ว');
    }
}
