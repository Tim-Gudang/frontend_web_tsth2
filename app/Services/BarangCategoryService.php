<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class BarangCategoryService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    /**
     * Get headers with authentication token
     */
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

    /**
     * Fetch all barang categories
     */
    public function getAll($perPage = 10)
    {
        try {
            Log::debug('Fetching all barang categories', ['token' => Session::get('token')]);

            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiBaseUrl . '/barang-categories', ['per_page' => $perPage]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch barang categories: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch a single barang category by ID
     */
    public function getById($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiBaseUrl . '/barang-categories/' . $id);

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }

            if ($response->status() === 404) {
                return null;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch barang category: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new barang category
     */
    public function create(array $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->apiBaseUrl . '/barang-categories', $data);

            if ($response->status() === 422) {
                throw ValidationException::withMessages($response->json()['errors'] ?? []);
            }

            if (!$response->successful()) {
                throw new \Exception('Failed to create barang category: ' . ($response->json()['message'] ?? $response->body()));
            }

            return $response->json()['data'];
        } catch (\Exception $e) {
            Log::error('Failed to create barang category: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing barang category
     */
    public function update($id, array $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put($this->apiBaseUrl . '/barang-categories/' . $id, $data);

            if ($response->status() === 422) {
                throw ValidationException::withMessages($response->json()['errors'] ?? []);
            }

            if (!$response->successful()) {
                throw new \Exception('Failed to update barang category: ' . ($response->json()['message'] ?? $response->body()));
            }

            return $response->json()['data'];
        } catch (\Exception $e) {
            Log::error('Failed to update barang category: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a barang category
     */
    public function delete($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete($this->apiBaseUrl . '/barang-categories/' . $id);

            if (!$response->successful()) {
                throw new \Exception('Failed to delete barang category: ' . ($response->json()['message'] ?? $response->body()));
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete barang category: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle API error responses
     */
    protected function handleErrorResponse($response)
    {
        $status = $response->status();
        $message = $response->json()['message'] ?? $response->body();

        if ($status === 401) {
            Session::forget(['token', 'user']);
            throw new \Exception('Session expired. Please login again.');
        } elseif ($status === 404) {
            throw new \Exception('Barang category not found');
        } elseif ($status === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        throw new \Exception("API request failed: {$message}");
    }
}