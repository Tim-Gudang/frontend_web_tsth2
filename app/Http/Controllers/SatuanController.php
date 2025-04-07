<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SatuanController extends Controller
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
            $response = $client->get("{$this->apiBaseUrl}/satuans", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            $satuans = json_decode($response->getBody(), true)['data'] ?? [];

            return view('frontend.satuan.index', compact('satuans'));
        } catch (\Exception $e) {
            return view('error.error', ['error' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $response = Http::withToken($token)
                ->post("{$this->apiBaseUrl}/satuans", $request->only('name', 'description'));

            if ($response->successful()) {
                return redirect()->route('satuans.index')->with('success', 'Berhasil menambahkan satuan!');
            }

            return back()->withErrors(['message' => 'Gagal menyimpan satuan.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
    public function edit($id)
{
    try {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
        }

        $response = Http::withToken($token)->get("{$this->apiBaseUrl}/satuans/{$id}");

        if ($response->successful()) {
            $satuan = $response->json('data');
            return view('frontend.satuan.edit', compact('satuan'));
        }

        return redirect()->route('satuans.index')->withErrors('Data tidak ditemukan.');
    } catch (\Exception $e) {
        return back()->withErrors($e->getMessage());
    }
}

public function update(Request $request, $id)
{
    try {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
        }

        $response = Http::withToken($token)
            ->put("{$this->apiBaseUrl}/satuans/{$id}", $request->only('name', 'description'));

        if ($response->successful()) {
            return redirect()->route('satuans.index')->with('success', 'Berhasil memperbarui satuan!');
        }

        return back()->withErrors(['message' => 'Gagal memperbarui satuan.']);
    } catch (\Exception $e) {
        return back()->withErrors(['message' => $e->getMessage()]);
    }
}


public function destroy($id)
{
    try {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
        }

        $response = Http::withToken($token)->delete("{$this->apiBaseUrl}/satuans/{$id}");

        if ($response->successful()) {
            return redirect()->route('satuans.index')->with('success', 'Berhasil menghapus satuan!');
        }

        return back()->withErrors(['message' => 'Gagal menghapus satuan.']);
    } catch (\Exception $e) {
        return back()->withErrors(['message' => $e->getMessage()]);
    }
}
public function show($id)
{
    try {
        $token = session('token');
        if (!$token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $response = Http::withToken($token)
            ->get("{$this->apiBaseUrl}/satuans/{$id}");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['message' => 'Data tidak ditemukan'], $response->status());
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

}
