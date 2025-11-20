<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserAuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        // Validate input including CAPTCHA
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'captcha' => ['required', 'string', 'size:6'],
        ]);

        // Verify CAPTCHA - TRIM whitespace first
        $userCaptcha = strtoupper(trim($request->input('captcha', '')));
        $sessionCaptcha = strtoupper(trim(session('captcha', '')));
        
        Log::info('Register CAPTCHA Verification', [
            'user_input' => substr($userCaptcha, 0, 2) . '***',
            'session_captcha' => substr($sessionCaptcha, 0, 2) . '***',
            'match' => $userCaptcha === $sessionCaptcha,
            'session_has_captcha' => session()->has('captcha')
        ]);
        
        if ($userCaptcha !== $sessionCaptcha || empty($sessionCaptcha)) {
            Log::warning('Register CAPTCHA Failed', [
                'user_input' => $userCaptcha,
                'session_captcha' => $sessionCaptcha
            ]);
            return back()->withErrors([
                'captcha' => 'Kode CAPTCHA salah. Silakan coba lagi.',
            ])->withInput($request->except('password', 'password_confirmation'));
        }
        
        // Clear CAPTCHA from session after verification
        session()->forget('captcha');
        session()->save();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->intended('/')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        // Validate input including CAPTCHA
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required', 'string', 'size:6'],
        ]);

        // Verify CAPTCHA - TRIM whitespace first
        $userCaptcha = strtoupper(trim($request->input('captcha', '')));
        $sessionCaptcha = strtoupper(trim(session('captcha', '')));
        
        Log::info('Login CAPTCHA Verification', [
            'user_input' => substr($userCaptcha, 0, 2) . '***',
            'session_captcha' => substr($sessionCaptcha, 0, 2) . '***',
            'match' => $userCaptcha === $sessionCaptcha,
            'session_has_captcha' => session()->has('captcha')
        ]);
        
        if ($userCaptcha !== $sessionCaptcha || empty($sessionCaptcha)) {
            Log::warning('Login CAPTCHA Failed', [
                'user_input' => $userCaptcha,
                'session_captcha' => $sessionCaptcha
            ]);
            return back()->withErrors([
                'captcha' => 'Kode CAPTCHA salah. Silakan coba lagi.',
            ])->withInput($request->except('password', 'password_confirmation'));
        }
        
        // Clear CAPTCHA from session after verification
        session()->forget('captcha');
        session()->save();

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Check if user exists and is not admin
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && $user->role === 'admin') {
            return back()->withErrors([
                'email' => 'Silakan gunakan halaman login admin.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}
