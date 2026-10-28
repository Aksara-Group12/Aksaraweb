<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\KategoriProduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ExpensesController extends BaseController
{
	public function index(Request $request)
	{
		$query = Expenses::where('user_id', auth()->user()->id);

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('kategori', 'like', "%{$search}%")
					->orWhere('keterangan', 'like', "%{$search}%");
			});
		}

		$perPage = (int) $request->input('per_page', 20);
		if ($perPage <= 0) {
			$perPage = 20;
		}

		$summaryTotal = (clone $query)->sum('nominal');
		$expenses = $query
			->orderBy('tanggal', 'desc')
			->orderBy('created_at', 'desc')
			->paginate($perPage)
			->appends($request->only('search', 'per_page'));

		return view('transaksi.expenses.index', compact('expenses', 'summaryTotal'));
	}

	public function create()
	{
		$kategoriProduk = KategoriProduk::where('tipe', 'expenses')->get();
		return view('transaksi.expenses.create', compact('kategoriProduk'));
	}

	public function store(Request $request)
	{
		try {
			$validated = $request->validate([
				'tanggal' => 'required|date',
				'kategori' => 'nullable|string|max:100',
				'kategori_produk_id' => 'nullable|exists:kategori_produk,id',
				'nominal' => 'required|numeric|min:0',
				'metode_pembayaran' => 'required|in:cash,non-cash',
				'keterangan' => 'nullable|string',
			]);

			Expenses::create([
				'user_id' => auth()->user()->id,
				'tanggal' => $validated['tanggal'],
				'kategori' => $validated['kategori'] ?? null,
				'nominal' => $validated['nominal'],
				'metode_pembayaran' => $validated['metode_pembayaran'],
				'keterangan' => $validated['keterangan'] ?? null,
				'reference_no' => null,
			]);

			return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dicatat');
		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()->withErrors($e->errors())->withInput();
		} catch (\Exception $e) {
			\Log::error('Store Expenses Error: ' . $e->getMessage());
			return back()->with('error', 'Terjadi kesalahan saat menyimpan pengeluaran');
		}
	}
 
	public function edit(Expenses $expense)
	{
		// Validasi kepemilikan
		if ((int) $expense->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke pengeluaran ini');
		}
		
		$kategoriProduk = KategoriProduk::where('tipe', 'expenses')->get();
		return view('transaksi.expenses.edit', compact('expense', 'kategoriProduk'));
	}

	public function update(Request $request, Expenses $expense)
	{
		// Validasi kepemilikan
		if ((int) $expense->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke pengeluaran ini');
		}

		try {
			$validated = $request->validate([
				'tanggal' => 'required|date',
				'kategori' => 'nullable|string|max:100',
				'kategori_produk_id' => 'nullable|exists:kategori_produk,id',
				'nominal' => 'required|numeric|min:0',
				'metode_pembayaran' => 'required|in:cash,non-cash',
				'keterangan' => 'nullable|string',
			]);

			$expense->update([
				'tanggal' => $validated['tanggal'],
				'kategori' => $validated['kategori'] ?? null,
				'nominal' => $validated['nominal'],
				'metode_pembayaran' => $validated['metode_pembayaran'],
				'keterangan' => $validated['keterangan'] ?? null,
			]);

			return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui');
		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()->withErrors($e->errors())->withInput();
		} catch (\Exception $e) {
			\Log::error('Update Expenses Error: ' . $e->getMessage());
			return back()->with('error', 'Terjadi kesalahan saat memperbarui pengeluaran');
		}
	}

	public function destroy(Expenses $expense)
	{
		// Validasi kepemilikan
		if ((int) $expense->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke pengeluaran ini');
		}

		try {
			$expense->delete();
			return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus');
		} catch (\Exception $e) {
			\Log::error('Destroy Expenses Error: ' . $e->getMessage());
			return back()->with('error', 'Terjadi kesalahan saat menghapus pengeluaran');
		}
	}

	public function bulkDestroy(Request $request)
	{
		$ids = $request->input('ids', []);
		if (is_string($ids)) {
			$decoded = json_decode($ids, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				$ids = $decoded;
			} else {
				$ids = array_filter(array_map('trim', explode(',', $ids)));
			}
		}
		if (!is_array($ids)) {
			$ids = [];
		}

		if (empty($ids) || !is_array($ids)) {
			return redirect()->route('expenses.index')->with('error', 'Tidak ada pengeluaran yang dipilih.');
		}

		// Validasi kepemilikan - hanya hapus yang milik user
		$deleted = Expenses::where('user_id', auth()->user()->id)
			->whereIn('id', $ids)
			->delete();

		return redirect()->route('expenses.index')->with('success', "{$deleted} pengeluaran berhasil dihapus");
	}

	public function exportExcel(Request $request)
	{
		$query = Expenses::query();

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('kategori', 'like', "%{$search}%")
					->orWhere('keterangan', 'like', "%{$search}%");
			});
		}

		$expenses = $query
			->orderBy('tanggal', 'desc')
			->orderBy('created_at', 'desc')
			->get();

		$stream = fopen('php://memory', 'r+');
		// BOM UTF-8 untuk Excel
		fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// Header kolom (tanpa aksi)
		fputcsv($stream, ['Tanggal', 'Kategori', 'Keterangan', 'Nominal', 'Metode'], ';');

		foreach ($expenses as $expense) {
			$tanggal = $expense->tanggal
				? $expense->tanggal->format('d/m/Y')
				: ($expense->created_at ? $expense->created_at->format('d/m/Y') : '');
			$metode = $expense->metode_pembayaran === 'cash' ? 'Cash' : 'Non-Cash';

			fputcsv($stream, [
				$tanggal,
				$expense->kategori ?? '-',
				$expense->keterangan ?? '-',
				$expense->nominal,
				$metode,
			], ';');
		}

		rewind($stream);
		$csv = stream_get_contents($stream);
		fclose($stream);

		$fileName = 'pengeluaran_' . now()->format('Ymd_His') . '.csv';

		return response($csv, 200, [
			'Content-Type' => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
		]);
	}

	public function exportPdf(Request $request)
	{
		$query = Expenses::query();

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('kategori', 'like', "%{$search}%")
					->orWhere('keterangan', 'like', "%{$search}%");
			});
		}

		$expenses = $query
			->orderBy('tanggal', 'desc')
			->orderBy('created_at', 'desc')
			->get();

		$pdf = Pdf::loadView('transaksi.expenses.export-pdf', [
			'expenses' => $expenses,
		]);

		$fileName = 'pengeluaran_' . now()->format('Ymd_His') . '.pdf';
		return $pdf->download($fileName);
	}
}

