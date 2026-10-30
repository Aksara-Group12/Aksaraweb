<?php

namespace Database\Seeders;

use App\Models\KategoriPengeluaran;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriPengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultKategoris = [
            'Pembelian barang dagang / stok',
            'Pembelian bahan baku',
            'Bahan kemasan (plastik, box, label, kantong)',
            'Sewa tempat',
            'Gaji karyawan',
            'Listrik',
            'Air',
            'Internet dan telepon',
            'Gas',
            'Iuran kebersihan dan keamanan',
        ];

        // Ambil semua user dengan role owner
        $owners = User::where('role', 'owner')->get();

        foreach ($owners as $owner) {
            foreach ($defaultKategoris as $kategoriName) {
                // Cek apakah kategori sudah ada untuk user ini
                $existing = KategoriPengeluaran::where('user_id', $owner->id)
                    ->where('name', $kategoriName)
                    ->first();

                // Jika belum ada, buat baru
                if (!$existing) {
                    KategoriPengeluaran::create([
                        'name' => $kategoriName,
                        'user_id' => $owner->id,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
