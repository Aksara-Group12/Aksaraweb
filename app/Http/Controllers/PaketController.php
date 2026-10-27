<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PaketController extends BaseController
{
	protected function resolveView(string $view): string
	{
		$superadminView = 'superadmin.' . $view;
		return view()->exists($superadminView) ? $superadminView : $view;
	}

	protected function ensureSuperadmin(): void
	{
		$user = auth()->user();
		if (!$user || $user->role !== 'superadmin') {
			abort(403, 'Anda tidak memiliki akses ke halaman ini');
		}
	}

	public function index(Request $request)
	{
		$this->ensureSuperadmin();

		$query = Paket::query();

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('nama_paket', 'like', "%{$search}%")
					->orWhere('deskripsi', 'like', "%{$search}%");
			});
		}

		if ($status = $request->input('status')) {
			if ($status === 'active') {
				$query->where('is_active', 1);
			} elseif ($status === 'inactive') {
				$query->where('is_active', 0);
			}
		}

		$perPage = (int) $request->input('per_page', 20);
		if ($perPage <= 0) {
			$perPage = 20;
		}

		$pakets = $query
			->orderByDesc('is_active')
			->orderBy('harga')
			->orderByDesc('created_at')
			->paginate($perPage)
			->appends($request->only('search', 'status', 'per_page'));

		return view($this->resolveView('paket.index'), compact('pakets'));
	}

	public function create()
	{
		$this->ensureSuperadmin();

		return view($this->resolveView('paket.create'));
	}

	public function store(Request $request)
	{
		$this->ensureSuperadmin();

		$validated = $request->validate([
			'nama_paket' => 'required|string|max:120',
			'deskripsi' => 'nullable|string',
			'harga' => 'required|numeric|min:0',
			'harga_diskon' => 'nullable|numeric|min:0',
			'durasi_bulan' => 'required|integer|min:1',
			'is_active' => 'required|boolean',
		]);

		Paket::create($validated);

		return redirect()
			->route('paket.index')
			->with('success', 'Paket berhasil ditambahkan.');
	}

	public function edit(Paket $paket)
	{
		$this->ensureSuperadmin();

		return view($this->resolveView('paket.edit'), compact('paket') + ['status' => $paket->is_active ? 1 : 0]);
	}

	public function update(Request $request, Paket $paket)
	{
		$this->ensureSuperadmin();

		$validated = $request->validate([
			'nama_paket' => 'required|string|max:120',
			'deskripsi' => 'nullable|string',
			'harga' => 'required|numeric|min:0',
			'harga_diskon' => 'nullable|numeric|min:0',
			'durasi_bulan' => 'required|integer|min:1',
			'is_active' => 'required|boolean',
		]);

		$paket->update($validated);

		return redirect()
			->route('paket.index')
			->with('success', 'Paket berhasil diperbarui.');
	}

	public function destroy(Paket $paket)
	{
		$this->ensureSuperadmin();

		$paket->delete();

		return redirect()
			->route('paket.index')
			->with('success', 'Paket berhasil dihapus.');
	}
}
