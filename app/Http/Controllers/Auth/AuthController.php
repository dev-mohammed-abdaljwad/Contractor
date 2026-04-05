<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ], [
            'phone.required' => 'رقم الهاتف مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('contractor.dashboard');
        }

        return back()->withErrors([
            'phone' => 'بيانات الدخول غير صحيحة',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
