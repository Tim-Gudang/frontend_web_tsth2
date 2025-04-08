<?php

namespace App\Http\Controllers;

use App\Services\GudangService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GudangController extends Controller
{
    protected $gudangService;

    public function __construct(GudangService $gudangService)
    {
        $this->gudangService = $gudangService;
    }

    public function index()
    {
        try {
            $gudangs = $this->gudangService->getAll();
            return view('gudangs.index', compact('gudangs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('gudangs.create');
    }

    public function store(Request $request)
    {
        try {
            $gudang = $this->gudangService->create($request->all());
            return redirect()->route('gudangs.index')
                ->with('success', 'Gudang berhasil dibuat');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $gudang = $this->gudangService->getById($id);
            if (!$gudang) {
                return redirect()->route('gudangs.index')
                    ->with('error', 'Gudang tidak ditemukan');
            }
            return view('gudangs.show', compact('gudang'));
        } catch (\Exception $e) {
            return redirect()->route('gudangs.index')
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $gudang = $this->gudangService->getById($id);
            if (!$gudang) {
                return redirect()->route('gudangs.index')
                    ->with('error', 'Gudang tidak ditemukan');
            }
            return view('gudangs.edit', compact('gudang'));
        } catch (\Exception $e) {
            return redirect()->route('gudangs.index')
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->gudangService->update($id, $request->all());
            return redirect()->route('gudangs.index')
                ->with('success', 'Gudang berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->gudangService->delete($id);
            return redirect()->route('gudangs.index')
                ->with('success', 'Gudang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('gudangs.index')
                ->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $this->gudangService->restore($id);
            return redirect()->route('gudangs.index')
                ->with('success', 'Gudang berhasil dipulihkan');
        } catch (\Exception $e) {
            return redirect()->route('gudangs.index')
                ->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->gudangService->forceDelete($id);
            return redirect()->route('gudangs.index')
                ->with('success', 'Gudang berhasil dihapus permanen');
        } catch (\Exception $e) {
            return redirect()->route('gudangs.index')
                ->with('error', $e->getMessage());
        }
    }
}
