<?php

namespace App\Http\Controllers;

use App\Services\BarangCategoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BarangCategoryController extends Controller
{
    protected $barangCategoryService;

    public function __construct(BarangCategoryService $barangCategoryService)
    {
        $this->barangCategoryService = $barangCategoryService;
    }

    /**
     * Display a listing of barang categories
     */
    public function index()
    {
        try {
            $token = session('token');
            $barangCategories = $this->barangCategoryService->getAll();
            return view('frontend.barangkategori.index', compact('barangCategories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new barang category
     */
    public function create()
    {
        return view('frontend.barangkategori.create');
    }

    /**
     * Store a newly created barang category
     */
    public function store(Request $request)
    {
        try {
            $this->barangCategoryService->create($request->all());
            return redirect()->route('frontend.barangkategori.index')
                ->with('success', 'Kategori barang berhasil dibuat');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified barang category
     */
    public function show($id)
    {
        try {
            $barangCategory = $this->barangCategoryService->getById($id);
            if (!$barangCategory) {
                return redirect()->route('frontend.barangkategori.index')
                    ->with('error', 'Kategori barang tidak ditemukan');
            }
            return view('frontend.barangkategori.show', compact('barangCategory'));
        } catch (\Exception $e) {
            return redirect()->route('frontend.barangkategori.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified barang category
     */
    public function edit($id)
    {
        try {
            $barangCategory = $this->barangCategoryService->getById($id);
            if (!$barangCategory) {
                return redirect()->route('frontend.barangkategori.index')
                    ->with('error', 'Kategori barang tidak ditemukan');
            }
            return view('frontend.barangkategori.edit', compact('barangCategory'));
        } catch (\Exception $e) {
            return redirect()->route('frontend.barangkategori.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified barang category
     */
    public function update(Request $request, $id)
    {
        try {
            $this->barangCategoryService->update($id, $request->all());
            return redirect()->route('frontend.barangkategori.index')
                ->with('success', 'Kategori barang berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified barang category
     */
    public function destroy($id)
    {
        try {
            $this->barangCategoryService->delete($id);
            return redirect()->route('frontend.barangkategori.index')
                ->with('success', 'Kategori barang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('frontend.barangkategori.index')
                ->with('error', $e->getMessage());
        }
    }
}
