@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Tab Navigation -->
    <div class="mb-8">
        <div class="flex border-b border-gray-200 gap-8">
            <button type="button" 
                id="tab-profil" 
                class="tab-button pb-4 px-1 text-lg font-medium border-b-2 border-transparent hover:border-blue-500 transition"
                onclick="switchTab('profil')">
                <i class="fas fa-user mr-2"></i>Profil
            </button>
            <button type="button" 
                id="tab-berlangganan" 
                class="tab-button pb-4 px-1 text-lg font-medium border-b-2 border-transparent hover:border-blue-500 transition active"
                onclick="switchTab('berlangganan')">
                <i class="fas fa-credit-card mr-2"></i>Berlangganan
            </button>
        </div>
    </div>

    <!-- Tab Content: Profil -->
    <div id="content-profil" class="tab-content hidden">
        @include('profile.index')
    </div>

    <!-- Tab Content: Berlangganan -->
    <div id="content-berlangganan" class="tab-content">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Status Berlangganan (Sidebar) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-20">
                    <h3 class="text-xl font-bold mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>Status Berlangganan
                    </h3>

                    @if ($activeSubscription)
                        <div class="space-y-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Paket Aktif</p>
                                <p class="text-2xl font-bold text-green-600">{{ $activeSubscription->paket_name }}</p>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Tanggal Mulai</p>
                                <p class="text-lg font-semibold">{{ $activeSubscription->start_date->format('d M Y') }}</p>
                            </div>

                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Tanggal Berakhir</p>
                                <p class="text-lg font-semibold">{{ $activeSubscription->end_date->format('d M Y') }}</p>
                            </div>

                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Sisa Hari</p>
                                <p class="text-2xl font-bold text-purple-600">
                                    {{ $activeSubscription->getDaysRemaining() }} 
                                    <span class="text-sm">hari</span>
                                </p>
                            </div>

                            <button type="button" 
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition"
                                onclick="switchPackage()">
                                <i class="fas fa-exchange-alt mr-2"></i>Ganti Paket
                            </button>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <p class="text-gray-700 mb-4">Anda belum memiliki paket berlangganan aktif</p>
                            <p class="text-sm text-gray-600">Pilih salah satu paket di bawah untuk memulai berlangganan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Paket List -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold mb-6">Pilih Paket Berlangganan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse ($packages as $package)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1"
                            id="package-{{ $package->id }}">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                                <h3 class="text-2xl font-bold mb-2">{{ $package->nama_paket }}</h3>
                                <p class="text-sm opacity-90">{{ $package->deskripsi }}</p>
                            </div>

                            <div class="p-6 space-y-4">
                                <!-- Harga -->
                                <div class="border-b pb-4">
                                    <p class="text-sm text-gray-600 mb-1">Harga</p>
                                    @if ($package->harga_diskon)
                                        <div class="flex items-center gap-3">
                                            <p class="text-base font-normal text-gray-500 line-through">Rp {{ number_format($package->harga, 0, ',', '.') }}</p>
                                            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($package->harga_diskon, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="text-xs text-green-600 font-semibold mt-1">✓ Harga Spesial / Promo</p>
                                    @else
                                        <p class="text-3xl font-bold text-blue-600">
                                            @if ($package->harga == 0)
                                                <span>Gratis</span>
                                            @else
                                                Rp {{ number_format($package->harga, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">per {{ $package->durasi_bulan }} bulan</p>
                                </div>

                                <!-- Info Durasi -->
                                <div class="bg-blue-50 p-3 rounded">
                                    <p class="text-sm font-semibold text-blue-900">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Durasi: {{ $package->durasi_bulan }} bulan
                                    </p>
                                </div>

                                <!-- Button -->
                                <button type="button" 
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition"
                                    onclick="selectPackage({{ $package->id }}, '{{ $package->nama_paket }}', {{ $package->harga_diskon ?? $package->harga }}, {{ $package->durasi_bulan }})">
                                    <i class="fas fa-check-circle mr-2"></i>Pilih Paket
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">Belum ada paket tersedia</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 sticky top-0">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-credit-card mr-2"></i>Konfirmasi Pembayaran
            </h2>
        </div>

        <form id="paymentForm" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Ringkasan Paket -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="mb-3 pb-3 border-b">
                    <p class="text-sm text-gray-600">Paket Dipilih</p>
                    <p class="text-lg font-bold" id="modalPackageName"></p>
                </div>
                <div class="mb-3 pb-3 border-b">
                    <p class="text-sm text-gray-600">Harga</p>
                    <p class="text-2xl font-bold text-blue-600" id="modalPrice"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Durasi</p>
                    <p class="font-semibold" id="modalDuration"></p>
                </div>
            </div>

            <!-- Pilih Bank -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-university mr-2 text-blue-500"></i>Pilih Bank
                </label>
                <select name="bank_id" id="bankSelect" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Bank --</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}" data-holder="{{ $bank->account_holder }}" data-account="{{ $bank->account_number }}">
                            {{ $bank->bank_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Info Rekening -->
            <div id="bankInfo" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Atas Nama</p>
                <p class="font-semibold mb-3" id="accountHolder"></p>
                <p class="text-sm text-gray-600 mb-1">Nomor Rekening</p>
                <p class="font-mono text-lg font-bold text-green-600 mb-3" id="accountNumber"></p>
                <button type="button" 
                    class="text-sm bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition"
                    onclick="copyToClipboard()">
                    <i class="fas fa-copy mr-1"></i>Salin Nomor Rekening
                </button>
            </div>

            <!-- Upload Bukti Transaksi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-file-upload mr-2 text-blue-500"></i>Upload Bukti Transaksi
                </label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600 font-semibold mb-1">Klik atau drag file kesini</p>
                    <p class="text-sm text-gray-500">JPG, PNG, atau PDF maksimal 5MB</p>
                    <input type="file" 
                        name="bukti_transaksi" 
                        id="buktiTransaksi" 
                        class="hidden" 
                        accept="image/jpeg,image/png,application/pdf"
                        required>
                </div>
                <p id="fileInfo" class="text-sm text-gray-600 mt-2"></p>
            </div>

            <!-- Preview Gambar -->
            <div id="imagePreview" class="hidden">
                <img id="previewImage" class="w-full rounded-lg border border-gray-300" alt="Preview">
                <button type="button" 
                    class="mt-2 text-sm text-red-500 hover:text-red-700"
                    onclick="clearFile()">
                    <i class="fas fa-trash mr-1"></i>Hapus File
                </button>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="button" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                    onclick="closeModal()">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" 
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-check-circle mr-2"></i>Konfirmasi Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Tab switching
    function switchTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(el => {
            el.classList.remove('border-b-2', 'border-blue-500', 'active');
            el.classList.add('border-transparent');
        });

        // Show selected tab
        document.getElementById('content-' + tab).classList.remove('hidden');
        document.getElementById('tab-' + tab).classList.add('border-b-2', 'border-blue-500', 'active');
    }

    // Select package
    function selectPackage(paketId, packageName, price, duration) {
        document.getElementById('modalPackageName').textContent = packageName;
        document.getElementById('modalPrice').textContent = price == 0 ? 'Gratis' : 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('modalDuration').textContent = duration + ' bulan';

        document.getElementById('paymentForm').dataset.paketId = paketId;
        document.getElementById('paymentForm').dataset.packageName = packageName;
        document.getElementById('paymentForm').dataset.price = price;
        document.getElementById('paymentForm').dataset.duration = duration;

        document.getElementById('paymentModal').classList.remove('hidden');
    }

    // Close modal
    function closeModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentForm').reset();
        document.getElementById('bankInfo').classList.add('hidden');
        clearFile();
    }

    // Bank selection change
    document.getElementById('bankSelect')?.addEventListener('change', function() {
        if (this.value) {
            const option = this.options[this.selectedIndex];
            document.getElementById('accountHolder').textContent = option.dataset.holder;
            document.getElementById('accountNumber').textContent = option.dataset.account;
            document.getElementById('bankInfo').classList.remove('hidden');
        } else {
            document.getElementById('bankInfo').classList.add('hidden');
        }
    });

    // Drag and drop file
    const dropZone = document.getElementById('dropZone');
    const buktiTransaksi = document.getElementById('buktiTransaksi');

    dropZone?.addEventListener('click', () => buktiTransaksi.click());

    dropZone?.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone?.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone?.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
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
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').classList.add('hidden');
            }
        }
    }

    function clearFile() {
        buktiTransaksi.value = '';
        document.getElementById('fileInfo').textContent = '';
        document.getElementById('imagePreview').classList.add('hidden');
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

        const formData = new FormData();
        formData.append('paket_id', this.dataset.paketId);
        formData.append('paket_name', this.dataset.packageName);
        formData.append('price', this.dataset.price);
        formData.append('duration_days', this.dataset.duration * 30); // convert bulan ke hari
        formData.append('bank_id', document.getElementById('bankSelect').value);
        formData.append('bukti_transaksi', buktiTransaksi.files[0]);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        try {
            const response = await fetch('{{ route('subscribe.store') }}', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                closeModal();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data');
        }
    });

    function switchPackage() {
        alert('Fitur ganti paket akan diimplementasikan');
    }
</script>
@endsection

@endsection
