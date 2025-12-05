<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'dashboard-view',

            // Role Management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'role-view',

            // User Management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'user-view',

            // Ruang Management
            'ruang-list',
            'ruang-create',
            'ruang-edit',
            'ruang-delete',
            'ruang-view',

            // Kategori Management
            'kategori-list',
            'kategori-create',
            'kategori-edit',
            'kategori-delete',
            'kategori-view',

            // Asset Management
            'aset-list',
            'aset-create',
            'aset-edit',
            'aset-delete',
            'aset-view',
            'aset-qrcode',
            'aset-download',

            // Histori Management
            'histori-list',
            'histori-create',
            'histori-edit',
            'histori-delete',
            'histori-view',
            'histori-print',

            // Laporan & Rekap
            'rekap-view',
            'rekap-print',
            'opruang-view',
            'opruang-print',

            // QR Code & Scan
            'scanqr-view',
            'scanqr-create',
            'webcam-capture',

            // Opname Histori & Barang Lelang
            'opnamhistori-view',
            'brglelang-view'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
