<!-- Status Berlangganan (Sidebar) + Paket List (Clean Luxury Horizon Theme) -->
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Status Berlangganan (Sidebar) -->
    <div style="background: #FFFFFF; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 18px; padding: 24px; height: fit-content; box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);">
        <h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.25rem; font-weight: 600; margin-bottom: 18px; color: #0F223D;">
            Status Berlangganan
        </h3>

        @if ($activeSubscription)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px;">
                    <p style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 4px;">Paket Aktif</p>
                    <p style="font-size: 1.5rem; font-weight: 800; color: #0F223D; margin: 0;">{{ $activeSubscription->paket_name }}</p>
                    <div style="margin-top: 8px;">
                        <span style="display: inline-flex; align-items: center; gap: 5px; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; background: {{ $activeSubscription->is_trial ? '#EFF6FF' : '#ECFDF5' }}; color: {{ $activeSubscription->is_trial ? '#1D4ED8' : '#065F46' }}; border: 1px solid {{ $activeSubscription->is_trial ? '#BFDBFE' : '#A7F3D0' }};">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $activeSubscription->is_trial ? '#3B82F6' : '#10B981' }};"></span>
                            {{ $activeSubscription->is_trial ? 'Trial 7 Hari' : 'Berlangganan Aktif' }}
                        </span>
                    </div>
                </div>

                <div style="background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px; padding: 12px 14px;">
                    <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 2px;">Tanggal Mulai</p>
                    <p style="font-weight: 600; color: #0F223D; font-size: 0.9rem; margin: 0;">{{ $activeSubscription->start_date->format('d M Y') }}</p>
                </div>

                <div style="background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px; padding: 12px 14px;">
                    <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 2px;">Tanggal Berakhir</p>
                    <p style="font-weight: 600; color: #0F223D; font-size: 0.9rem; margin: 0;">{{ $activeSubscription->end_date->format('d M Y') }}</p>
                </div>

                <div style="background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px; padding: 12px 14px;">
                    <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 2px;">Sisa Durasi</p>
                    <p style="font-size: 1.4rem; font-weight: 800; color: #C5A059; margin: 0;">
                        {{ $activeSubscription->getDaysRemaining() }}
                        <span style="font-size: 0.8rem; font-weight: 600; color: #64748B;">Hari</span>
                    </p>
                </div>

                <button type="button" 
                    style="width: 100%; background: #0F223D; color: #FFFFFF; border: none; padding: 11px 16px; border-radius: 10px; cursor: pointer; font-family: inherit; font-size: 0.85rem; font-weight: 600; margin-top: 6px; box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15); transition: background 0.15s ease;"
                    onmouseover="this.style.background='#162C50'"
                    onmouseout="this.style.background='#0F223D'"
                    onclick="switchTab('profil')">
                    ↻ Ubah Profil Toko
                </button>
            </div>
        @else
            <div style="background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; text-align: center;">
                <p style="color: #0F223D; font-weight: 600; font-size: 0.9rem; margin-bottom: 6px;">Belum ada paket aktif</p>
                <p style="color: #64748B; font-size: 0.8rem; margin: 0;">Pilih salah satu paket di samping untuk berlangganan.</p>
            </div>
        @endif
    </div>

    <!-- Paket List -->
    <div>
        <h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 600; margin-bottom: 18px; color: #0F223D;">
            Pilih Paket Berlangganan
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px;">
            @forelse ($packages as $package)
                <div style="background: #FFFFFF; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 18px; overflow: hidden; transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); display: flex; flex-direction: column; justify-content: space-between;"
                    id="package-{{ $package->id }}"
                    onmouseover="this.style.borderColor='rgba(197, 160, 89, 0.45)'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px -4px rgba(15, 34, 61, 0.08)';"
                    onmouseout="this.style.borderColor='rgba(226, 232, 240, 0.95)'; this.style.transform='none'; this.style.boxShadow='0 4px 16px -2px rgba(15, 34, 61, 0.04)';">
                    
                    <div>
                        <!-- Header Card Paket -->
                        <div style="background: #0F223D; color: #FFFFFF; padding: 20px 22px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
                                <h4 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.25rem; font-weight: 600; color: #FFFFFF; margin: 0;">{{ $package->nama_paket }}</h4>
                            </div>
                            <p style="font-size: 0.8rem; color: #CBD5E1; margin: 0; line-height: 1.4;">{{ $package->deskripsi }}</p>
                        </div>

                        <div style="padding: 20px 22px;">
                            <!-- Harga -->
                            <div style="border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; margin-bottom: 16px;">
                                <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Investasi Biaya</p>
                                @if ($package->harga_diskon)
                                    <div style="display: flex; align-items: baseline; gap: 10px; flex-wrap: wrap;">
                                        <p style="font-size: 1rem; color: #94A3B8; text-decoration: line-through; margin: 0;">Rp {{ number_format($package->harga, 0, ',', '.') }}</p>
                                        <p style="font-size: 1.6rem; font-weight: 800; color: #0F223D; margin: 0;">Rp {{ number_format($package->harga_diskon, 0, ',', '.') }}</p>
                                    </div>
                                    <p style="font-size: 0.72rem; color: #059669; margin-top: 4px; font-weight: 600;">✓ Harga Spesial / Promo</p>
                                @else
                                    <p style="font-size: 1.6rem; font-weight: 800; color: #0F223D; margin: 0;">
                                        @if ($package->harga == 0)
                                            <span>Gratis (Trial)</span>
                                        @else
                                            Rp {{ number_format($package->harga, 0, ',', '.') }}
                                        @endif
                                    </p>
                                @endif
                            </div>

                            <!-- Info Durasi -->
                            <div style="background: #FAF8F5; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 10px; margin-bottom: 18px;">
                                <p style="font-size: 0.825rem; font-weight: 600; color: #0F223D; margin: 0; display: flex; align-items: center; gap: 6px;">
                                    <span>📅</span> Durasi: {{ $package->durasi_bulan == 0 ? '7 Hari' : $package->durasi_bulan . ' Bulan' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="padding: 0 22px 22px;">
                        <button type="button" 
                            style="width: 100%; background: #0F223D; color: #FFFFFF; border: none; padding: 11px 16px; border-radius: 10px; cursor: pointer; font-family: inherit; font-size: 0.85rem; font-weight: 600; transition: all 0.15s ease; box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);"
                            onmouseover="this.style.background='#162C50'; this.style.transform='translateY(-1px)';"
                            onmouseout="this.style.background='#0F223D'; this.style.transform='none';"
                            onclick="selectPackage({{ $package->id }}, '{{ $package->nama_paket }}', {{ $package->harga_diskon ?? $package->harga }}, {{ $package->durasi_bulan }})">
                            Pilih Paket Ini →
                        </button>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 40px 20px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px;">
                    <p style="color: #64748B; font-size: 0.9rem;">Belum ada paket tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Pembayaran (Clean White & Navy Horizon) -->
<div id="paymentModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; width: 100%; max-width: 440px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 30px -10px rgba(15, 34, 61, 0.2);">
        <div style="background: #0F223D; color: white; padding: 20px 24px; position: sticky; top: 0; display: flex; justify-content: space-between; align-items: center; border-radius: 17px 17px 0 0;">
            <h2 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 600; margin: 0; color: #FFFFFF;">Konfirmasi Langganan</h2>
            <button type="button" onclick="closeModal()" style="background: none; border: none; color: #94A3B8; font-size: 1.25rem; cursor: pointer; line-height: 1;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#94A3B8'">✕</button>
        </div>

        <form id="paymentForm" enctype="multipart/form-data" style="padding: 24px;">
            @csrf

            <!-- Ringkasan Paket -->
            <div style="background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: #64748B;">Paket Dipilih:</span>
                    <span style="font-size: 0.95rem; font-weight: 700; color: #0F223D;" id="modalPackageName"></span>
                </div>
                <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: #64748B;">Total Biaya:</span>
                    <span style="font-size: 1.15rem; font-weight: 800; color: #0F223D;" id="modalPrice"></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: #64748B;">Durasi Akses:</span>
                    <span style="font-weight: 600; color: #0F223D; font-size: 0.875rem;" id="modalDuration"></span>
                </div>
            </div>

            <!-- Pilih Bank -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.825rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
                    Pilih Rekening Bank Pembayaran
                </label>
                <select name="bank_id" id="bankSelect" style="width: 100%; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; padding: 10px 12px; border-radius: 10px; font-size: 0.875rem; outline: none;" required>
                    <option value="">-- Pilih Bank --</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}" data-holder="{{ $bank->account_holder }}" data-account="{{ $bank->account_number }}">
                            {{ $bank->bank_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Info Rekening -->
            <div id="bankInfo" style="display: none; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                <p style="font-size: 0.72rem; color: #065F46; font-weight: 600; text-transform: uppercase; margin-bottom: 2px;">Atas Nama Rekening</p>
                <p style="font-weight: 700; color: #065F46; font-size: 0.95rem; margin-bottom: 10px;" id="accountHolder"></p>
                <p style="font-size: 0.72rem; color: #065F46; font-weight: 600; text-transform: uppercase; margin-bottom: 2px;">Nomor Rekening</p>
                <p style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: #065F46; margin-bottom: 10px;" id="accountNumber"></p>
                <button type="button" 
                    style="font-size: 0.785rem; background: #0F223D; color: white; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 600;"
                    onclick="copyToClipboard()">
                    Salin Nomor Rekening
                </button>
            </div>

            <!-- Upload Bukti Transaksi -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.825rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
                    Upload Bukti Transfer
                </label>
                <div id="dropZone" style="border: 2px dashed #CBD5E1; border-radius: 12px; padding: 22px; text-align: center; cursor: pointer; background: #FAF8F5; transition: all 0.2s ease;"
                    onmouseover="this.style.borderColor='#0F223D'; this.style.background='#FFFFFF';"
                    onmouseout="this.style.borderColor='#CBD5E1'; this.style.background='#FAF8F5';">
                    <p style="color: #0F223D; font-weight: 600; font-size: 0.875rem; margin-bottom: 4px;">Klik untuk memilih file bukti transfer</p>
                    <p style="font-size: 0.75rem; color: #64748B; margin: 0;">JPG, PNG, atau PDF (maksimal 5MB)</p>
                    <input type="file" 
                        name="bukti_transaksi" 
                        id="buktiTransaksi" 
                        style="display: none;" 
                        accept="image/jpeg,image/png,application/pdf"
                        required>
                </div>
                <p id="fileInfo" style="font-size: 0.8rem; color: #059669; font-weight: 600; margin-top: 8px;"></p>
            </div>

            <!-- Preview Gambar -->
            <div id="imagePreview" style="display: none; margin-bottom: 16px;">
                <img id="previewImage" style="width: 100%; border-radius: 10px; border: 1px solid #E2E8F0;" alt="Preview">
                <button type="button" 
                    style="margin-top: 6px; font-size: 0.75rem; color: #EF4444; background: none; border: none; cursor: pointer; font-weight: 600;"
                    onclick="clearFile()">
                    Hapus file yang dipilih
                </button>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 10px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
                <button type="button" 
                    style="flex: 1; background: #FFFFFF; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.85rem;"
                    onclick="closeModal()">
                    Batal
                </button>
                <button type="submit" 
                    style="flex: 1; background: #0F223D; color: white; border: none; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.85rem; box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);">
                    Kirim Konfirmasi
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
        document.getElementById('modalDuration').textContent = duration == 0 ? '7 Hari' : duration + ' bulan';

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
        dropZone.style.borderColor = '#0F223D';
        dropZone.style.background = '#FFFFFF';
    });

    dropZone?.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#CBD5E1';
        dropZone.style.background = '#FAF8F5';
    });

    dropZone?.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#CBD5E1';
        dropZone.style.background = '#FAF8F5';
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

        try {
            const response = await fetch('{{ route('subscribe.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const responseText = await response.text();

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                alert('Terjadi kesalahan pada server saat memproses konfirmasi.');
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
            alert('Terjadi kesalahan jaringan: ' + error.message);
        }
    });
</script>
