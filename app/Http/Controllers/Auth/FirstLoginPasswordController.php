<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FirstLoginPasswordController extends Controller
{
    public function edit(): View|RedirectResponse
    {
        if (!auth()->user()->must_change_password) {
            return redirect()->route('dashboard');
        }

        return view('auth.first-login-password');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed', Rule::notIn(['pass1234'])],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard');
    }
}
