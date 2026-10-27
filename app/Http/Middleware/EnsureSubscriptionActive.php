<?php

namespace App\Http\Middleware;

use App\Models\Subscribe;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeName = $request->route()?->getName();
        $path = $request->path();

        // Log **SEMUA** request - untuk debug
        \Log::info('=== EnsureSubscriptionActive Middleware ===', [
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'route_name' => $routeName,
            'path' => $path,
            'FULL_URL' => $request->fullUrl(),
            'METHOD' => $request->method(),
        ]);

        if (!$user || $user->role !== 'owner') {
            \Log::info('✓ PASS: Not owner role', ['role' => $user?->role]);
            return $next($request);
        }

        // Wrap dalam try-catch untuk menangkap exception dari Subscribe query
        try {
            $activeSubscription = Subscribe::getActiveSubscription($user->id);
            
            if ($activeSubscription) {
                \Log::info('✓ PASS: Owner has active subscription', [
                    'subscription_id' => $activeSubscription->id,
                    'end_date' => $activeSubscription->end_date,
                ]);
                return $next($request);
            }
        } catch (\Exception $e) {
            // Jika Subscribe query gagal (DB error, query error, dll)
            // LOG error dan ALLOW access (fallback aman)
            \Log::error('🔥 EnsureSubscriptionActive::getActiveSubscription() EXCEPTION', [
                'user_id' => $user->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            // Fallback: Allow access (owner tetap bisa akses fitur meski query gagal)
            // Ini lebih aman daripada membuat 500 error
            \Log::warning('⚠️ Allowing owner access due to subscription query error (fallback)');
            return $next($request);
        }

        $allowedRoutes = [
            'dashboard',
            'subscribe.package',
            'subscribe.store',
            'subscribe.status',
            'subscribe.banks',
            'profile.index',
            'profile.create',
            'profile.store',
            'profile.edit',
            'profile.update',
            'logout',
            'manajemen-user.subscription-details', // API endpoint untuk superadmin
        ];

        $routeName = $request->route()?->getName();

        if ($routeName && in_array($routeName, $allowedRoutes, true)) {
            return $next($request);
        }

        $request->session()->flash('subscription_modal', true);
        return $next($request);
    }
}
