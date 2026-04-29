<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // جلب البيانات الحقيقية من الـ database
        $activeWorkers = Worker::where('is_active', true)->count();
        $activeCompanies = Company::where('is_active', true)->count();

        return view('auth.login', [
            'activeWorkers' => $activeWorkers,
            'activeCompanies' => $activeCompanies,
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'رقم الهاتف أو البريد الإلكتروني مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $login = $validated['login'];
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$fieldType => $login, 'password' => $validated['password']])) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('contractor.dashboard');
        }

        return back()->withErrors([
            'login' => 'بيانات الدخول غير صحيحة',
        ])->onlyInput('login');
    }

    public function submitRegistrationRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'company_name' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'company_name.required' => 'اسم الشركة مطلوب',
        ]);

        // Save registration request
        \App\Models\RegistrationRequest::create($validated);

        return redirect()->back()->with('success', 'تم استقبال طلبك! سيتواصل معك فريق الدعم قريباً.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Verify if a phone number exists in the database.
     */
    public function verifyPhone(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $phone = $request->input('phone');

        $user = \App\Models\User::where('phone', $phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الهاتف غير مسجل في النظام',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'name' => $user->name,
            'message' => 'تم التحقق من الرقم بنجاح',
        ]);
    }

    /**
     * Reset user password after phone verification.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.min' => 'كلمة المرور يجب أن تكون ٦ أحرف على الأقل',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
        ]);

        $user = \App\Models\User::where('phone', $request->input('phone'))->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الهاتف غير مسجل في النظام',
            ], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تعيين كلمة المرور الجديدة بنجاح! سيتم توجيهك لتسجيل الدخول...',
        ]);
    }
}
