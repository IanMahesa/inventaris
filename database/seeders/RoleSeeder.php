<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Hapus cache permission (wajib kalau kamu mengubah permission-role)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'dashboard-view',
            'aset-list',
            'aset-create',
            'aset-edit',
            'aset-delete',
            'aset-view',
            'aset-qrcode',
            'aset-download',
            'histori-list',
            'histori-create',
            'histori-edit',
            'histori-delete',
            'histori-view',
            'histori-print',
            'kategori-list',
            'kategori-create',
            'kategori-edit',
            'kategori-delete',
            'kategori-view',
            'ruang-list',
            'ruang-create',
            'ruang-edit',
            'ruang-delete',
            'ruang-view',
            'rekap-view',
            'rekap-print',
            'opruang-view',
            'opruang-print',
            'scanqr-view',
            'scanqr-create',
            'webcam-capture',
            'opnamhistori-view',
            'brglelang-view',
            'user-list',
            'user-view'
        ]);

        // Operator
        $operator = Role::firstOrCreate(['name' => 'Operator', 'guard_name' => 'web']);
        $operator->syncPermissions([
            'dashboard-view',
            'aset-list',
            'aset-create',
            'aset-edit',
            'aset-view',
            'aset-qrcode',
            'aset-download',
            'histori-list',
            'histori-create',
            'histori-edit',
            'histori-view',
            'histori-print',
            'kategori-list',
            'kategori-view',
            'ruang-list',
            'ruang-view',
            'scanqr-view',
            'scanqr-create',
            'webcam-capture',
            'opnamhistori-view',
            'brglelang-view'
        ]);

        // Viewer
        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions([
            'dashboard-view',
            'aset-list',
            'aset-view',
            'histori-list',
            'histori-view',
            'kategori-list',
            'kategori-view',
            'ruang-list',
            'ruang-view',
            'scanqr-view',
            'opnamhistori-view',
            'brglelang-view'
        ]);

        // Auditor
        $auditor = Role::firstOrCreate(['name' => 'Auditor', 'guard_name' => 'web']);
        $auditor->syncPermissions([
            'dashboard-view',
            'aset-list',
            'aset-view',
            'histori-list',
            'histori-view',
            'kategori-list',
            'kategori-view',
            'ruang-list',
            'ruang-view',
            'rekap-view',
            'rekap-print',
            'opruang-view',
            'opruang-print',
            'opnamhistori-view',
            'brglelang-view'
        ]);
    }
}
