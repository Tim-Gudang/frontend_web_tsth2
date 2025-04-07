<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        // Check using only one consistent authentication method
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
            $credentials = $request->only('email', 'password');
            $response = $this->authService->login($credentials);

            if (!$response['success']) {
                return back()
                    ->withInput()
                    ->withErrors(['message' => $response['message']]);
            }

            // Debug the session immediately after login
            Log::info('Login successful - Token in session: ' . (session()->has('token') ? 'YES' : 'NO'));
            Log::info('User data in session: ' . json_encode(session('user')));

            return redirect()->intended('dashboard')
                ->with('success', $response['message'] ?? 'Login berhasil');
        } catch (\Exception $e) {
            Log::error('Login exception: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $response = $this->authService->logout();

        // Ensure session is completely cleared
        session()->forget(['user', 'token']);
        session()->flush();

        // If using Laravel's built-in auth as well
        if (Auth::check()) {
            Auth::logout();
        }

        return redirect()->route('login')
            ->with('success', 'Logout berhasil');
    }
}
