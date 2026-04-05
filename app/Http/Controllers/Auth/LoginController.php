<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request
     */
    public function login(LoginRequest $request)
    {
        // Find user by phone
        $user = User::where('phone', $request->phone)->first();

        // Verify password
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => 'رقم الهاتف غير مسجل في النظام أو كلمة المرور غير صحيحة.',
            ]);
        }

        // Login the user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Redirect based on role
        return match($user->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'contractor' => redirect()->route('contractor.dashboard'),
            default      => redirect('/'),
        };
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
