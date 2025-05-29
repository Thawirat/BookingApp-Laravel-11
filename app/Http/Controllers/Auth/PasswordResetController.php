<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOtpResetMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Mail\PasswordResetSuccessMail;


class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'ไม่พบบัญชีผู้ใช้นี้']);
        }

        // สร้าง OTP
        $otp = rand(100000, 999999);

        // เก็บลงฐานข้อมูล (หรือ session ก็ได้)
        DB::table('password_otps')->updateOrInsert(
            ['email' => $user->email],
            [
                'otp' => $otp,
                'created_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        // ส่งอีเมล
        Mail::to($user->email)->send(new SendOtpResetMail($user, $otp));

        // ส่งไปหน้า verify OTP
        session(['reset_email' => $user->email]);
        return redirect()->route('password.otp')->with('status', 'เราได้ส่งรหัสยืนยันให้ทางอีเมลของคุณ');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // ตรวจสอบว่า OTP ได้รับการยืนยันแล้ว
        if (!session('otp_verified') || session('reset_email') !== $request->email) {
            return redirect()->route('password.request')->withErrors(['email' => 'ไม่อนุญาตให้เปลี่ยนรหัสผ่าน']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'ไม่พบบัญชีผู้ใช้นี้']);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        // ส่งอีเมลแจ้งเตือน
        Mail::to($user->email)->send(new PasswordResetSuccessMail($user));
        // เคลียร์ session
        Session::forget(['otp_verified', 'reset_email']);

        return redirect()->route('login')->with('status', 'รีเซ็ตรหัสผ่านเรียบร้อยแล้ว');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // สร้าง OTP 6 หลัก
        $otp = rand(100000, 999999);

        // เก็บ OTP และอีเมลไว้ใน session ชั่วคราว
        Session::put('otp', $otp);
        Session::put('otp_email', $user->email);

        // ส่ง OTP ไปยังอีเมล
        Mail::to($user->email)->send(new SendOtpResetMail($user, $otp));

        return redirect()->route('password.otp')->with('status', 'ส่งรหัส OTP ไปยังอีเมลของคุณแล้ว');
    }
    public function showOtpForm()
    {
        return view('verify_otp'); // คุณต้องสร้าง view ชื่อ verify-otp.blade.php
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $email = session('reset_email');

        $otpRecord = DB::table('password_otps')
            ->where('email', $email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'รหัส OTP ไม่ถูกต้องหรือหมดอายุ']);
        }

        // OTP ถูกต้อง → ให้ไปหน้ารีเซ็ตรหัสผ่าน
        session(['otp_verified' => true]);

        return redirect()->route('password.reset');
    }
    public function showResetPasswordForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('reset-password'); // ต้องสร้าง view นี้ด้วย
    }
}
