<?php

namespace App\Http\Controllers;

use App\Services\TransactionTypeService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionTypeController extends Controller
{
    protected $transactionTypeService;

    public function __construct(TransactionTypeService $transactionTypeService)
    {
        $this->transactionTypeService = $transactionTypeService;
    }

    public function index()
    {
        try {
            $transactionTypes = $this->transactionTypeService->getAll();
            return view('frontend.transaksi_tipe.index', compact('transactionTypes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->transactionTypeService->create($request->all());
            return redirect()->route('frontend.transaksi_tipe.index')
                ->with('success', 'Tipe transaksi berhasil dibuat');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->transactionTypeService->update($id, $request->all());
            return redirect()->route('frontend.transaksi_tipe.index')
                ->with('success', 'Tipe transaksi berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->transactionTypeService->delete($id);
            return redirect()->route('frontend.transaksi_tipe.index')
                ->with('success', 'Tipe transaksi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('frontend.transaksi_tipe.index')
                ->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $this->transactionTypeService->restore($id);
            return redirect()->route('frontend.transaksi_tipe.index')
                ->with('success', 'Tipe transaksi berhasil dipulihkan');
        } catch (\Exception $e) {
            return redirect()->route('frontend.transaksi_tipe.index')
                ->with('error', $e->getMessage());
        }
    }
}