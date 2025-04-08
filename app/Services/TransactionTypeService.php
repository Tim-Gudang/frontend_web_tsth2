<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class TransactionTypeService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    protected function getHeaders()
    {
        $token = Session::get('token') ?? request()->bearerToken();

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

    // In TransactionTypeService.php

    public function getAll($perPage = 10)
    {
        try {
            Log::debug('Fetching all transaction types', ['token' => Session::get('token')]);

            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiBaseUrl . '/transaction-types', [
                    'per_page' => $perPage,
                    'with_trashed' => true, // Include soft-deleted items
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Convert the array items to objects for easier access in the view
                return array_map(function ($item) {
                    return (object) $item;
                }, $data['data'] ?? []);
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch transaction types: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getById($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiBaseUrl . '/transaction-types/' . $id);

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }

            if ($response->status() === 404) {
                return null;
            }

            $this->handleErrorResponse($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch transaction type: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->apiBaseUrl . '/transaction-types', $data);

            if ($response->status() === 422) {
                throw ValidationException::withMessages($response->json()['errors'] ?? []);
            }

            if (!$response->successful()) {
                throw new \Exception('Failed to create transaction type: ' . ($response->json()['message'] ?? $response->body()));
            }

            return $response->json()['data'];
        } catch (\Exception $e) {
            Log::error('Failed to create transaction type: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put($this->apiBaseUrl . '/transaction-types/' . $id, $data);

            if ($response->status() === 422) {
                throw ValidationException::withMessages($response->json()['errors'] ?? []);
            }

            if (!$response->successful()) {
                throw new \Exception('Failed to update transaction type: ' . ($response->json()['message'] ?? $response->body()));
            }

            return $response->json()['data'];
        } catch (\Exception $e) {
            Log::error('Failed to update transaction type: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete($this->apiBaseUrl . '/transaction-types/' . $id);

            if (!$response->successful()) {
                throw new \Exception('Failed to delete transaction type: ' . ($response->json()['message'] ?? $response->body()));
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete transaction type: ' . $e->getMessage());
            throw $e;
        }
    }

    public function restore($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->apiBaseUrl . '/transaction-types/' . $id . '/restore');

            if (!$response->successful()) {
                throw new \Exception('Failed to restore transaction type: ' . ($response->json()['message'] ?? $response->body()));
            }

            return $response->json()['data'];
        } catch (\Exception $e) {
            Log::error('Failed to restore transaction type: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function handleErrorResponse($response)
    {
        $status = $response->status();
        $message = $response->json()['message'] ?? $response->body();

        if ($status === 401) {
            Session::forget(['token', 'user']);
            throw new \Exception('Session expired. Please login again.');
        } elseif ($status === 404) {
            throw new \Exception('Transaction type not found');
        } elseif ($status === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        throw new \Exception("API request failed: {$message}");
    }
}
