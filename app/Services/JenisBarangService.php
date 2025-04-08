<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class JenisBarangService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url'); // Pastikan ini dikonfigurasi di config.php atau .env
    }

    protected function getHeaders(): array
    {
        $token = Session::get('token');
        if (!$token) {
            Log::error('No authentication token found');
            throw new \Exception('Authentication token missing');
        }

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function getAll()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBaseUrl}/jenis-barangs");

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch jenis-barangs: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getById($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBaseUrl}/jenis-barangs/{$id}");

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }

            if ($response->status() === 404) {
                return null;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch jenis-barang by ID: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();
            $validatedData['slug'] = Str::slug($validatedData['name']);
            $validatedData['user_id'] = auth()->id();

            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiBaseUrl}/jenis-barangs", $validatedData);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to create jenis-barang: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();
            $validatedData['slug'] = Str::slug($validatedData['name']);
            $validatedData['user_id'] = auth()->id();

            $response = Http::withHeaders($this->getHeaders())
                ->put("{$this->apiBaseUrl}/jenis-barangs/{$id}", $validatedData);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to update jenis-barang: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete("{$this->apiBaseUrl}/jenis-barangs/{$id}");

            if ($response->successful()) {
                return true;
            }

            if ($response->status() === 404) {
                return false;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to delete jenis-barang: ' . $e->getMessage());
            throw $e;
        }
    }

    public function restore($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiBaseUrl}/jenis-barangs/{$id}/restore");

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to restore jenis-barang: ' . $e->getMessage());
            throw $e;
        }
    }

    public function forceDelete($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete("{$this->apiBaseUrl}/jenis-barangs/{$id}/force");

            if ($response->successful()) {
                return true;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to force delete jenis-barang: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function handleErrorResponse($response): void
    {
        $status = $response->status();
        $message = $response->json()['message'] ?? $response->body();

        if ($status === 401) {
            throw new \Exception('Unauthorized: Session expired or invalid token.');
        } elseif ($status === 404) {
            throw new \Exception('Resource not found');
        } elseif ($status === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        } elseif ($status === 403) {
            throw new \Exception('Forbidden: You do not have permission to perform this action.');
        }

        throw new \Exception("API request failed: {$message}");
    }
}