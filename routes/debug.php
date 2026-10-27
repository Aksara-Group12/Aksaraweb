<?php

// Add this to routes/web.php temporarily for debugging:
// require_once base_path('routes/debug.php');

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/debug-storage', function () {
    $user = auth()->user();
    if (!$user || $user->role !== 'owner') {
        return 'Only owner can debug!';
    }

    $profile = \App\Models\Profile::where('user_id', $user->id)->first();
    
    $debugInfo = [
        '✅ Storage Disk (public)' => [
            'Public Path' => Storage::disk('public')->path(''),
            'Storage Link Exists' => file_exists(public_path('storage')),
            'Public Storage Dir Exists' => is_dir(public_path('storage')),
        ],
        '📁 Profile Data' => [
            'Profile Exists' => !!$profile,
            'Store Name' => $profile?->store_name,
            'Logo Path (DB)' => $profile?->logo_path,
            'Logo File Exists' => $profile?->logo_path ? Storage::disk('public')->exists($profile->logo_path) : false,
        ],
        '🔗 Asset URLs' => [
            'Generated URL' => $profile?->logo_path ? asset('storage/' . $profile->logo_path) : 'No logo path',
            'PHP file_exists' => $profile?->logo_path ? file_exists(Storage::disk('public')->path($profile->logo_path)) : false,
        ],
        '📂 Files in logos folder' => array_slice(Storage::disk('public')->files('logos'), -5) ?: 'Empty',
    ];

    return response()->json($debugInfo, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->name('debug.storage');
