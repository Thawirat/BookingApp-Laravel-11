<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (auth()->user()->status !== 'active') {
                $status = auth()->user()->status;
                Auth::logout();

                return back()->withErrors([
                    'email' => $status === 'pending'
                        ? 'บัญชีของคุณยังรอการอนุมัติจากแอดมิน'
                        : 'บัญชีของคุณถูกปฏิเสธ โปรดติดต่อแอดมิน',
                ]);
            }

            return redirect()->intended('/login'); // หรือ path ที่ต้องการ
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect('/dashboard');
        } elseif ($user->isSubAdmin()) {
            return redirect('/manage-buildings');
        }

        return redirect('/');
    }
}
