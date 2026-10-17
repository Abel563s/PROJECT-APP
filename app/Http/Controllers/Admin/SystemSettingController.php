<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\SystemSetting::all();
        $user = auth()->user();
        return view('admin.settings.index', compact('settings', 'user'));
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            \App\Models\SystemSetting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'System settings synchronized.');
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password,password_confirmation', 'current_password'],
            'password' => ['nullable', 'required_with:current_password,password_confirmation', 'confirmed', 'min:8'],
            'password_confirmation' => ['nullable', 'required_with:current_password,password'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            if ($user->employee) {
                $nameParts = explode(' ', $user->name, 2);
                $user->employee->update([
                    'first_name' => $nameParts[0],
                    'last_name' => $nameParts[1] ?? '',
                    'email' => $user->email,
                ]);
            }
        });

        return redirect()->route('admin.settings.index')->with('success', 'Account settings saved successfully.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        // Sync with Employee record
        if ($user->employee) {
            $nameParts = explode(' ', $user->name, 2);
            $user->employee->update([
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $user->email,
            ]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Personal identity updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Security protocols updated.');
    }
}
