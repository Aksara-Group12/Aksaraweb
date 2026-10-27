<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\Bank;
use App\Models\Paket;
use App\Models\User;
use App\Mail\PaymentProofNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscribeController extends Controller
{
    public function __construct()
    {
        // Middleware untuk owner role bisa di definisikan di route
    }

    /**
     * Show subscription page with active subscription status
     */
    public function package()
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Hanya owner yang dapat mengakses fitur berlangganan');
        }

        $packages = Paket::where('is_active', true)->get();
        $activeSubscription = Subscribe::getActiveSubscription(auth()->id());
        $banks = Bank::all();

        return view('subscribe.package', [
            'packages' => $packages,
            'activeSubscription' => $activeSubscription,
            'banks' => $banks,
        ]);
    }

    /**
     * Store new subscription
     */
    public function store(Request $request)
    {
        // Validasi role owner
        if (!auth()->check() || auth()->user()->role !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat menggunakan fitur ini'
            ], 403)->header('Content-Type', 'application/json');
        }

        try {
            Log::info('Subscribe store called', [
                'user_id' => auth()->id(),
                'request_data' => $request->except('bukti_transaksi')
            ]);

            $validated = $request->validate([
                'paket_id' => 'required|integer|exists:paket,id',
                'paket_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1|max:3650',
                'bank_id' => 'required|integer|exists:banks,id',
                'bukti_transaksi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            Log::info('Validation passed', $validated);

            // Upload bukti transaksi
            $file = $request->file('bukti_transaksi');
            $filename = auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $uploaded = Storage::disk('public')->putFileAs('subscriptions', $file, $filename);
            
            if (!$uploaded) {
                Log::error('File upload failed', ['filename' => $filename]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload file. Hubungi admin.',
                ], 500)->header('Content-Type', 'application/json');
            }

            Log::info('File uploaded', ['filename' => $filename]);

            // Create subscription
            $startDate = Carbon::now();
            $durationDays = (int) $validated['duration_days'];
            $endDate = $startDate->copy()->addDays($durationDays);

            $subscription = Subscribe::create([
                'user_id' => auth()->id(),
                'paket_id' => (int) $validated['paket_id'],
                'paket_name' => $validated['paket_name'],
                'price' => (float) $validated['price'],
                'duration_days' => $durationDays,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'bank_id' => $validated['bank_id'],
                'bukti_transaksi' => $filename,
                'status' => 'pending',
            ]);

            Log::info('Subscription created', ['subscription_id' => $subscription->id]);

            // Send notification email to admin
            $adminEmail = config('mail.from.address', 'firadodo0507@gmail.com');
            Mail::to($adminEmail)->send(new PaymentProofNotification($subscription));

            return response()->json([
                'success' => true,
                'message' => 'Berlangganan berhasil! Admin akan mengkonfirmasi dalam waktu singkat.',
                'subscription_id' => $subscription->id,
            ], 200)->header('Content-Type', 'application/json');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422)->header('Content-Type', 'application/json');

        } catch (\Exception $th) {
            Log::error('Subscribe error', [
                'user_id' => auth()->id(),
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage(),
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Get subscription status
     */
    public function getStatus()
    {
        if (auth()->user()->role !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $subscription = Subscribe::getActiveSubscription(auth()->id());

        if (!$subscription) {
            return response()->json([
                'has_subscription' => false,
                'message' => 'Anda belum berlangganan',
            ]);
        }

        return response()->json([
            'has_subscription' => true,
            'paket_name' => $subscription->paket_name,
            'start_date' => $subscription->start_date->format('d M Y'),
            'end_date' => $subscription->end_date->format('d M Y'),
            'days_remaining' => $subscription->getDaysRemaining(),
            'status' => $subscription->status,
            'is_trial' => $subscription->is_trial,
            'status_label' => $subscription->is_trial ? 'trial' : $subscription->status,
        ]);
    }

    /**
     * Get available banks for dropdown
     */
    public function getBanks()
    {
        if (auth()->user()->role !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json(Bank::all());
    }
}
