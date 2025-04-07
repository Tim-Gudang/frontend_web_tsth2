<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $client;
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiBaseUrl = config('api.base_url');
    }

    public function login(array $credentials)
    {
        try {
            Log::info('Login attempt for email: ' . $credentials['email']);

            $response = Http::post("{$this->apiBaseUrl}/auth/login", [
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ]);

            if ($response->failed()) {
                $errorData = $response->json();
                Log::warning('Login failed: ' . ($errorData['message'] ?? 'Unknown error'));
                return [
                    'success' => false,
                    'message' => $errorData['message'] ?? 'Login failed',
                    'status' => $response->status(),
                    'response_code' => $errorData['response_code'] ?? '401'
                ];
            }

            $responseData = $response->json();
            $userData = $responseData['data']['user'] ?? null;
            $accessToken = $responseData['data']['token'] ?? null;

            // Store in session
            if ($userData && $accessToken) {
                session([
                    'user' => $userData,
                    'token' => $accessToken
                ]);

                // Verify session was set correctly
                Log::info('Session set - Token exists: ' . (session()->has('token') ? 'YES' : 'NO'));
                Log::info('Session user data: ' . json_encode(session('user')));
            } else {
                Log::error('Missing user data or token in API response');
            }

            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Login berhasil',
                'status' => $response->status(),
                'response_code' => $responseData['response_code'] ?? '200',
                'data' => $responseData['data'] ?? [
                    'user' => null,
                    'token' => null
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Login exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'status' => 500,
                'response_code' => '500'
            ];
        }
    }
    public function logout()
    {
        try {
            $token = session('token');

            if (!$token) {
                return [
                    'success' => false,
                    'message' => 'No token found in session',
                    'status' => 401,
                    'response_code' => '401'
                ];
            }

            // Call API to revoke token
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->post("{$this->apiBaseUrl}/auth/logout");

            // Clear session regardless of API response
            session()->forget(['user', 'token']);
            session()->flush();

            if ($response->failed()) {
                $errorData = $response->json();
                return [
                    'success' => false,
                    'message' => $errorData['message'] ?? 'Logout failed',
                    'status' => $response->status(),
                    'response_code' => $errorData['response_code'] ?? '401'
                ];
            }

            $responseData = $response->json();
            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Logout berhasil',
                'status' => $response->status(),
                'response_code' => $responseData['response_code'] ?? '200'
            ];
        } catch (\Exception $e) {
            // Clear session on exception
            session()->forget(['user', 'token']);
            session()->flush();

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'status' => 500,
                'response_code' => '500'
            ];
        }
    }
    public function check()
    {
        $hasToken = session()->has('token');
        Log::info('Auth check - Token exists: ' . ($hasToken ? 'YES' : 'NO'));
        return $hasToken;
    }

    public function getAuthenticatedUser()
    {
        return session('user');
    }

    public function getAccessToken()
    {
        return session('token');
    }
}
