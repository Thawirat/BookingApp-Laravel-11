<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ManageBuildingsController extends Controller
{
    // แสดงรายการอาคาร
    public function index(Request $request)
    {
        $query = Building::query();

        // If sub-admin, only show assigned buildings
        if (Auth::user()->role === 'sub-admin') {
            $query->whereHas('users', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // Apply search if provided
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('building_name', 'like', '%' . $request->search . '%')
                    ->orWhere('citizen_save', 'like', '%' . $request->search . '%');
            });
        }

        // >>> ต้อง clone ก่อน paginate
        $buildingsQueryClone = clone $query;
        $buildings = $query->paginate(15);
        $totalBuildings = $buildingsQueryClone->count();

        return view('dashboard.manage_buildings', compact('buildings', 'totalBuildings'));
    }

    // เพิ่มอาคาร
    public function store(Request $request)
    {
        // Verify permission
        if (Auth::user()->role === 'sub-admin') {
            abort(403, 'Only administrators can create new buildings.');
        }

        // เล่ม validation `building_name` และ `citizen_save`
        $request->validate([
            'building_name' => 'required|string|max:255',
            'citizen_save' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Log the request data for debugging
        Log::info('Building Store Request Data:', $request->all());

        // ส่วนของการเพิ่มข้อมูล
        $building = new Building;
        $building->building_name = $request->building_name;
        $building->citizen_save = $request->citizen_save;
        $building->date_save = now();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('buildings', 'public');
            $building->image = $imagePath;
        }

        $building->save();

        return redirect()->route('manage_rooms.index')->with('success', 'Building added successfully.');
    }

    public function update(Request $request, $id)
    {
        $building = Building::findOrFail($id);

        // Check if sub-admin has permission for this building
        if (Auth::user()->role === 'sub-admin' && ! $building->users->contains(Auth::id())) {
            abort(403, 'You do not have permission to edit this building.');
        }

        $building->building_name = $request->building_name;
        $building->citizen_save = $request->citizen_save;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($building->image && Storage::disk('public')->exists($building->image)) {
                Storage::disk('public')->delete($building->image);
            }

            $imagePath = $request->file('image')->store('buildings', 'public');
            $building->image = $imagePath;
        }

        $building->save();

        return redirect()->route('manage_rooms.index')->with('success', 'Building updated successfully.');
    }

    public function destroy($id)
    {
        $building = Building::findOrFail($id);

        // Check if sub-admin has permission for this building
        if (Auth::user()->role === 'sub-admin' && ! $building->users->contains(Auth::id())) {
            abort(403, 'You do not have permission to delete this building.');
        }

        // 2. ลบข้อมูล
        $building->delete();

        // 3. Redirect พร้อมข้อความแจ้งเตือน
        return redirect()->back()
            ->with('success', 'ลบอาคาร: สำเร็จ');
    }
}
