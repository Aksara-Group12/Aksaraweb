<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();
        if (!$admin) {
            return back()
                ->withErrors(['email' => 'Admin tidak ditemukan.'])
                ->onlyInput('email');
        }

        if ($admin->status !== 'active') {
            return back()
                ->withErrors(['email' => 'Admin nonaktif. Hubungi owner untuk mengaktifkan akun.'])
                ->onlyInput('email');
        }

        if (!$admin->hasVerifiedEmail()) {
            return back()
                ->withErrors(['email' => 'Email admin belum diverifikasi. Silakan cek email verifikasi.'])
                ->onlyInput('email');
        }

        if (!Hash::check($credentials['password'], $admin->password)) {
            return back()
                ->withErrors(['email' => 'Kredensial yang diberikan tidak cocok.'])
                ->onlyInput('email');
        }

        $owner = User::find($admin->user_id);
        if (!$owner) {
            return back()
                ->withErrors(['email' => 'Owner tidak ditemukan untuk admin ini.'])
                ->onlyInput('email');
        }

        $remember = $request->boolean('remember');
        Auth::login($owner, $remember);
        $request->session()->regenerate();

        $request->session()->put([
            'admin_id' => $admin->id,
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
        ]);

        return redirect()->intended(route('dashboard'));
    }
}
