<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'store':
                    return redirect()->intended('/store/dashboard');
                case 'beneficiary':
                    return redirect()->intended('/beneficiary/dashboard');
                case 'charity':
                    return redirect()->intended('/charity/dashboard');
                default:
                    return redirect()->intended('/');
            }

        }

        return back()->withErrors([
            'email' => 'Email or password is incorrect.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
