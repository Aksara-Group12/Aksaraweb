<!-- Status Berlangganan (Sidebar) + Paket List -->
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Status Berlangganan (Sidebar) -->
    <div style="background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 12px; padding: 20px; height: fit-content;">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 16px; color: #60a5fa;">
            📊 Status Berlangganan
        </h3>

        @if ($activeSubscription)
            <div style="space: 16px;">
                <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Paket Aktif</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #22c55e;">{{ $activeSubscription->paket_name }}</p>
                    <p style="font-size: 0.75rem; color: {{ $activeSubscription->is_trial ? '#2563eb' : '#22c55e' }}; margin-top: 4px; font-weight: 600;">
                        Status: {{ $activeSubscription->is_trial ? 'Trial' : 'Berlangganan' }}
                    </p>
                </div>

                <div style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Tanggal Mulai</p>
                    <p style="font-weight: 600; color: #e0e7ff;">{{ $activeSubscription->start_date->format('d M Y') }}</p>
                </div>

                <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Tanggal Berakhir</p>
                    <p style="font-weight: 600; color: #dc2626;">{{ $activeSubscription->end_date->format('d M Y') }}</p>
                </div>

                <div style="background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Sisa Hari</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #d8b4fe;">
                        {{ $activeSubscription->getDaysRemaining() }}
                        <span style="font-size: 0.75rem;">hari</span>
                    </p>
                </div>

                <button type="button" 
                    style="width: 100%; background: #f97316; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; font-weight: 500; margin-top: 8px;"
                    onclick="switchTab('profil')">
                    ↻ Ganti Paket
                </button>
            </div>
        @else
            <div style="background: rgba(234, 179, 8, 0.15); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: 8px; padding: 12px; text-align: center;">
                <p style="color: #2563eb; font-size: 0.9rem; margin-bottom: 8px;">⚠️ Belum ada paket berlangganan aktif</p>
                <p style="color: #64748b; font-size: 0.8rem;">Pilih salah satu paket di bawah untuk memulai berlangganan</p>
            </div>
        @endif
    </div>

    <!-- Paket List -->
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 16px; color: #fff;">Pilih Paket Berlangganan</h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            @forelse ($packages as $package)
                <div style="background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 12px; overflow: hidden; transition: all 0.3s ease; cursor: pointer;"
                    id="package-{{ $package->id }}"
                    onmouseover="this.style.borderColor='#60a5fa'; this.style.boxShadow='0 0 20px rgba(96, 165, 250, 0.2)';"
                    onmouseout="this.style.borderColor='rgba(148, 163, 184, 0.2)'; this.style.boxShadow='none';">
                    
                    <div style="background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); color: white; padding: 16px;">
                        <h4 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 4px;">{{ $package->nama_paket }}</h4>
                        <p style="font-size: 0.8rem; opacity: 0.9;">{{ $package->deskripsi }}</p>
                    </div>

                    <div style="padding: 16px;">
                        <!-- Harga -->
                        <div style="border-bottom: 1px solid rgba(148, 163, 184, 0.2); padding-bottom: 12px; margin-bottom: 12px;">
                            <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Harga</p>
                        @if ($package->harga_diskon)
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <p style="font-size: 1.25rem; font-weight: 400; color: #64748b; text-decoration: line-through;">Rp {{ number_format($package->harga, 0, ',', '.') }}</p>
                                <p style="font-size: 1.75rem; font-weight: 700; color: #22c55e;">Rp {{ number_format($package->harga_diskon, 0, ',', '.') }}</p>
                            </div>
                            <p style="font-size: 0.7rem; color: #22c55e; margin-top: 4px; font-weight: 600;">✓ Harga Spesial / Promo</p>
                        @else
                            <p style="font-size: 1.75rem; font-weight: 700; color: #60a5fa;">
                                @if ($package->harga == 0)
                                    <span>Gratis</span>
                                @else
                                    Rp {{ number_format($package->harga, 0, ',', '.') }}
                                @endif
                            </p>
                        @endif

                        <!-- Info Durasi -->
                        <div style="background: rgba(59, 130, 246, 0.15); padding: 10px; border-radius: 6px; margin-bottom: 12px;">
                            <p style="font-size: 0.85rem; font-weight: 600; color: #93c5fd;">
                                📅 Durasi: {{ $package->durasi_bulan }} bulan
                            </p>
                        </div>

                        <!-- Button -->
                        <button type="button" 
                            style="width: 100%; background: #60a5fa; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; font-weight: 500; transition: background 0.3s;"
                            onmouseover="this.style.background='#3b82f6';"
                            onmouseout="this.style.background='#60a5fa';"
                            onclick="selectPackage({{ $package->id }}, '{{ $package->nama_paket }}', {{ $package->harga_diskon ?? $package->harga }}, {{ $package->durasi_bulan }})">
                            ✓ Pilih Paket
                        </button>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 40px 20px; background: rgba(30, 41, 59, 0.5); border-radius: 12px;">
                    <p style="color: #64748b;">Belum ada paket tersedia</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div id="paymentModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 50; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #1f293e; border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 12px; width: 100%; max-width: 400px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
        <div style="background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); color: white; padding: 20px; position: sticky; top: 0;">
            <h2 style="font-size: 1.25rem; font-weight: 700;">💳 Konfirmasi Pembayaran</h2>
        </div>

        <form id="paymentForm" enctype="multipart/form-data" style="padding: 24px;">
            @csrf

            <!-- Ringkasan Paket -->
            <div style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(148, 163, 184, 0.2);">
                    <p style="font-size: 0.75rem; color: #64748b;">Paket Dipilih</p>
                    <p style="font-size: 1.125rem; font-weight: 700; color: white;" id="modalPackageName"></p>
                </div>
                <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(148, 163, 184, 0.2);">
                    <p style="font-size: 0.75rem; color: #64748b;">Harga</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #60a5fa;" id="modalPrice"></p>
                </div>
                <div>
                    <p style="font-size: 0.75rem; color: #64748b;">Durasi</p>
                    <p style="font-weight: 600; color: white;" id="modalDuration"></p>
                </div>
            </div>

            <!-- Pilih Bank -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: white; margin-bottom: 8px;">
                    🏦 Pilih Bank
                </label>
                <select name="bank_id" id="bankSelect" style="width: 100%; border: 1px solid rgba(148, 163, 184, 0.3); background: rgba(30, 41, 59, 0.8); color: white; padding: 10px; border-radius: 8px; font-size: 0.9rem;" required>
                    <option value="">-- Pilih Bank --</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}" data-holder="{{ $bank->account_holder }}" data-account="{{ $bank->account_number }}">
                            {{ $bank->bank_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Info Rekening -->
            <div id="bankInfo" style="display: none; background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Atas Nama</p>
                <p style="font-weight: 600; color: white; margin-bottom: 12px;" id="accountHolder"></p>
                <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Nomor Rekening</p>
                <p style="font-family: monospace; font-size: 1.125rem; font-weight: 700; color: #22c55e; margin-bottom: 12px;" id="accountNumber"></p>
                <button type="button" 
                    style="font-size: 0.8rem; background: #22c55e; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;"
                    onclick="copyToClipboard()">
                    📋 Salin Nomor
                </button>
            </div>

            <!-- Upload Bukti Transaksi -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: white; margin-bottom: 8px; text-align: center;">
                    📤 Upload Bukti Transaksi
                </label>
                <div id="dropZone" style="border: 2px dashed rgba(148, 163, 184, 0.3); border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.3s;"
                    onmouseover="this.style.borderColor='#60a5fa'; this.style.background='rgba(96, 165, 250, 0.1)';"
                    onmouseout="this.style.borderColor='rgba(148, 163, 184, 0.3)'; this.style.background='transparent';">
                    <p style="font-size: 2rem; margin-bottom: 8px;">☁️</p>
                    <p style="color: #1e293b; font-weight: 500; margin-bottom: 4px;">Klik atau drag file kesini</p>
                    <p style="font-size: 0.8rem; color: #64748b;">JPG, PNG, atau PDF maksimal 5MB</p>
                    <input type="file" 
                        name="bukti_transaksi" 
                        id="buktiTransaksi" 
                        style="display: none;" 
                        accept="image/jpeg,image/png,application/pdf"
                        required>
                </div>
                <p id="fileInfo" style="font-size: 0.8rem; color: #64748b; margin-top: 8px;"></p>
            </div>

            <!-- Preview Gambar -->
            <div id="imagePreview" style="display: none; margin-bottom: 16px;">
                <img id="previewImage" style="width: 100%; border-radius: 8px; border: 1px solid rgba(148, 163, 184, 0.2);" alt="Preview">
                <button type="button" 
                    style="margin-top: 8px; font-size: 0.8rem; color: #ef4444; background: none; border: none; cursor: pointer;"
                    onclick="clearFile()">
                    🗑️ Hapus File
                </button>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid rgba(148, 163, 184, 0.2);">
                <button type="button" 
                    style="flex: 1; background: #6b7280; color: white; border: none; font-weight: 600; padding: 10px; border-radius: 8px; cursor: pointer;"
                    onclick="closeModal()">
                    ✕ Batal
                </button>
                <button type="submit" 
                    style="flex: 1; background: #60a5fa; color: white; border: none; font-weight: 600; padding: 10px; border-radius: 8px; cursor: pointer;">
                    ✓ Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Select package
    function selectPackage(paketId, packageName, price, duration) {
        document.getElementById('modalPackageName').textContent = packageName;
        document.getElementById('modalPrice').textContent = price == 0 ? 'Gratis' : 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('modalDuration').textContent = duration + ' bulan';

        document.getElementById('paymentForm').dataset.paketId = paketId;
        document.getElementById('paymentForm').dataset.packageName = packageName;
        document.getElementById('paymentForm').dataset.price = price;
        document.getElementById('paymentForm').dataset.duration = duration;

        document.getElementById('paymentModal').style.display = 'flex';
    }

    // Close modal
    function closeModal() {
        document.getElementById('paymentModal').style.display = 'none';
        document.getElementById('paymentForm').reset();
        document.getElementById('bankInfo').style.display = 'none';
        clearFile();
    }

    // Close modal saat klik outside
    document.getElementById('paymentModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Bank selection change
    document.getElementById('bankSelect')?.addEventListener('change', function() {
        if (this.value) {
            const option = this.options[this.selectedIndex];
            document.getElementById('accountHolder').textContent = option.dataset.holder;
            document.getElementById('accountNumber').textContent = option.dataset.account;
            document.getElementById('bankInfo').style.display = 'block';
        } else {
            document.getElementById('bankInfo').style.display = 'none';
        }
    });

    // Drag and drop file
    const dropZone = document.getElementById('dropZone');
    const buktiTransaksi = document.getElementById('buktiTransaksi');

    dropZone?.addEventListener('click', () => buktiTransaksi.click());

    dropZone?.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#60a5fa';
        dropZone.style.background = 'rgba(96, 165, 250, 0.1)';
    });

    dropZone?.addEventListener('dragleave', () => {
        dropZone.style.borderColor = 'rgba(148, 163, 184, 0.3)';
        dropZone.style.background = 'transparent';
    });

    dropZone?.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'rgba(148, 163, 184, 0.3)';
        dropZone.style.background = 'transparent';
        buktiTransaksi.files = e.dataTransfer.files;
        handleFileSelect();
    });

    buktiTransaksi?.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = buktiTransaksi.files[0];
        if (file) {
            document.getElementById('fileInfo').textContent = '✓ ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').style.display = 'none';
            }
        }
    }

    function clearFile() {
        buktiTransaksi.value = '';
        document.getElementById('fileInfo').textContent = '';
        document.getElementById('imagePreview').style.display = 'none';
    }

    // Copy to clipboard
    function copyToClipboard() {
        const text = document.getElementById('accountNumber').textContent;
        navigator.clipboard.writeText(text).then(() => {
            alert('Nomor rekening berhasil disalin!');
        });
    }

    // Form submission
    document.getElementById('paymentForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!buktiTransaksi.files[0]) {
            alert('Pilih bukti transaksi terlebih dahulu');
            return;
        }

        const bankSelect = document.getElementById('bankSelect');
        if (!bankSelect.value) {
            alert('Pilih bank terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('paket_id', this.dataset.paketId);
        formData.append('paket_name', this.dataset.packageName);
        formData.append('price', this.dataset.price);
        formData.append('duration_days', this.dataset.duration * 30); // convert bulan ke hari
        formData.append('bank_id', bankSelect.value);
        formData.append('bukti_transaksi', buktiTransaksi.files[0]);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        console.log('Form data ready, submitting to {{ route('subscribe.store') }}');
        console.log('Paket ID:', this.dataset.paketId);
        console.log('File:', buktiTransaksi.files[0]?.name);

        try {
            const response = await fetch('{{ route('subscribe.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('Response status:', response.status);
            console.log('Response type:', response.type);
            console.log('Content-Type:', response.headers.get('content-type'));

            const responseText = await response.text();
            console.log('Raw response:', responseText);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                alert('Error: Server mengembalikan response yang tidak valid.\n\nBuka Console (F12 > Console) untuk detail.');
                return;
            }

            if (data.success) {
                alert(data.message);
                closeModal();
                location.reload();
            } else {
                if (data.errors) {
                    let errorMsg = 'Data tidak valid:\n';
                    for (const [key, messages] of Object.entries(data.errors)) {
                        errorMsg += '\n' + key + ': ' + messages.join(', ');
                    }
                    alert(errorMsg);
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                }
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan jaringan: ' + error.message + '\n\nBuka Console (F12 > Console) untuk detail.');
        }
    });
</script>
