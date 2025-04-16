<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Building;
use Illuminate\Support\Facades\Hash;

class ManageUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        $users = $query->paginate(50);

        // Get user statistics
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $regularUserCount = $totalUsers - $adminCount;

        return view('dashboard.manage_users', compact('users', 'totalUsers', 'adminCount', 'regularUserCount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,sub-admin,user',
            'password' => 'nullable|min:8',
            'buildings' => 'array'
        ]);

        $user = User::findOrFail($id);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update building assignments
        if ($request->has('buildings')) {
            $user->buildings()->sync($request->buildings);
        } else {
            $user->buildings()->detach();
        }

        return redirect()->route('manage_users.index')
            ->with('success', 'User updated successfully');
    }

    public function getUserBuildings($id)
    {
        $user = User::findOrFail($id);
        $userBuildingIds = $user->buildings->pluck('id')->toArray();

        $buildings = Building::all()->map(function($building) use ($userBuildingIds) {
            return [
                'id' => $building->id,
                'building_name' => $building->building_name,
                'assigned' => in_array($building->id, $userBuildingIds)
            ];
        });

        return response()->json(['buildings' => $buildings]);
    }
}

