<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Admin</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        
        <h2 style="color: #020617; margin-bottom: 20px;">Selamat Datang, {{ $admin->name }}! 👋</h2>
        
        <p>Anda telah ditambahkan sebagai <strong>Admin</strong> di {{ config('app.name') }}.</p>
        
        <p style="margin-top: 20px;">Untuk menyelesaikan pendaftaran, silakan verifikasi email Anda dengan mengklik tombol di bawah:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
               style="display: inline-block; padding: 12px 30px; background-color: #0f172a; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Verifikasi Email
            </a>
        </div>
        
        <p style="font-size: 12px; color: #666;">Atau copy link ini ke browser:</p>
        <p style="font-size: 11px; color: #666; word-break: break-all;">{{ $verificationUrl }}</p>
        
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
        
        <div style="background-color: #f5f5f5; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 0; font-size: 13px;"><strong>Informasi Login:</strong></p>
            <p style="margin: 5px 0; font-size: 13px;">📧 Email: <code style="background: #fff; padding: 2px 5px;">{{ $admin->email }}</code></p>
            <p style="margin: 5px 0; font-size: 13px;">🔐 Password: Gunakan password yang Anda buat</p>
        </div>
        
        <p style="font-size: 12px; color: #666;">Link verifikasi akan berlaku selama 24 jam.</p>
        
        <p style="margin-top: 20px; font-size: 12px; color: #999;">
            Jika Anda tidak menjalani perubahan ini, abaikan email ini.
        </p>
        
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
        
        <p style="font-size: 11px; color: #999; text-align: center;">
            © {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.
        </p>
    </div>
</body>
</html>
