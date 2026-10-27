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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin: 15px 0;
        }
        .detail-box {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #059669;
        }
        .value {
            color: #333;
            text-align: right;
            font-weight: 500;
        }
        .success-badge {
            display: inline-block;
            background-color: #dcfce7;
            border: 2px solid #10b981;
            color: #059669;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            margin: 15px 0;
            font-size: 16px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .cta-button {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 12px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
        }
        .cta-button:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Selamat! 🎉</h1>
            <p>Subscription Anda Telah Disetujui</p>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            
            <p>Kabar gembira! 🎊 Subscription Anda telah <strong>disetujui oleh admin</strong>. Sekarang Anda dapat mengakses semua fitur premium Golday.</p>
            
            <div class="success-badge">
                ✓ Status: Aktif
            </div>
            
            <div class="detail-box">
                <div class="detail-row">
                    <span class="label">📦 Paket:</span>
                    <span class="value"><strong>{{ $subscription->paket_name }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="label">💰 Nominal:</span>
                    <span class="value">Rp {{ number_format($subscription->price, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">📅 Tanggal Mulai:</span>
                    <span class="value">{{ $subscription->start_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">📅 Tanggal Berakhir:</span>
                    <span class="value">{{ $subscription->end_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">⏳ Durasi:</span>
                    <span class="value">{{ $subscription->duration_days }} hari</span>
                </div>
            </div>
            
            <p><strong>📞 Apa yang bisa Anda lakukan sekarang?</strong></p>
            <ul>
                <li>✅ Akses penuh semua fitur kasir dan manajemen stok</li>
                <li>✅ Buat laporan keuangan lengkap</li>
                <li>✅ Export data transaksi</li>
                <li>✅ Kelola multiple cabang/lokasi</li>
                <li>✅ Dan fitur premium lainnya</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="https://idotech.my.id/dashboard" class="cta-button">
                    🚀 Buka Dashboard Sekarang
                </a>
            </div>
            
            <p style="background-color: #f0fdf4; padding: 15px; border-radius: 4px; border-left: 4px solid #10b981;">
                <strong>💡 Tip:</strong> Simpan email ini sebagai bukti subscription Anda. Jika ada pertanyaan, jangan ragu untuk menghubungi kami!
            </p>
            
            <p style="margin-top: 30px;">
                Terima kasih telah mempercayai Golday! 🙏<br>
                <strong>Tim Golday</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Golday - Sistem Kasir & Stok Logam Mulia. Semua hak dilindungi.</p>
            <p><a href="https://idotech.my.id" style="color: #10b981; text-decoration: none;">idotech.my.id</a></p>
        </div>
    </div>
</body>
</html>
