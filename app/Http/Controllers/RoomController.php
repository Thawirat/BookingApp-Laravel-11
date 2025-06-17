<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\RoomType;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['building', 'status'])->get();

        return view('rooms.index', compact('rooms'));
    }

    public function byType($type)
    {
        $rooms = Room::with(['building', 'status'])
            ->where('room_type', $type)
            ->get();

        $roomType = \App\Models\RoomType::find($type);
        $title = $roomType ? "ประเภทห้อง: {$roomType->name}" : "ประเภทห้อง";

        return view('rooms.filtered', compact('rooms', 'title'));
    }
    public function byBuilding($building_id)
    {
        $building = Building::findOrFail($building_id);
        $rooms = Room::with(['building', 'status'])
            ->where('building_id', $building_id)
            ->get();
        $title = "ห้องในอาคาร: {$building->building_name}";

        return view('rooms.filtered', compact('rooms', 'title'));
    }

    public function popular()
    {
        // In a real application, you might sort by booking count or ratings
        // Here we're just showing all rooms as an example
        $rooms = Room::with(['building', 'status'])->get();
        $title = 'ห้องยอดนิยม';

        return view('rooms.filtered', compact('rooms', 'title'));
    }
}
