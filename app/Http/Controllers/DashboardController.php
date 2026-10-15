<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isManager() || $user->isUser()) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    }
}
