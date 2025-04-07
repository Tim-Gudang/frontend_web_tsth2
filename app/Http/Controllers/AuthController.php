<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $response = Http::post("{$this->apiBaseUrl}/auth/login", $credentials);
            $data = $response->json();

            if ($response->successful() && isset($data['response_code']) && $data['response_code'] == 200) {
                session([
                    'token' => $data['data']['token'],
                    'user' => $data['data']['user']
                ]);

                session()->flash('login_success', 'Login berhasil! Selamat datang, ' . $data['data']['user']['name']);
                return redirect()->route('dashboard');
            }

            // Menampilkan error message dari API jika login gagal
            return back()->withErrors(['login_error' => $data['message'] ?? 'Login gagal. Coba lagi.']);
        } catch (RequestException $e) {
            // Jika terjadi error saat menghubungi API
            Log::error('Login API Error: ' . $e->getMessage());
            return back()->withErrors(['login_error' => 'Terjadi kesalahan saat menghubungi server.']);
        }
    }

    public function handleLogout()
    {
        $token = session('token');

        if ($token) {
            $response = Http::withToken($token)->post("{$this->apiBaseUrl}/auth/logout");

            if ($response->successful()) {
                Session::flush();
                return redirect()->route('auth.login')->with('logout_success', 'Anda telah logout.');
            }
        }

        return redirect()->route('auth.login');
    }
}
