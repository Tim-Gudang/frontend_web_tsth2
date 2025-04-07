<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    public function login(array $credentials)
    {
        try {
            $response = Http::post("{$this->apiBaseUrl}/api/auth/login", [
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ]);

            if ($response->failed()) {
                throw new \Exception($response->json()['message'] ?? 'Login failed');
            }

            $data = $response->json()['data'];
            
            // Store token and user in session
            Session::put([
                'jwt_token' => $data['token'],
                'user' => $data['user']
            ]);

            return $data['user'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            $token = Session::get('jwt_token');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post("{$this->apiBaseUrl}/api/auth/logout");

            if ($response->failed()) {
                throw new \Exception($response->json()['message'] ?? 'Logout failed');
            }

            // Clear session
            Session::forget(['jwt_token', 'user']);

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getAuthenticatedUser()
    {
        return Session::get('user');
    }

    public function check()
    {
        return Session::has('jwt_token');
    }
}