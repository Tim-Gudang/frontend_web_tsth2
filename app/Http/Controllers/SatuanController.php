<?php

namespace App\Http\Controllers;

use App\Services\SatuanService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SatuanController extends Controller
{
    protected $satuanService;

    public function __construct(SatuanService $satuanService)
    {
        $this->satuanService = $satuanService;
    }

    public function index()
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $satuans = $this->satuanService->getAll();
            return view('frontend.satuan.index', compact('satuans'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('frontend.satuan.create');
    }

    public function store(Request $request)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $satuan = $this->satuanService->create($request->only('name', 'description'));
            return redirect()->route('satuans.index')
                ->with('success', 'Satuan berhasil dibuat');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $satuan = $this->satuanService->getById($id);
            if (!$satuan) {
                return response()->json(['message' => 'Satuan tidak ditemukan'], 404);
            }
            return response()->json(['data' => $satuan]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $satuan = $this->satuanService->getById($id);
            if (!$satuan) {
                return redirect()->route('satuans.index')
                    ->with('error', 'Satuan tidak ditemukan');
            }
            return view('frontend.satuan.edit', compact('satuan'));
        } catch (\Exception $e) {
            return redirect()->route('satuans.index')
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $this->satuanService->update($id, $request->only('name', 'description'));
            return redirect()->route('satuans.index')
                ->with('success', 'Satuan berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $this->satuanService->delete($id);
            return redirect()->route('satuans.index')
                ->with('success', 'Satuan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('satuans.index')
                ->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $this->satuanService->restore($id);
            return redirect()->route('satuans.index')
                ->with('success', 'Satuan berhasil dipulihkan');
        } catch (\Exception $e) {
            return redirect()->route('satuans.index')
                ->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $this->satuanService->forceDelete($id);
            return redirect()->route('satuans.index')
                ->with('success', 'Satuan berhasil dihapus permanen');
        } catch (\Exception $e) {
            return redirect()->route('satuans.index')
                ->with('error', $e->getMessage());
        }
    }
}
