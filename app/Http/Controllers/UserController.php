<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // app/Http/Controllers/UserController.php
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'required|email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'user_type' => 'nullable|in:internal,external',
            'position' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email', 'phone', 'address', 'phone_number', 'address', 'user_type', 'position', 'department'));

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
        $user->update($request->only(['name', 'email', 'phone', 'address', 'phone_number', 'address', 'user_type', 'position', 'department']));

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

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldStatus = $user->status;
        $newStatus = $request->input('status');

        $user->status = $newStatus;
        $user->save();

        // ถ้าเปลี่ยนจาก pending เป็น active → ส่งเมลแจ้งว่าอนุมัติ
        if ($oldStatus !== 'active' && $newStatus === 'active') {
            Mail::to($user->email)->send(new UserApprovedMail($user));
        }

        // ถ้าเปลี่ยนจาก pending เป็น rejected → ส่งเมลแจ้งว่าไม่อนุมัติ
        if ($oldStatus !== 'rejected' && $newStatus === 'rejected') {
            Mail::to($user->email)->send(new UserRejectedMail($user));
        }

        return back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }
}
