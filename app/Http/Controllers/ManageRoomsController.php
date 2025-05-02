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
        $rooms = Room::with(['building', 'status'])
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
            // Log incoming request data
            Log::info('Room creation request data:', $request->all());
            $roomTypeName = null;
            if ($request->filled('room_type')) {
                $roomType = \App\Models\RoomType::find($request->input('room_type'));
                $roomTypeName = $roomType?->name;
            } elseif ($request->filled('custom_room_type')) {
                $roomTypeName = $request->input('custom_room_type');
            }
            // Validate the request
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'class' => 'required|string|max:255',
                'room_details' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'service_rates' => 'required|numeric|min:0',
                'room_type' => 'required|string|max:255',
                'status_id' => 'required|exists:status,status_id',
            ]);

            // Log successful validation
            Log::info('Room data validated successfully:', $validated);

            // Create new room
            $room = new Room();
            $room->building_id = $validated['building_id'];
            $room->room_id = $this->generateRoomId($validated['building_id']);
            $room->room_name = $validated['room_name'];
            $room->capacity = $validated['capacity'];
            $room->class = $validated['class'];
            $room->room_type = $roomTypeName ?? 'ไม่ระบุ';
            $room->room_details = $validated['room_details'];
            $room->service_rates = $validated['service_rates'];
            $room->status_id = $validated['status_id'];


            // Handle image upload with enhanced validation and debugging
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');

                    // Validate file type and size
                    if (!$image->isValid()) {
                        Log::error('Invalid file upload attempt', [
                            'name' => $image->getClientOriginalName(),
                            'error' => $image->getErrorMessage()
                        ]);
                        throw new \Exception('Invalid file upload: ' . $image->getErrorMessage());
                    }

                    // Verify file extension
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $extension = strtolower($image->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        throw new \Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions));
                    }

                    // Generate unique filename with original extension
                    $imageName = time() . '_' . uniqid() . '.' . $extension;

                    // Verify storage directory exists
                    $storagePath = storage_path('app/public/room_images');
                    if (!is_dir($storagePath)) {
                        if (!mkdir($storagePath, 0755, true)) {
                            throw new \Exception('Failed to create storage directory');
                        }
                    }

                    // Store image and handle errors
                    $imagePath = $image->storeAs('public/room_images', $imageName);
                    if (!$imagePath) {
                        throw new \Exception('Failed to store image');
                    }

                    $room->image = 'room_images/' . $imageName;
                    Log::info('Room image uploaded successfully:', [
                        'path' => $imagePath,
                        'size' => $image->getSize(),
                        'mime' => $image->getMimeType(),
                        'storage' => $storagePath
                    ]);
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage(), [
                        'file' => $image->getClientOriginalName(),
                        'error' => $e->getTraceAsString()
                    ]);
                    return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
                }
            }


            // Save the room
            $room->save();
            Log::info('Room created successfully:', $room->toArray());

            return redirect()->route('manage_rooms.show', $room->building_id)->with('success', 'Room created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to create room. Please try again.');
        }
    }

    public function update(Request $request, $roomId)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'status_id' => 'required|exists:status,status_id',
                'class' => 'required|string|max:255',
                'room_details' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'service_rates' => 'required|numeric|min:0',
                'room_type' => 'required|exists:room_types,id',
            ]);

            // Find the room to be updated
            $room = Room::findOrFail($roomId);

            // Handle room name check for duplicates
            $existing = Room::where('building_id', $validated['building_id'])
                ->where('room_name', $validated['room_name'])
                ->where('id', '!=', $roomId)
                ->first();

            if ($existing) {
                return redirect()->back()->with('error', 'ห้องนี้มีอยู่แล้วในอาคารนี้');
            }

            // Update only the fields that are provided
            if ($request->has('room_name')) {
                $room->room_name = $validated['room_name'];
            }
            if ($request->has('capacity')) {
                $room->capacity = $validated['capacity'];
            }
            if ($request->has('room_type')) {
                $room->room_type = $validated['room_type'];
            }
            if ($request->has('room_details')) {
                $room->room_details = $validated['room_details'];
            }
            if ($request->has('service_rates')) {
                $room->service_rates = $validated['service_rates'];
            }
            if ($request->has('status_id')) {
                $room->status_id = $validated['status_id'];
            }
            if ($request->has('class')) {
                $room->class = $validated['class'];
            }

            // Handle image update if exists
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');

                    // Validate file type and size
                    if (!$image->isValid()) {
                        Log::error('Invalid file upload attempt', [
                            'name' => $image->getClientOriginalName(),
                            'error' => $image->getErrorMessage(),
                        ]);
                        throw new \Exception('Invalid file upload: ' . $image->getErrorMessage());
                    }

                    // Verify file extension
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $extension = strtolower($image->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        throw new \Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions));
                    }

                    // Delete old image if exists
                    if ($room->image && Storage::exists('public/' . $room->image)) {
                        if (!Storage::delete('public/' . $room->image)) {
                            Log::warning('Failed to delete old image', ['path' => $room->image]);
                        }
                    }

                    // Generate unique filename with original extension
                    $imageName = time() . '_' . uniqid() . '.' . $extension;

                    // Verify storage directory exists
                    $storagePath = storage_path('app/public/room_images');
                    if (!is_dir($storagePath)) {
                        if (!mkdir($storagePath, 0755, true)) {
                            throw new \Exception('Failed to create storage directory');
                        }
                    }

                    // Store new image
                    $imagePath = $image->storeAs('public/room_images', $imageName);
                    if (!$imagePath) {
                        throw new \Exception('Failed to store image');
                    }

                    $room->image = 'room_images/' . $imageName;
                    Log::info('Room image updated successfully:', [
                        'path' => $imagePath,
                        'size' => $image->getSize(),
                        'mime' => $image->getMimeType(),
                        'storage' => $storagePath,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Image update failed: ' . $e->getMessage(), [
                        'file' => $image->getClientOriginalName(),
                        'error' => $e->getTraceAsString(),
                    ]);
                    return redirect()->back()->with('error', 'Failed to update image: ' . $e->getMessage());
                }
            }

            // Save the updated room details
            $room->save();

            Log::info('Room updated successfully:', $room->toArray());

            return redirect()->route('manage_rooms.show', $room->building_id)->with('success', 'Room updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

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

    public function subAdminRooms(Request $request)
    {
        $user = Auth::user();
        $buildings = Building::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $query = Room::query();

        // If building ID is provided, filter rooms by that building
        if ($request->building) {
            $query->where('building_id', $request->building);
        }

        $rooms = $query->whereIn('building_id', $buildings->pluck('id'))
            ->with(['building', 'status'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('room_name', 'like', '%' . $request->search . '%');
            })
            ->paginate(12);

        return view('dashboard.sub_admin_rooms', compact('rooms', 'buildings'));
    }
}
