<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    /**
     * Display listing of banks
     */
    public function index(Request $request)
    {
        $query = Bank::query();

        // Search by bank name atau account holder
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', '%' . $search . '%')
                    ->orWhere('account_holder', 'like', '%' . $search . '%')
                    ->orWhere('account_number', 'like', '%' . $search . '%');
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $banks = $query->paginate($perPage);

        return view('superadmin.bank.index', compact('banks'));
    }

    /**
     * Show the form for creating a new bank
     */
    public function create()
    {
        return view('superadmin.bank.create');
    }

    /**
     * Store a newly created bank in database
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'bank_name' => 'required|string|max:100|unique:banks',
                'account_holder' => 'required|string|max:100',
                'account_number' => 'required|string|max:50|unique:banks',
            ]);

            Bank::create($validated);

            return redirect()
                ->route('bank.index')
                ->with('success', 'Bank berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error creating bank', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan bank: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified bank
     */
    public function edit(Bank $bank)
    {
        return view('superadmin.bank.edit', compact('bank'));
    }

    /**
     * Update the specified bank in database
     */
    public function update(Request $request, Bank $bank)
    {
        try {
            $validated = $request->validate([
                'bank_name' => 'required|string|max:100|unique:banks,bank_name,' . $bank->id,
                'account_holder' => 'required|string|max:100',
                'account_number' => 'required|string|max:50|unique:banks,account_number,' . $bank->id,
            ]);

            $bank->update($validated);

            return redirect()
                ->route('bank.index')
                ->with('success', 'Bank berhasil diubah');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating bank', [
                'bank_id' => $bank->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Gagal mengubah bank: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified bank from database
     */
    public function destroy(Bank $bank)
    {
        try {
            $bank->delete();

            return redirect()
                ->route('bank.index')
                ->with('success', 'Bank berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error deleting bank', [
                'bank_id' => $bank->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus bank: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple banks
     */
    public function bulkDestroy(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return redirect()
                    ->back()
                    ->with('error', 'Pilih bank yang ingin dihapus');
            }

            Bank::whereIn('id', $ids)->delete();

            return redirect()
                ->route('bank.index')
                ->with('success', count($ids) . ' bank(s) berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error bulk deleting banks', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus bank: ' . $e->getMessage());
        }
    }
}
