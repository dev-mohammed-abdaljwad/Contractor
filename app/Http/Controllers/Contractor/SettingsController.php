<?php

namespace App\Http\Controllers\Contractor;

use App\Exceptions\SettingsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ChangePasswordRequest;
use App\Http\Requests\Settings\TerminateSessionsRequest;
use App\Http\Requests\Settings\UpdateNotificationsRequest;
use App\Http\Requests\Settings\UpdateProfileRequest;
use App\Http\Requests\Settings\UpdateSystemPreferencesRequest;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        private SettingsService $settingsService
    ) {}

    /**
     * Display the settings page.
     */
    public function index(): View
    {
        $user = auth()->user()->load('preferences');
        $preferences = $user->preferences;

        return view('contractor.settings.show', compact('user', 'preferences'));
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        try {
            $this->settingsService->updatePersonalInfo($request->validated(), auth()->user());
            return back()->with('success', 'تم تحديث البيانات الشخصية بنجاح');
        } catch (SettingsException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Change user password.
     */
    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        try {
            $this->settingsService->changePassword($request->validated(), auth()->user());
            return back()->with('success', 'تم تغيير كلمة السر بنجاح');
        } catch (SettingsException $e) {
            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(UpdateNotificationsRequest $request): RedirectResponse
    {
        try {
            $this->settingsService->updateNotifications($request->validated(), auth()->user());
            return back()->with('success', 'تم حفظ إعدادات الإشعارات بنجاح');
        } catch (SettingsException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update system preferences.
     */
    public function updateSystemPreferences(UpdateSystemPreferencesRequest $request): RedirectResponse
    {
        try {
            $this->settingsService->updateSystemPreferences($request->validated(), auth()->user());
            return back()->with('success', 'تم حفظ إعدادات النظام بنجاح');
        } catch (SettingsException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Terminate all other user sessions.
     */
    public function terminateSessions(TerminateSessionsRequest $request): RedirectResponse
    {
        try {
            $this->settingsService->terminateOtherSessions($request->validated(), auth()->user());
            return back()->with('success', 'تم تسجيل الخروج من كل الأجهزة بنجاح');
        } catch (SettingsException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
