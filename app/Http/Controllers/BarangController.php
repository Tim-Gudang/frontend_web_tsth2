<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    public function index()
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $client = new Client();

            // Fetch Data Barang dengan Relasi
            $response = $client->get("{$this->apiBaseUrl}/barangs", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);
            $barangs = json_decode($response->getBody(), true)['data'] ?? [];

            // Fetch Categories
            $responseCategories = $client->get("{$this->apiBaseUrl}/barang-categories", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);
            $categories = json_decode($responseCategories->getBody(), true)['data'] ?? [];

            // Fetch Jenis Barang
            $responseJenis = $client->get("{$this->apiBaseUrl}/jenis-barangs", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);
            $jenisbarangs = json_decode($responseJenis->getBody(), true)['data'] ?? [];

            // Fetch Satuan
            $responseSatuan = $client->get("{$this->apiBaseUrl}/satuans", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);
            $satuans = json_decode($responseSatuan->getBody(), true)['data'] ?? [];

            return view('frontend.barang.index', [
                'barangs' => $barangs,
                'categories' => $categories,
                'jenisbarangs' => $jenisbarangs,
                'satuans' => $satuans,
            ]);
        } catch (\Exception $e) {
            return view('error.error', ['error' => $e->getMessage()]);
        }
    }
}
