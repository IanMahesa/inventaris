<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Aset;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/aset/{id_aset}', function ($id_aset) {
    $aset = \App\Models\Aset::with(['ruang', 'kategori'])->where('id_aset', $id_aset)->first();
    if (!$aset) {
        return response()->json(['message' => 'Aset tidak ditemukan'], 404);
    }
    return response()->json([
        'id_aset' => $aset->id_aset,
        'barang_id' => $aset->barang_id,
        'nama_brg' => $aset->nama_brg,
        'periode' => $aset->periode ? $aset->periode->format('d-m-Y') : null,
        'merk' => $aset->merk,
        'seri' => $aset->seri,
        'bahan' => $aset->bahan,
        'ukuran' => $aset->ukuran,
        'kondisi' => $aset->kondisi,
        'harga' => $aset->harga,
        'code_ruang' => [
            'code' => $aset->code_ruang,
            'name' => $aset->ruang ? $aset->ruang->name : null,
        ],
        'code_kategori' => [
            'kode' => $aset->code_kategori,
            'nama_brg' => $aset->kategori ? $aset->kategori->nama : null,
        ],
        'keterangan' => $aset->keterangan,
        'foto' => $aset->foto,
    ]);
});
