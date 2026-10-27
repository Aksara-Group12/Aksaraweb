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
            background: linear-gradient(135deg, #d9a441 0%, #b8872f 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin: 15px 0;
        }
        .detail-box {
            background-color: #f5f5f5;
            border-left: 4px solid #d9a441;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            background-color: #fef3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 8px 12px;
            border-radius: 4px;
            font-weight: bold;
            margin: 10px 0;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📬 Bukti Pembayaran Diterima</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>Admin Golday</strong>,</p>
            
            <p>Kami menerima bukti pembayaran subscription dari pengguna. Berikut detail yang perlu Anda review:</p>
            
            <div class="detail-box">
                <div class="detail-row">
                    <span class="label">📝 Nama Pengguna:</span>
                    <span class="value">{{ $user->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">📧 Email:</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">📦 Paket:</span>
                    <span class="value">{{ $subscription->paket_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">💰 Nominal:</span>
                    <span class="value">Rp {{ number_format($subscription->price, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">📅 Durasi:</span>
                    <span class="value">{{ $subscription->duration_days }} hari</span>
                </div>
                <div class="detail-row">
                    <span class="label">🏦 Bank:</span>
                    <span class="value">{{ $subscription->bank?->bank_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">📸 Bukti Transaksi:</span>
                    <span class="value">{{ $subscription->bukti_transaksi }}</span>
                </div>
            </div>
            
            <div style="text-align: center;">
                <span class="status-badge">⏳ Menunggu Persetujuan Admin</span>
            </div>
            
            <p><strong>📌 Langkah Selanjutnya:</strong></p>
            <ul>
                <li>Login ke dashboard admin</li>
                <li>Buka halaman <strong>Manajemen User</strong></li>
                <li>Cari user: <strong>{{ $user->name }}</strong></li>
                <li>Review bukti pembayaran dan approve/reject</li>
            </ul>
            
            <p style="color: #666; font-size: 12px;">
                <strong>Note:</strong> Ini adalah notifikasi otomatis, mohon jangan reply ke email ini.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Golday - Sistem Kasir & Stok Logam Mulia. Semua hak dilindungi.</p>
            <p><a href="https://idotech.my.id" style="color: #d9a441; text-decoration: none;">idotech.my.id</a></p>
        </div>
    </div>
</body>
</html>
