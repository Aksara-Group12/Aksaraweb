<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Display a listing of admins for current user
     */
    public function index(Request $request)
    {
        $query = Admin::where('user_id', auth()->id());

        // Search
        if ($search = $request->input('search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Show deleted
        if ($request->input('deleted') === 'only') {
            $query->onlyTrashed();
        }

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) $perPage = 10;

        $admins = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.index', compact('admins'));
    }

    /**
     * Show form create admin
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store admin in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['password'] = bcrypt($validated['password']);
        $validated['email_verified_at'] = null; // Belum verifikasi

        $admin = Admin::create($validated);

        // Kirim email verifikasi
        Mail::to($admin->email)->send(new \App\Mail\AdminVerifyEmail($admin));

        return redirect()->route('admin.index')
            ->with('success', 'Admin berhasil dibuat! Email verifikasi telah dikirim ke ' . $admin->email);
    }

    /**
     * Show form edit admin
     */
    public function edit($id)
    {
        // Cari admin termasuk yang soft-deleted
        $admin = Admin::withTrashed()->find($id);

        // Jika admin tidak ditemukan
        if (!$admin) {
            return redirect()->route('admin.index')
                ->with('error', 'Admin tidak ditemukan');
        }

        // Pastikan hanya pemilik bisa edit
        if ((int) $admin->user_id !== (int) auth()->id()) {
            return redirect()->route('admin.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit admin ini');
        }

        return view('admin.edit', compact('admin'));
    }

    /**
     * Update admin in database
     */
    public function update(Request $request, $id)
    {
        // Cari admin termasuk yang soft-deleted
        $admin = Admin::withTrashed()->find($id);

        // Jika admin tidak ditemukan
        if (!$admin) {
            return redirect()->route('admin.index')
                ->with('error', 'Admin tidak ditemukan');
        }

        // Pastikan hanya pemilik bisa update
        if ((int) $admin->user_id !== (int) auth()->id()) {
            return redirect()->route('admin.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit admin ini');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Jika password diisi, hash dan include di update
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            // Jika password kosong, remove dari validated array
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('admin.index')
            ->with('success', 'Admin berhasil diperbarui');
    }

    /**
     * Delete admin (soft delete)
     */
    public function destroy($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route('admin.index')
                ->with('error', 'Admin tidak ditemukan');
        }

        if ((int) $admin->user_id !== (int) auth()->id()) {
            return redirect()->route('admin.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus admin ini');
        }

        $admin->delete();

        return redirect()->route('admin.index')
            ->with('success', 'Admin berhasil dihapus');
    }

    /**
     * Restore admin from soft delete
     */
    public function restore($id)
    {
        $admin = Admin::withTrashed()->find($id);

        if (!$admin) {
            return redirect()->route('admin.index')
                ->with('error', 'Admin tidak ditemukan');
        }

        if ((int) $admin->user_id !== (int) auth()->id()) {
            return redirect()->route('admin.index')
                ->with('error', 'Anda tidak memiliki akses untuk memulihkan admin ini');
        }

        $admin->restore();

        return redirect()->route('admin.index')
            ->with('success', 'Admin berhasil dipulihkan');
    }

    /**
     * Bulk delete admins
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.index')
                ->with('error', 'Pilih minimal 1 admin');
        }

        Admin::whereIn('id', $ids)
            ->where('user_id', auth()->id())
            ->delete();

        return redirect()->route('admin.index')
            ->with('success', 'Admin berhasil dihapus');
    }

    /**
     * Verify admin email
     */
    public function verifyEmail($id, Request $request)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect('/')->with('error', 'Admin tidak ditemukan');
        }

        // Verify token
        $token = md5($admin->email);
        if ($request->query('token') !== $token) {
            return redirect('/')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa');
        }

        // Jika sudah verifikasi
        if ($admin->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'Email sudah terverifikasi sebelumnya');
        }

        // Mark as verified
        $admin->markEmailAsVerified();

        return redirect('/')->with('success', 'Email berhasil diverifikasi! Anda dapat login sekarang');
    }
}
