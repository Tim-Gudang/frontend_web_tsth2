<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GudangService
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

    public function getAll($perPage = 10)
    {
        try {
            Log::debug('Attempting to fetch gudangs with token', ['token' => Session::get('token')]);

            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiBaseUrl . '/gudangs');

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to get gudang list: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function handleErrorResponse($response)
    {
        $status = $response->status();
        $message = $response->json()['message'] ?? $response->body();

        if ($status === 401) {
            // Clear session if unauthorized
            Session::forget(['token', 'user']);
            throw new \Exception('Session expired. Please login again.');
        } elseif ($status === 404) {
            throw new \Exception('Resource not found');
        } elseif ($status === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        throw new \Exception("API request failed: {$message}");
    }
    public function getById($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->apiBaseUrl . '/gudangs/' . $id);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }

        if ($response->status() === 404) {
            return null;
        }

        throw new \Exception('Failed to get gudang: ' . $response->body());
    }

    public function create(array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->apiBaseUrl . '/gudangs', $data); // Added /api prefix

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        if (!$response->successful()) {
            throw new \Exception('Failed to create gudang: ' . ($response->json()['message'] ?? $response->body()));
        }

        return $response->json()['data'];
    }

    public function update($id, array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->put($this->apiBaseUrl . '/gudangs/' . $id, $data);

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        if (!$response->successful()) {
            throw new \Exception('Failed to update gudang: ' . $response->body());
        }

        return $response->json()['data'];
    }

    public function delete($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->delete($this->apiBaseUrl . '/gudangs/' . $id);

        if (!$response->successful()) {
            throw new \Exception('Failed to delete gudang: ' . $response->body());
        }

        return true;
    }

    public function restore($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->apiBaseUrl . '/gudangs/' . $id . '/restore');

        if (!$response->successful()) {
            throw new \Exception('Failed to restore gudang: ' . $response->body());
        }

        return $response->json()['data'];
    }

    public function forceDelete($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->delete($this->apiBaseUrl . '/gudangs/' . $id . '/force');

        if (!$response->successful()) {
            throw new \Exception('Failed to permanently delete gudang: ' . $response->body());
        }

        return true;
    }
    public function getCount()
    {
        // Replace the logic below with the actual implementation to count gudangs
        return DB::table('gudangs')->count();
    }
}
