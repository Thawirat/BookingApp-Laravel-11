<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\RoomEquipment;


class ManageRoomsController extends Controller
{
    public function index()
    {
        $query = Building::query();

        if (Auth::user()->role === 'sub-admin') {
            $query->whereHas('users', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if (request('search')) {
            $query->where('building_name', 'like', '%' . request('search') . '%');
        }

        $query->withCount('rooms');

        $buildingsQueryClone = clone $query;
        $buildings = $query->paginate(12);

        $totalBuildings = $buildingsQueryClone->count();
        $totalRooms = $buildingsQueryClone->get()->sum('rooms_count');

        // >>>> ตรงนี้แก้ใหม่
        $buildingIds = $buildingsQueryClone->pluck('id');
        $rooms = Room::whereIn('building_id', $buildingIds)->get();

        $status = Status::all();
        $roomTypes = RoomType::all();

        return view('dashboard.manage_rooms', compact(
            'buildings',
            'rooms',
            'roomTypes',
            'status',
            'totalBuildings',
            'totalRooms'
        ));
    }

    public function showRooms($buildingId)
    {
        // Ensure building exists and get its ID
        $building = Building::findOrFail($buildingId);

        // Get rooms for this building with search and pagination
        // Get total counts for available and unavailable rooms
        $totalCount = Room::where('building_id', $building->id)->count();

        $availableCount = Room::where('building_id', $building->id)
            ->where('status_id', 2)
            ->count();

        $unavailableCount = Room::where('building_id', $building->id)
            ->where('status_id', 1)
            ->count();

        // Get paginated rooms
        $rooms = Room::with(['building', 'status', 'equipments'])
            ->where('building_id', $building->id)
            ->when(request('search'), function ($query) {
                $query->where('room_name', 'like', '%' . request('search') . '%');
            })
            ->paginate(12);

        $buildings = Building::all();
        $status = Status::all();
        $roomTypes = RoomType::all();

        return view('dashboard.rooms', compact(
            'rooms',
            'building',
            'buildings',
            'roomTypes',
            'status',
            'totalCount',
            'availableCount',
            'unavailableCount'
        ));
    }

    public function fetchRooms()
    {
        return Room::all();
    }

    private function generateRoomId($buildingId)
    {
        // Get the maximum room_id for this building
        $maxRoomId = Room::where('building_id', $buildingId)
            ->max('room_id');

        // Return next available room_id
        return $maxRoomId ? $maxRoomId + 1 : 1;
    }

    public function store(Request $request)
    {
        try {
            Log::info('Room creation request data:', $request->all());

            $request->merge([
                'status_id' => $request->input('status_id', 2)
            ]);

            // ✅ Validate input
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'class' => 'required|string|max:255',
                'room_details' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status_id' => 'required|exists:status,status_id',
                'room_type' => 'required',
                'room_type_other' => 'nullable|string|max:255',
            ]);

            Log::info('Room data validated successfully:', $validated);

            $room = new Room();
            $room->building_id = $validated['building_id'];
            $room->room_id = $this->generateRoomId($validated['building_id']);
            $room->room_name = $validated['room_name'];
            $room->capacity = $validated['capacity'];
            $room->class = $validated['class'];
            $room->room_details = $validated['room_details'] ?? '';
            $room->status_id = $validated['status_id'];

            // ✅ Handle room type
            if ($request->room_type === 'other') {
                $room->room_type = 'other';
                $room->room_type_other = $request->room_type_other;
            } else {
                $room->room_type = $request->room_type;
                $room->room_type_other = null;
            }

            // ✅ Handle image upload
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');
                    if (!$image->isValid()) {
                        throw new \Exception('Invalid file upload: ' . $image->getErrorMessage());
                    }

                    $extension = strtolower($image->getClientOriginalExtension());
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($extension, $allowed)) {
                        throw new \Exception("Invalid file type. Allowed types: " . implode(', ', $allowed));
                    }

                    $imageName = time() . '_' . uniqid() . '.' . $extension;
                    $path = $image->storeAs('/room_images', $imageName);
                    if (!$path) {
                        throw new \Exception('Failed to store image');
                    }

                    $room->image = 'room_images/' . $imageName;

                    Log::info('Room image uploaded successfully:', [
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime' => $image->getMimeType(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                    return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
                }
            }
            $room->save();

            if ($request->has('equipment_names')) {
                foreach ($request->equipment_names as $index => $name) {
                    RoomEquipment::create([
                        'room_id' => $room->room_id,
                        'building_id' => $room->building_id,
                        'name'        => $name ?: null,
                        'note'        => $request->equipment_notes[$index] ?? null,
                        'quantity'    => $request->equipment_quantities[$index] ?? null,
                    ]);
                }
            }

            Log::info('Room created successfully:', $room->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Room created successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error creating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create room. Please try again.',
            ], 500);
        }
    }

    public function update(Request $request, $roomId)
    {
        try {
            // Log the request for debugging
            Log::info('Update request received', ['room_id' => $roomId, 'data' => $request->all()]);

            // Validate input
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'status_id' => 'required|exists:status,status_id',
                'class' => 'required|string|max:255',
                'room_details' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'service_rates' => 'required|numeric|min:0',
                'room_type' => 'required',
                'custom_room_type' => 'nullable|string|max:255',
                'room_type_other' => 'nullable|string',  // กรณีเลือก "อื่นๆ"
            ]);

            // Find the room to be updated
            $room = Room::findOrFail($roomId);

            // Check for duplicate room name in the same building
            $existing = Room::where('building_id', $validated['building_id'])
                ->where('room_name', $validated['room_name'])
                ->where('room_id', '!=', $roomId)
                ->first();

            if ($existing) {
                $message = 'ห้องนี้มีอยู่แล้วในอาคารนี้';
                Log::warning($message, ['existing_room_id' => $existing->room_id]);

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 409);
                }
                return redirect()->back()->with('error', $message);
            }

            // Update room fields
            $room->building_id = $validated['building_id'];
            $room->room_name = $validated['room_name'];
            $room->capacity = $validated['capacity'];
            $room->class = $validated['class'];
            $room->room_details = $validated['room_details'];
            // $room->service_rates = $validated['service_rates'];
            $room->status_id = $validated['status_id'];

            // ถ้าเลือก "อื่นๆ"
            if ($request->room_type == 'other') {
                $room->room_type = 'other';
                $room->room_type_other = $request->room_type_other;  // เก็บข้อความที่ระบุเอง
            } else {
                $room->room_type = $request->room_type;
                $room->room_type_other = null;  // ล้างค่าหากไม่ใช่ "อื่นๆ"
            }

            // Image upload (if provided)
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');

                    if (!$image->isValid()) {
                        throw new \Exception('Invalid file upload: ' . $image->getErrorMessage());
                    }

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $extension = strtolower($image->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        throw new \Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions));
                    }

                    // Delete old image
                    if ($room->image && Storage::exists('public/' . $room->image)) {
                        Storage::delete('public/' . $room->image);
                    }

                    $imageName = time() . '_' . uniqid() . '.' . $extension;
                    $imagePath = $image->storeAs('/room_images', $imageName);
                    if (!$imagePath) {
                        throw new \Exception('Failed to store image');
                    }

                    $room->image = 'room_images/' . $imageName;

                    Log::info('Room image updated successfully', [
                        'path' => $imagePath,
                        'size' => $image->getSize(),
                        'mime' => $image->getMimeType(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Image update failed: ' . $e->getMessage(), [
                        'file' => $request->file('image')->getClientOriginalName(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return redirect()->back()->with('error', 'Failed to update image: ' . $e->getMessage());
                }
            }

            // Save room
            $room->save();
            // จัดการอุปกรณ์
            $existingEquipments = $room->equipments()->get()->keyBy('id');

            // เก็บ id ที่ส่งมาใน request
            $inputEquipmentIds = collect($request->equipments ?? [])->pluck('id')->filter()->all();

            // ลบอุปกรณ์ที่มีใน DB แต่ไม่อยู่ใน request (ถ้าต้องการลบ)
            $room->equipments()->whereNotIn('id', $inputEquipmentIds)->delete();
            $buildingId = $request->input('building_id');
            // Loop ผ่าน equipments ใน request
            foreach ($request->equipments ?? [] as $equipment) {
                if (!empty($equipment['name']) && !empty($equipment['quantity'])) {
                    if (!empty($equipment['id']) && $existingEquipments->has($equipment['id'])) {
                        $existingEquipment = $existingEquipments->get($equipment['id']);
                        $existingEquipment->update([
                            'name' => $equipment['name'] ?? null,
                            'quantity' => $equipment['quantity'] ?? null,
                            'note' => $equipment['note'] ?? null,

                        ]);
                    } else {
                        $room->equipments()->create([
                            'name' => $equipment['name'] ?? null,
                            'quantity' => $equipment['quantity'] ?? null,
                            'note' => $equipment['note'] ?? null,
                            'building_id' => $buildingId,
                        ]);
                    }
                }
            }
            Log::info('Room updated successfully', $room->toArray());

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('manage_rooms.show', $room->building_id)->with('success', 'Room updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดระหว่างอัปเดต'], 500);
            }

            return redirect()->back()->with('error', 'Failed to update room. Please try again.');
        }
    }
    public function destroy($room_id)
    {
        // ค้นหาห้องที่ต้องการลบ
        $room = Room::findOrFail($room_id);

        // ลบรูปภาพ (ถ้ามี)
        if ($room->image) {
            Storage::delete('public/' . $room->image);
        }

        // ลบห้อง
        $room->delete();

        // ส่งหน้าก่อนหน้าพร้อมข้อความสำเร็จ
        return redirect()->back()->with('success', 'ลบห้องสำเร็จ');
    }
    // public function subAdminRooms(Request $request)
    // {
    //     $user = Auth::user();
    //     $buildings = Building::whereHas('users', function ($query) use ($user) {
    //         $query->where('user_id', $user->id);
    //     })->get();

    //     $query = Room::query();

    //     // If building ID is provided, filter rooms by that building
    //     if ($request->building) {
    //         $query->where('building_id', $request->building);
    //     }

    //     $rooms = $query->whereIn('building_id', $buildings->pluck('id'))
    //         ->with(['building', 'status'])
    //         ->when($request->search, function ($query) use ($request) {
    //             $query->where('room_name', 'like', '%' . $request->search . '%');
    //         })
    //         ->paginate(12);

    //     return view('dashboard.sub_admin_rooms', compact('rooms', 'buildings'));
    // }
}
