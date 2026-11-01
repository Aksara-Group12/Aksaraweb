<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'owner',
                'display_name' => 'Owner',
                'permissions' => ['dashboard', 'produk', 'pos', 'riwayat_penjualan', 'pengeluaran', 'laporan', 'data_master'],
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'permissions' => ['dashboard', 'produk', 'pos', 'pengeluaran'],
                'is_active' => true,
            ],
            [
                'name' => 'kasir',
                'display_name' => 'Kasir',
                'permissions' => ['dashboard', 'pos'],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
