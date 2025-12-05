<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\ScanQRController;
use App\Http\Controllers\WebcamController;
use App\Http\Controllers\HistoriController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\OpRuangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard-view');

    // User Management - Super Admin only
    Route::middleware('permission:user-list')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Role Management - Super Admin only
    Route::middleware('permission:role-list')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Resource utama di luar prefix/group
    Route::resource('kategori', KategoriController::class)->middleware([
        'permission:kategori-list|kategori-create|kategori-edit|kategori-delete|kategori-view'
    ]);
    Route::get('/aset/print', [AsetController::class, 'print'])->name('aset.print')->middleware('permission:aset-print');
    Route::get('/aset/printjenis', [AsetController::class, 'printJenis'])->name('aset.printjenis')->middleware('permission:aset-print');
    Route::get('/aset/printqrcodeall', [AsetController::class, 'printQrcodeall'])->name('aset.printqrcodeall')->middleware('permission:aset-print');
    Route::get('/aset/cetakqrcode/{id_aset?}', [AsetController::class, 'cetakQrcode'])->name('aset.cetakqrcode')->middleware('permission:aset-print');
    Route::get('/aset/import', [AsetController::class, 'importForm'])->name('aset.import.form')->middleware('permission:aset-create');
    Route::post('/aset/import', [AsetController::class, 'import'])->name('aset.import')->middleware('permission:aset-create');
    Route::get('/histori/print', [HistoriController::class, 'print'])->name('histori.print')->middleware('permission:histori-print');
    Route::get('/histori/print/{status}', [HistoriController::class, 'printByStatus'])->name('histori.print.status')->middleware('permission:histori-print');
    Route::resource('aset', AsetController::class)->middleware([
        'permission:aset-list|aset-create|aset-edit|aset-delete|aset-view'
    ]);
    Route::resource('scanqr', ScanQRController::class)->middleware([
        'permission:scanqr-view|scanqr-create'
    ]);
    Route::resource('histori', HistoriController::class)->middleware([
        'permission:histori-list|histori-create|histori-edit|histori-delete|histori-view'
    ]);
    Route::resource('ruang', RuangController::class)->middleware([
        'permission:ruang-list|ruang-create|ruang-edit|ruang-delete|ruang-view'
    ]);

    // Tambahkan route QRCode aset di luar prefix/group
    Route::get('/aset/{id}/qrcode', [AsetController::class, 'generateQrCode'])
        ->name('aset.qrcode')
        ->middleware('permission:aset-qrcode');
    Route::get('/aset/{id}/download-qr', [AsetController::class, 'downloadQr'])
        ->name('aset.qrcode.download')
        ->middleware('permission:aset-download');

    Route::get('/aset/{id}/qrcode', [AsetController::class, 'generateQrCode'])
        ->name('aset.qrcode')
        ->middleware('permission:aset-qrcode');
    Route::get('/aset/{id}/download-qr', [AsetController::class, 'downloadQr'])
        ->name('aset.qrcode.download')
        ->middleware('permission:aset-download');
    // Komentari resource yang sama di dalam prefix/group
    // Route::prefix('ruang')->group(function () {
    //     Route::get('/', [RuangController::class, 'index'])->name('ruang.index')->middleware('permission:ruang-list');
    //     Route::get('/create', [RuangController::class, 'create'])->name('ruang.create')->middleware('permission:ruang-create');
    //     Route::post('/', [RuangController::class, 'store'])->name('ruang.store')->middleware('permission:ruang-create');
    //     Route::get('/{ruang}/edit', [RuangController::class, 'edit'])->name('ruang.edit')->middleware('permission:ruang-edit');
    //     Route::put('/{ruang}', [RuangController::class, 'update'])->name('ruang.update')->middleware('permission:ruang-edit');
    //     Route::delete('/{ruang}', [RuangController::class, 'destroy'])->name('ruang.destroy')->middleware('permission:ruang-delete');
    //     Route::get('/{ruang}', [RuangController::class, 'show'])->name('ruang.show')->middleware('permission:ruang-view');

    // Extra: memindahkan akun (set parent)
    // Route::post('/move', [RuangController::class, 'move'])->name('ruang.move');

    // Kategori Management
    // Route::prefix('kategori')->group(function () {
    //     Route::get('/', [KategoriController::class, 'index'])->name('kategori.index')->middleware('permission:kategori-list');
    //     Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create')->middleware('permission:kategori-create');
    //     Route::post('/', [KategoriController::class, 'store'])->name('kategori.store')->middleware('permission:kategori-create');
    //     Route::get('/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit')->middleware('permission:kategori-edit');
    //     Route::put('/{kategori}', [KategoriController::class, 'update'])->name('kategori.update')->middleware('permission:kategori-edit');
    //     Route::delete('/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy')->middleware('permission:kategori-delete');
    //     Route::get('/{kategori}', [KategoriController::class, 'show'])->name('kategori.show')->middleware('permission:kategori-view');

    // Aset Management
    // Route::prefix('aset')->group(function () {
    //     Route::get('/', [AsetController::class, 'index'])->name('aset.index')->middleware('permission:aset-list');
    //     Route::get('/create', [AsetController::class, 'create'])->name('aset.create')->middleware('permission:aset-create');
    //     Route::post('/', [AsetController::class, 'store'])->name('aset.store')->middleware('permission:aset-create');
    //     Route::get('/{aset}/edit', [AsetController::class, 'edit'])->name('aset.edit')->middleware('permission:aset-edit');
    //     Route::put('/{aset}', [AsetController::class, 'update'])->name('aset.update')->middleware('permission:aset-edit');
    //     Route::delete('/{aset}', [AsetController::class, 'destroy'])->name('aset.destroy')->middleware('permission:aset-delete');
    //     Route::get('/{aset}', [AsetController::class, 'show'])->name('aset.show')->middleware('permission:aset-view');

    // QR Code routes
    // Route::get('/{id}/qrcode', [AsetController::class, 'generateQrCode'])->name('aset.qrcode')->middleware('permission:aset-qrcode');
    // Route::get('/{id}/download-qr', [AsetController::class, 'downloadQr'])->name('aset.qrcode.download')->middleware('permission:aset-download');

    // Scan QR Management
    // Route::prefix('scaqr')->group(function () {
    //     Route::get('/', [ScanQRController::class, 'index'])->name('scanqr.index')->middleware('permission:scanqr-view');
    //     Route::post('/submit/{id}', [ScanQRController::class, 'submit'])->name('scanqr.submit')->middleware('permission:scanqr-create');

    // Histori Management
    // Route::prefix('histori')->group(function () {
    //     Route::get('/', [HistoriController::class, 'index'])->name('histori.index')->middleware('permission:histori-list');
    //     Route::get('/create', [HistoriController::class, 'create'])->name('histori.create')->middleware('permission:histori-create');
    //     Route::post('/', [HistoriController::class, 'store'])->name('histori.store')->middleware('permission:histori-create');
    //     Route::get('/histori/edit-aset/{id_aset}', [HistoriController::class, 'editByAset'])->name('histori.editByAset')->middleware('permission:histori-edit');
    //     Route::put('/{histori}', [HistoriController::class, 'update'])->name('histori.update')->middleware('permission:histori-edit');
    //     Route::delete('/{histori}', [HistoriController::class, 'destroy'])->name('histori.destroy')->middleware('permission:histori-delete');
    //     Route::get('/{histori}', [HistoriController::class, 'show'])->name('histori.show')->middleware('permission:histori-view');

    // QR Code routes for histori
    // Route::get('/qrcode/{id}', [HistoriController::class, 'qrcode'])->name('histori.qrcode')->middleware('permission:aset-qrcode');
    // Route::get('/qrcode/{id}/download', [HistoriController::class, 'downloadQrcode'])->name('histori.qrcode.download')->middleware('permission:aset-download');
    // });
    // });
    // });
    // });
});

