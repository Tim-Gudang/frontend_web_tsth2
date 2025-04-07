<?php

namespace App\Services;

use App\Http\Constant\ApiConstant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class BaseService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ];
    }

    protected function getAccessToken()
    {
        if (Auth::check() && Auth::user()->token()) {
            return Auth::user()->token()->accessToken;
        }

        throw new \Exception('No valid access token available');
    }

    protected function handleResponse($response)
    {
        if ($response->failed()) {
            $error = $response->json();
            throw new \Exception($error['message'] ?? 'Request failed', $error['code'] ?? 500);
        }

        return $response->json()['data'] ?? $response->json();
    }

    protected function withOAuthErrorHandling(callable $callback)
    {
        try {
            return $callback();
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            // Handle token expired or invalid
            if (Auth::check()) {
                Auth::user()->token()->delete();
            }
            throw new \Exception('Session expired. Please login again.');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
