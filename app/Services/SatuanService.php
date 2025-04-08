<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class SatuanService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    protected function getHeaders()
    {
        $token = Session::get('token');
        if (!$token) {
            Log::error('No authentication token found in session');
            throw new \Exception('Authentication token missing');
        }

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function getAll(int $perPage = 10)
    {
        try {
            Log::debug('Attempting to fetch satuans with token', ['token' => Session::get('token')]);
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBaseUrl}/satuans", ['per_page' => $perPage]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to get satuan list: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getById($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBaseUrl}/satuans/{$id}");

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }

            if ($response->status() === 404) {
                return null;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to get satuan: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiBaseUrl}/satuans", $data);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to create satuan: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put("{$this->apiBaseUrl}/satuans/{$id}", $data);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to update satuan: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete("{$this->apiBaseUrl}/satuans/{$id}");

            if ($response->successful()) {
                return true;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to delete satuan: ' . $e->getMessage());
            throw $e;
        }
    }

    public function restore($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiBaseUrl}/satuans/{$id}/restore");

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to restore satuan: ' . $e->getMessage());
            throw $e;
        }
    }

    public function forceDelete($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete("{$this->apiBaseUrl}/satuans/{$id}/force");

            if ($response->successful()) {
                return true;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to force delete satuan: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function handleErrorResponse($response): void
    {
        $status = $response->status();
        $message = $response->json()['message'] ?? $response->body();

        if ($status === 401) {
            Session::forget(['token', 'user']);
            throw new \Exception('Session expired. Please login again.');
        } elseif ($status === 404) {
            throw new \Exception('Resource not found');
        } elseif ($status === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        throw new \Exception("API request failed: {$message}");
    }
}