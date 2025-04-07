<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        if ($this->authService->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        try {
            $user = $this->authService->login($request->only('email', 'password'));

            // Manually authenticate user in Laravel's web guard
            Auth::loginUsingId($user['id']);

            return redirect()->intended('dashboard')
                ->with('success', 'Login berhasil');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();
            Auth::logout(); // Logout from web guard

            return redirect()->route('login')
                ->with('success', 'Logout berhasil');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
