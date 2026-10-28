<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #b8872f 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin: 15px 0;
        }
        .verification-btn {
            display: inline-block;
            background-color: #3b82f6;
            color: black;
            padding: 12px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
        }
        .verification-btn:hover {
            background-color: #b8872f;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .link-text {
            word-break: break-all;
            font-size: 12px;
            color: #666;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di Golday! 🎉</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            
            <p>Terima kasih telah mendaftar di Golday. Untuk menyelesaikan proses pendaftaran dan mengaktifkan akun Anda, silakan verifikasi email dengan mengklik tombol di bawah:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="verification-btn">
                    ✓ Verifikasi Email Saya
                </a>
            </div>
            
            <p>Atau jika tombol tidak berfungsi, salin dan paste tautan berikut di browser Anda:</p>
            
            <div class="link-text">
                {{ $verificationUrl }}
            </div>
            
            <p><strong>⏰ Catatan:</strong> Link verifikasi ini hanya berlaku selama 24 jam.</p>
            
            <p style="margin-top: 30px;">Jika Anda tidak membuat akun ini, abaikan email ini.</p>
            
            <p style="margin-top: 20px;">
                Salam,<br>
                <strong>Tim Golday</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Golday - Sistem Kasir & Stok Logam Mulia. Semua hak dilindungi.</p>
            <p><a href="https://idotech.my.id" style="color: #3b82f6; text-decoration: none;">idotech.my.id</a></p>
        </div>
    </div>
</body>
</html>
