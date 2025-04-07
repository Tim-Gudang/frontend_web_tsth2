<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class TransactionTypeService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . Session::get('api_token'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    public function getAll()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->apiBaseUrl . '/api/transaction-types');

        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }

        throw new \Exception('Failed to get transaction types: ' . $response->body());
    }

    public function getById($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->apiBaseUrl . '/api/transaction-types/' . $id);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }

        if ($response->status() === 404) {
            return null;
        }

        throw new \Exception('Failed to get transaction type: ' . $response->body());
    }

    public function create(array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->apiBaseUrl . '/api/transaction-types', $data);

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        if (!$response->successful()) {
            throw new \Exception('Failed to create transaction type: ' . $response->body());
        }

        return $response->json()['data'];
    }

    public function update($id, array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->put($this->apiBaseUrl . '/api/transaction-types/' . $id, $data);

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        if (!$response->successful()) {
            throw new \Exception('Failed to update transaction type: ' . $response->body());
        }

        return $response->json()['data'];
    }

    public function delete($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->delete($this->apiBaseUrl . '/api/transaction-types/' . $id);

        if (!$response->successful()) {
            throw new \Exception('Failed to delete transaction type: ' . $response->body());
        }

        return true;
    }
}
