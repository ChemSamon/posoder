<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin_login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (\Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/admin')->with('success', 'Login successful');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ])->withInput();
}
}
