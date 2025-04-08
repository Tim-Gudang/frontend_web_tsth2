<?php

namespace App\Http\Controllers;

use App\Services\JenisBarangService;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    protected $jenisBarangService;

    public function __construct(JenisBarangService $jenisBarangService)
    {
        $this->jenisBarangService = $jenisBarangService;
    }

    public function index()
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $jenisBarangs = $this->jenisBarangService->getAll();
            return view('frontend.jenis-barang.index', compact('jenisBarangs'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            return view('frontend.jenis-barang.create');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $response = $this->jenisBarangService->create($request->only([
                'name',
                'description',
            ]));

            return redirect()->route('jenis-barangs.index')->with('success', 'Jenis barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $jenisBarang = $this->jenisBarangService->getById($id);
            if (!$jenisBarang) {
                return redirect()->route('jenis-barangs.index')->withErrors(['message' => 'Jenis barang tidak ditemukan.']);
            }

            return view('frontend.jenis-barang.edit', compact('jenisBarang'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $data = $request->only(['name', 'description']);

            $response = $this->jenisBarangService->update($id, $data);

            return redirect()->route('jenis-barangs.index')->with('success', 'Jenis barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $deleted = $this->jenisBarangService->delete($id);
            if (!$deleted) {
                return back()->withErrors(['message' => 'Jenis barang tidak ditemukan.']);
            }

            return redirect()->route('jenis-barangs.index')->with('success', 'Jenis barang berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function restore($id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $jenisBarang = $this->jenisBarangService->restore($id);
            return redirect()->route('jenis-barangs.index')->with('success', 'Jenis barang berhasil dikembalikan!');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function forceDelete($id)
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $this->jenisBarangService->forceDelete($id);
            return redirect()->route('jenis-barangs.index')->with('success', 'Jenis barang berhasil dihapus permanen!');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
