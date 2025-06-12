<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::orderBy('name')->get();
        return view('dashboard.equipments', compact('equipments'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'remaining' => 'nullable|integer|min:0',
        ]);

        // ถ้าไม่กรอก remaining ให้เท่ากับ quantity
        if (!isset($validated['remaining'])) {
            $validated['remaining'] = $validated['quantity'];
        }

        Equipment::create($validated);

        return redirect()->route('equipments.index')->with('success', 'เพิ่มอุปกรณ์เรียบร้อยแล้ว');
    }
    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'remaining' => 'required|integer|min:0|max:' . $request->quantity,
        ]);
        $equipment->update([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'remaining' => $request->remaining,
        ]);
        return redirect()->route('equipments.index')->with('success', 'อัปเดตข้อมูลอุปกรณ์เรียบร้อยแล้ว');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('equipments.index')->with('success', 'ลบอุปกรณ์เรียบร้อยแล้ว');
    }
}
