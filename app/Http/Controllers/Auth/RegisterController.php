<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserRegisteredNotification;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // ✅ สร้าง user ใหม่
        $user = $this->create($request->all());

        // ✅ ส่ง Email แจ้งเตือนหา admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new UserRegisteredMail($user));
        }

        // ✅ ส่ง Notification ลง database สำหรับ admin
        Notification::send($admins, new NewUserRegisteredNotification($user));

        return redirect()->route('login')->with('success', 'ลงทะเบียนสำเร็จ กรุณาเข้าสู่ระบบ');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@snru\.ac\.th$/i',
            ],
            'department' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:12'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.regex' => 'อีเมลต้องลงท้ายด้วย @snru.ac.th เท่านั้น',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'department'   => $data['department'],
            'phone_number' => $data['phone_number'],
            'password'     => Hash::make($data['password']),
        ]);
    }
}
