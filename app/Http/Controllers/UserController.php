<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    // app/Http/Controllers/UserController.php
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        $user = Auth::user();
        $user->update($request->only('email', 'phone', 'address', 'dob'));

        return back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านเดิมไม่ถูกต้อง']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
    public function updateAll(Request $request)
    {
        $user = Auth::user();

        // อัปเดตโปรไฟล์
        $user->update($request->only(['name', 'email', 'phone', 'address', 'dob']));

        if ($request->hasFile('avatar')) {
            // Upload & save avatar logic here
        }

        // ถ้ามีการกรอกรหัสผ่านเก่า
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'รหัสผ่านเดิมไม่ถูกต้อง']);
            }

            if ($request->new_password !== $request->confirm_password) {
                return back()->withErrors(['confirm_password' => 'รหัสผ่านใหม่ไม่ตรงกัน']);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        return back()->with('success', 'อัปเดตโปรไฟล์เรียบร้อยแล้ว');
    }
}
