<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdatePreferencesRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $preferences = $user->preferences;

        return view('admin.settings.show', compact('user', 'preferences'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        
        $user->update([
            'name' => trim($request->first_name . ' ' . $request->last_name),
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.settings.show')
            ->with('success', 'تم تحديث البيانات الشخصية بنجاح ✓');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('admin.settings.show')
            ->with('success', 'تم تحديث كلمة السر بنجاح ✓');
    }

    public function updatePreferences(UpdatePreferencesRequest $request)
    {
        $user = auth()->user();
        
        $user->preferences()->updateOrCreate(
            ['user_id' => $user->id],
            $request->validated()
        );

        return redirect()->route('admin.settings.show')
            ->with('success', 'تم حفظ الإعدادات بنجاح ✓');
    }
}
