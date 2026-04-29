<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\Services\PasswordResetService;
use App\Exceptions\PasswordResetException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(
        protected PasswordResetService $service
    ) {}

    /**
     * Show the forgot password form.
     */
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Generate and send the OTP code.
     */
    public function sendCode(ForgotPasswordRequest $request): RedirectResponse
    {
        try {
            $this->service->sendCode($request->email);
            
            return redirect()->route('password.verify-form')
                ->with('reset_email', $request->email)
                ->with('status', 'تم إرسال كود التحقق إلى بريدك الإلكتروني');
        } catch (PasswordResetException $e) {
            // Anti-enumeration: if email not found, still act as if sent (or follow specific security rules)
            // But the request says: "Always say 'لو الإيميل مسجل هيوصلك كود' regardless"
            return redirect()->route('password.verify-form')
                ->with('reset_email', $request->email)
                ->with('status', 'لو البريد الإلكتروني مسجل، هيوصلك كود التحقق');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'حدث خطأ — حاول مرة أخرى']);
        }
    }

    /**
     * Show the code verification form.
     */
    public function showVerifyForm(): View
    {
        $email = session('reset_email');
        
        if (!$email) {
            abort(403, 'غير مصرح بالدخول');
        }

        return view('auth.verify-code', [
            'email' => $email
        ]);
    }

    /**
     * Verify the entered OTP code.
     */
    public function verifyCode(VerifyCodeRequest $request): RedirectResponse
    {
        try {
            $token = $this->service->verifyCode($request->email, $request->code);

            return redirect()->route('password.reset-form')
                ->with('reset_token', $token);
        } catch (PasswordResetException $e) {
            return back()->withErrors(['code' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(): View
    {
        $token = session('reset_token');

        if (!$token) {
            abort(403, 'انتهت الجلسة — ابدأ من جديد');
        }

        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    /**
     * Update the password.
     */
    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        try {
            $this->service->resetPassword($request->token, $request->password);

            return redirect()->route('login')
                ->with('success', 'تم تغيير كلمة السر بنجاح. يمكنك تسجيل الدخول الآن');
        } catch (PasswordResetException $e) {
            return back()->withErrors(['token' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['password' => 'حدث خطأ — حاول مرة أخرى']);
        }
    }
}
