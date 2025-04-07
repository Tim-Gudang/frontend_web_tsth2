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
            return view('transaction-types.index', compact('transactionTypes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('transaction-types.create');
    }

    public function store(Request $request)
    {
        try {
            $transactionType = $this->transactionTypeService->create($request->all());
            return redirect()->route('transaction-types.index')
                ->with('success', 'Transaction type created successfully');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $transactionType = $this->transactionTypeService->getById($id);
            if (!$transactionType) {
                return redirect()->route('transaction-types.index')
                    ->with('error', 'Transaction type not found');
            }
            return view('transaction-types.show', compact('transactionType'));
        } catch (\Exception $e) {
            return redirect()->route('transaction-types.index')
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $transactionType = $this->transactionTypeService->getById($id);
            if (!$transactionType) {
                return redirect()->route('transaction-types.index')
                    ->with('error', 'Transaction type not found');
            }
            return view('transaction-types.edit', compact('transactionType'));
        } catch (\Exception $e) {
            return redirect()->route('transaction-types.index')
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transactionType = $this->transactionTypeService->update($id, $request->all());
            return redirect()->route('transaction-types.index')
                ->with('success', 'Transaction type updated successfully');
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
            return redirect()->route('transaction-types.index')
                ->with('success', 'Transaction type deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('transaction-types.index')
                ->with('error', $e->getMessage());
        }
    }
}