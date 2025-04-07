<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class GudangService
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.api.url');
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . Session::get('api_token'),
            'Accept' => 'application/json'
        ];
    }

    public function getAll($perPage = 10)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->apiBaseUrl . '/api/gudangs');

        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }

        throw new \Exception('Failed to get gudang list: ' . $response->body());
    }

    public function getById($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->apiBaseUrl . '/api/gudangs/' . $id);

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
            ->post($this->apiBaseUrl . '/api/gudangs', $data);

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json()['errors'] ?? []);
        }

        if (!$response->successful()) {
            throw new \Exception('Failed to create gudang: ' . $response->body());
        }

        return $response->json()['data'];
    }

    public function update($id, array $data)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->put($this->apiBaseUrl . '/api/gudangs/' . $id, $data);

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
            ->delete($this->apiBaseUrl . '/api/gudangs/' . $id);

        if (!$response->successful()) {
            throw new \Exception('Failed to delete gudang: ' . $response->body());
        }

        return true;
    }

    public function restore($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->apiBaseUrl . '/api/gudangs/' . $id . '/restore');

        if (!$response->successful()) {
            throw new \Exception('Failed to restore gudang: ' . $response->body());
        }

        return $response->json()['data'];
    }

    public function forceDelete($id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->delete($this->apiBaseUrl . '/api/gudangs/' . $id . '/force');

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