// Webcam capture
Route::post('webcam-capture', [WebcamController::class, 'store'])->name('webcam.capture')->middleware('permission:webcam-capture');

// Aset histori routes
Route::get('/aset/histori/edit/{id}', [AsetController::class, 'editHistori'])->name('aset.histori.edit')->middleware('permission:histori-edit');
Route::post('/aset/histori/update/{id}', [AsetController::class, 'updateHistori'])->name('aset.histori.update')->middleware('permission:histori-edit');
Route::get('/aset/histori/editlelang/{id}', [AsetController::class, 'editHistoriLlg'])->name('aset.histori.editlelang')->middleware('permission:histori-edit');
Route::post('/aset/histori/updatelelang/{id}', [AsetController::class, 'updateHistoriLlg'])->name('aset.histori.updatelelang')->middleware('permission:histori-edit');

// Laporan & Rekap
Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index')->middleware('permission:rekap-view');
Route::get('/rekap/print', [RekapController::class, 'print'])->name('rekap.print')->middleware('permission:rekap-print');

Route::get('/opruang', [OpRuangController::class, 'index'])->name('opruang.index')->middleware('permission:opruang-view');
Route::get('/opruang/print', [OpRuangController::class, 'print'])->name('opruang.print')->middleware('permission:opruang-print');

Route::get('/opnamhistori', [AsetController::class, 'opnamHistoriIndex'])->name('opnamhistori.index')->middleware('permission:opnamhistori-view');
Route::get('/brglelang', [AsetController::class, 'lelangHistoriIndex'])->name('brglelang.index')->middleware('permission:brglelang-view');
