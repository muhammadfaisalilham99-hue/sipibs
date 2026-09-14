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

    public function showTeacherLogin(): View
    {
        return view('teacher.login');
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
            'role' => ['required', 'in:siswa,guru'],
            'name' => ['required', 'string', 'max:255'],
            'identity_number' => ['required', 'string', 'max:50', 'unique:users,identity_number'],
            'identity_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'class_name' => ['nullable', 'string', 'max:100'],
            'subject' => ['nullable', 'string', 'max:100'],
            'room_name' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'terms' => ['accepted'],
        ]);

        $identityDocPath = '/storage/' . $request->file('identity_document')->store('identity-documents', 'public');

        $user = User::create([
            'name' => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'identity_document' => $identityDocPath,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'class_name' => $validated['class_name'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'room_name' => $validated['room_name'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
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

        if (! Auth::attempt($validated, $request->boolean('remember')) || Auth::user()->role === 'admin' || Auth::user()->role === 'guru') {
            Auth::logout();
            return back()->withErrors([
                'identity_number' => 'NIS atau kata sandi siswa salah.',
            ])->onlyInput('identity_number');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard.user');
    }

    public function teacherLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identity_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated, $request->boolean('remember')) || Auth::user()->role !== 'guru') {
            Auth::logout();
            return back()->withErrors([
                'identity_number' => 'NIP/NUPTK atau kata sandi guru salah.',
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


