<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscribe;
use App\Models\Paket;
use App\Mail\SubscriptionApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ManajemenUserController extends Controller
{
    /**
     * Tampilkan daftar user dengan role 'owner'
     */
    public function index(Request $request)
    {
        // Hanya superadmin yang bisa akses halaman ini
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized: Only superadmin can access this page');
        }

        $query = User::where('role', 'owner');

        // Search by name atau email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        // Sort by pending subscription first (users dengan pending approval prioritas atas)
        $query->leftJoin('subscriptions as pending_subs', function ($join) {
            $join->on('users.id', '=', 'pending_subs.user_id')
                ->where('pending_subs.approval_status', '=', 'pending');
        })
        ->select('users.*', DB::raw('CASE WHEN pending_subs.id IS NOT NULL THEN 0 ELSE 1 END as has_pending'))
        ->orderBy('has_pending', 'asc')
        ->orderBy('users.created_at', 'desc');

        $users = $query->distinct()->paginate($perPage);

        $activeSubscriptions = Subscribe::whereIn('user_id', $users->pluck('id'))
            ->where('status', 'active')
            ->where('end_date', '>=', Carbon::now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                return $group->first();
            });

        return view('superadmin.manajemen-user.index', compact('users', 'activeSubscriptions'));
    }

    /**
     * Tampilkan form edit user
     */
    public function edit(User $user)
    {
        // Pastikan hanya edit user dengan role 'owner'
        if ($user->role !== 'owner') {
            abort(403, 'Anda hanya bisa mengedit user dengan role owner');
        }

        return view('superadmin.manajemen-user.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        // Pastikan hanya edit user dengan role 'owner'
        if ($user->role !== 'owner') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'nullable|in:aktif,trial,non_aktif',
        ]);

        // Jangan update password jika kosong
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        // Handle status change jika ada
        if (!empty($validated['status'])) {
            $this->updateUserStatus($user, $validated['status']);
        }

        return redirect()
            ->route('manajemen-user.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * Update user subscription status
     */
    private function updateUserStatus(User $user, string $status)
    {
        // Deactivate subscription lama
        Subscribe::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        if ($status === 'non_aktif') {
            // Tidak buat subscription baru jika non-aktif
            return;
        }

        // Tentukan apakah trial atau aktif
        $isTrial = ($status === 'trial');
        $trialPackage = Paket::where('nama_paket', 'Trial')->first();
        $activePackage = Paket::where('nama_paket', '!=', 'Trial')
            ->where('is_active', 1)
            ->first() ?? Paket::first();

        $package = $isTrial ? $trialPackage : $activePackage;

        if (!$package) {
            return;
        }

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays($package->durasi_hari ?? 7);

        // Create subscription baru
        Subscribe::create([
            'user_id' => $user->id,
            'paket_id' => $package->id,
            'paket_name' => $package->nama_paket,
            'price' => $package->harga ?? 0,
            'duration_days' => $package->durasi_hari ?? 7,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'approval_status' => 'approved',
            'approved_at' => Carbon::now(),
            'approved_by' => auth()->id(),
            'is_trial' => $isTrial,
        ]);
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Pastikan hanya hapus user dengan role 'owner'
        if ($user->role !== 'owner') {
            abort(403);
        }

        $user->delete();

        return redirect()
            ->route('manajemen-user.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Hapus multiple users (bulk delete)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()
                ->route('manajemen-user.index')
                ->with('error', 'Pilih user yang akan dihapus');
        }

        // Hanya hapus user dengan role 'owner'
        User::whereIn('id', $ids)->where('role', 'owner')->delete();

        return redirect()
            ->route('manajemen-user.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Get subscription details untuk modal
     */
    public function getSubscriptionDetails($user_id)
    {
        try {
            $currentUser = auth()->user();
            Log::info('getSubscriptionDetails accessed', [
                'current_user_id' => $currentUser?->id,
                'current_user_role' => $currentUser?->role,
                'target_user_id' => $user_id,
            ]);
            
            $user = User::findOrFail($user_id);
            
            // Hanya superadmin yang bisa akses endpoint ini untuk manage user lain
            if ($currentUser->role !== 'superadmin') {
                Log::warning('Unauthorized access to getSubscriptionDetails', [
                    'user_role' => $currentUser->role,
                    'user_id' => $currentUser->id,
                ]);
                return response()->json(['error' => 'Unauthorized - Role: ' . $currentUser->role], 403)
                    ->header('Content-Type', 'application/json');
            }

            $subscriptions = Subscribe::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'paket_name' => $sub->paket_name,
                        'price' => $sub->price,
                        'status' => $sub->status,
                        'approval_status' => $sub->approval_status,
                        'is_trial' => $sub->is_trial,
                        'start_date' => $sub->start_date?->format('d-m-Y'),
                        'end_date' => $sub->end_date?->format('d-m-Y'),
                        'bukti_transaksi' => $sub->bukti_transaksi,
                        'bukti_transaksi_url' => $sub->bukti_transaksi ? route('manajemen-user.subscription.download', ['filename' => $sub->bukti_transaksi]) : null,
                        'admin_notes' => $sub->admin_notes,
                        'created_at' => $sub->created_at?->format('d-m-Y H:i'),
                    ];
                });

            return response()->json([
                'success' => true,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'subscriptions' => $subscriptions
            ], 200)->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            Log::error('Error getting subscription details', [
                'user_id' => $user_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Approve subscription
     */
    public function approveSubscription(Request $request, Subscribe $subscription)
    {
        $validated = $request->validate([
            'paket_id' => 'required|integer|exists:paket,id',
            'duration_days' => 'required|integer|min:1|max:3650',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        try {
            // Get paket details
            $paket = Paket::findOrFail($validated['paket_id']);

            // Calculate end date
            $startDate = Carbon::now();
            $durationDays = (int) $validated['duration_days'];
            $endDate = $startDate->copy()->addDays($durationDays);

            // Update subscription
            $subscription->update([
                'paket_id' => (int) $validated['paket_id'],
                'paket_name' => $paket->nama_paket,
                'price' => $paket->harga,
                'duration_days' => $durationDays,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'admin_notes' => $validated['admin_notes'],
                'is_trial' => false,
            ]);

            Log::info('Subscription approved', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'approved_by' => auth()->id(),
            ]);

            // Send approval email to customer
            Mail::to($subscription->user->email)->send(new SubscriptionApproved($subscription));

            return redirect()
                ->route('manajemen-user.index')
                ->with('success', 'Subscription berhasil disetujui dan email notifikasi telah dikirim ke customer.');

        } catch (\Exception $e) {
            Log::error('Error approving subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('manajemen-user.index')
                ->with('error', 'Gagal menyetujui subscription: ' . $e->getMessage());
        }
    }

    /**
     * Reject subscription
     */
    public function rejectSubscription(Request $request, Subscribe $subscription)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        try {
            $subscription->update([
                'approval_status' => 'rejected',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'admin_notes' => $validated['admin_notes'],
                'status' => 'rejected',
            ]);

            Log::info('Subscription rejected', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'rejected_by' => auth()->id(),
            ]);

            return redirect()
                ->route('manajemen-user.index')
                ->with('success', 'Subscription berhasil ditolak');

        } catch (\Exception $e) {
            Log::error('Error rejecting subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('manajemen-user.index')
                ->with('error', 'Gagal menolak subscription: ' . $e->getMessage());
        }
    }

    /**
     * Edit subscription (from user management)
     */
    public function editSubscription(Subscribe $subscription)
    {
        $packages = Paket::where('is_active', true)->get();
        
        return view('superadmin.manajemen-user.edit-subscription', [
            'subscription' => $subscription,
            'packages' => $packages,
            'user' => $subscription->user,
        ]);
    }

    /**
     * Update subscription (pindah paket tanpa menghapus data)
     */
    public function updateSubscription(Request $request, Subscribe $subscription)
    {
        $validated = $request->validate([
            'paket_id' => 'required|exists:paket,id',
            'duration_days' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'admin_notes' => 'nullable|string',
        ]);

        $newPackage = Paket::find($validated['paket_id']);

        // Set subscription lama ke status 'cancelled' (tidak dihapus, hanya di-nonaktifkan)
        $subscription->update([
            'status' => 'cancelled',
            'admin_notes' => $validated['admin_notes'] ?? 'Pindah paket ke ' . $newPackage->nama_paket,
        ]);

        // Buat subscription baru dengan paket baru
        $newSubscription = Subscribe::create([
            'user_id' => $subscription->user_id,
            'paket_id' => $newPackage->id,
            'paket_name' => $newPackage->nama_paket,
            'price' => $newPackage->harga ?? 0,
            'duration_days' => $validated['duration_days'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
            'approval_status' => 'approved',
            'approved_at' => Carbon::now(),
            'approved_by' => auth()->id(),
            'is_trial' => false,
            'admin_notes' => $validated['admin_notes'] ?? 'Pindah paket dari ' . $subscription->paket_name,
        ]);

        return redirect()
            ->route('manajemen-user.index')
            ->with('success', 'Paket berhasil diubah. Data transaksi tetap tersimpan.');
    }

    /**
     * Download bukti transaksi
     */
    public function downloadBukti($filename)
    {
        try {
            $path = 'subscriptions/' . $filename;
            
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'error' => 'File tidak ditemukan'
                ], 404)->header('Content-Type', 'application/json');
            }
            
            return response()->file(
                Storage::disk('public')->path($path),
                [
                    'Content-Type' => Storage::disk('public')->mimeType($path),
                    'Content-Disposition' => 'inline; filename="' . $filename . '"'
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error downloading bukti', [
                'filename' => $filename,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Gagal download file'
            ], 500)->header('Content-Type', 'application/json');
        }
    }
}
