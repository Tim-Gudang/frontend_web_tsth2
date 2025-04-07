<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BaseService
{
    protected $apiBaseUrl;
    protected $authService;

    public function __construct(AuthService $authService = null)
    {
        $this->apiBaseUrl = config('api.base_url');
        $this->authService = $authService ?? new AuthService();
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
        $token = $this->authService->getAccessToken();
        
        if (!$token) {
            throw new \Exception('No valid access token available. Please login again.');
        }
        
        return $token;
    }

    protected function handleResponse($response)
    {
        if ($response->status() === 401) {
            // Token likely expired
            session()->forget(['user', 'token']);
            throw new \Exception('Session expired. Please login again.');
        }

        if ($response->failed()) {
            $error = $response->json();
            throw new \Exception($error['message'] ?? 'Request failed', $response->status());
        }

        return $response->json()['data'] ?? $response->json();
    }

    protected function get($endpoint, $params = [])
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBaseUrl}/{$endpoint}", $params);
            
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function post($endpoint, $data = [])
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiBaseUrl}/{$endpoint}", $data);
            
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function put($endpoint, $data = [])
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put("{$this->apiBaseUrl}/{$endpoint}", $data);
            
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function delete($endpoint, $params = [])
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete("{$this->apiBaseUrl}/{$endpoint}", $params);
            
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}