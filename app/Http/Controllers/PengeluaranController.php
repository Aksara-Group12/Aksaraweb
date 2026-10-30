<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PengeluaranController extends BaseController
{
	public function index(Request $request)
	{
		$query = Pengeluaran::where('user_id', auth()->user()->id);

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
		$pengeluaran = $query
			->orderBy('tanggal', 'desc')
			->orderBy('created_at', 'desc')
			->paginate($perPage)
			->appends($request->only('search', 'per_page'));

		return view('transaksi.pengeluaran.index', compact('pengeluaran', 'summaryTotal'));
	}

	public function create()
	{
		$kategoriPengeluarans = KategoriPengeluaran::where('user_id', auth()->id())
			->active()
			->get();
		return view('transaksi.pengeluaran.create', compact('kategoriPengeluarans'));
	}

	public function store(Request $request)
	{
		try {
			$validated = $request->validate([
				'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
				'keterangan' => 'required|string|max:255',
				'nominal' => 'required|integer|min:0',
			]);

			$kategori = KategoriPengeluaran::find($validated['kategori_pengeluaran_id']);

			Pengeluaran::create([
				'user_id' => auth()->user()->id,
				'tanggal' => now()->format('Y-m-d'),
				'kategori' => $kategori->name,
				'nominal' => $validated['nominal'],
				'metode_pembayaran' => 'cash',
				'keterangan' => $validated['keterangan'],
				'reference_no' => null,
			]);

			return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dicatat');
		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()->withErrors($e->errors())->withInput();
		} catch (\Exception $e) {
			\Log::error('Store Pengeluaran Error: ' . $e->getMessage());
			return back()->with('error', 'Terjadi kesalahan saat menyimpan pengeluaran');
		}
	}
 
	public function edit(Pengeluaran $pengeluaran)
	{
		// Validasi kepemilikan
		if ((int) $pengeluaran->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke pengeluaran ini');
		}

		$kategoriPengeluarans = KategoriPengeluaran::where('user_id', auth()->id())
			->active()
			->get();
		return view('transaksi.pengeluaran.edit', compact('pengeluaran', 'kategoriPengeluarans'));
	}

	public function update(Request $request, Pengeluaran $pengeluaran)
	{
		// Validasi kepemilikan
		if ((int) $pengeluaran->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke pengeluaran ini');
		}

		try {
			$validated = $request->validate([
				'tanggal' => 'required|date',
				'kategori_pengeluaran_id' => 'nullable|exists:kategori_pengeluaran,id',
				'nominal' => 'required|integer|min:0',
				'metode_pembayaran' => 'required|in:cash,non-cash',
				'keterangan' => 'nullable|string',
			]);

			$kategori = isset($validated['kategori_pengeluaran_id']) ? KategoriPengeluaran::find($validated['kategori_pengeluaran_id']) : null;

			$pengeluaran->update([
				'tanggal' => $validated['tanggal'],
				'kategori' => $kategori ? $kategori->name : $pengeluaran->kategori,
				'nominal' => $validated['nominal'],
				'metode_pembayaran' => $validated['metode_pembayaran'],
				'keterangan' => $validated['keterangan'] ?? null,
			]);

			return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui');
		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()->withErrors($e->errors())->withInput();
		} catch (\Exception $e) {
			\Log::error('Update Pengeluaran Error: ' . $e->getMessage());
			return back()->with('error', 'Terjadi kesalahan saat memperbarui pengeluaran');
		}
	}

	public function destroy(Pengeluaran $pengeluaran)
	{
		// Validasi kepemilikan
		if ((int) $pengeluaran->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke pengeluaran ini');
		}

		try {
			$pengeluaran->delete();
			return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus');
		} catch (\Exception $e) {
			\Log::error('Destroy Pengeluaran Error: ' . $e->getMessage());
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
			return redirect()->route('pengeluaran.index')->with('error', 'Tidak ada pengeluaran yang dipilih.');
		}

		// Validasi kepemilikan - hanya hapus yang milik user
		$deleted = Pengeluaran::where('user_id', auth()->user()->id)
			->whereIn('id', $ids)
			->delete();

		return redirect()->route('pengeluaran.index')->with('success', "{$deleted} pengeluaran berhasil dihapus");
	}

	public function exportExcel(Request $request)
	{
		$query = Pengeluaran::query();

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('kategori', 'like', "%{$search}%")
					->orWhere('keterangan', 'like', "%{$search}%");
			});
		}

		$pengeluaran = $query
			->orderBy('tanggal', 'desc')
			->orderBy('created_at', 'desc')
			->get();

		$stream = fopen('php://memory', 'r+');
		// BOM UTF-8 untuk Excel
		fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// Header kolom (tanpa aksi)
		fputcsv($stream, ['Tanggal', 'Kategori', 'Keterangan', 'Nominal', 'Metode'], ';');

		foreach ($pengeluaran as $expense) {
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
		$query = Pengeluaran::query();

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('kategori', 'like', "%{$search}%")
					->orWhere('keterangan', 'like', "%{$search}%");
			});
		}

		$pengeluaran = $query
			->orderBy('tanggal', 'desc')
			->orderBy('created_at', 'desc')
			->get();

		$pdf = Pdf::loadView('transaksi.pengeluaran.export-pdf', [
			'pengeluaran' => $pengeluaran,
		]);

		$fileName = 'pengeluaran_' . now()->format('Ymd_His') . '.pdf';
		return $pdf->download($fileName);
	}
}

