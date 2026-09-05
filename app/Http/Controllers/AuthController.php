<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('user.login');
    }

    public function showAdminLogin(): View
    {
        return view('admin.login');
    }

    public function showRegister(): View
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'identity_number' => ['required', 'string', 'max:50', 'unique:users,identity_number'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.user')->with('success', 'Registrasi berhasil.');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identity_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated, $request->boolean('remember'))) {
            return back()->withErrors([
                'identity_number' => 'NIS/NIP atau kata sandi salah.',
            ])->onlyInput('identity_number');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard.user');
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated) || Auth::user()->role !== 'admin') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Email admin atau kata sandi salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard.admin');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

