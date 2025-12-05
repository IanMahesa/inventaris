<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Aset;

class ScanQRController extends Controller
{
    public function index()
    {
        $aset = Aset::all();
        $qr_path = null;
        return view('scanqr.index', compact('aset', 'qr_path'));
    }

    public function submit(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        $this->validate($request, [
            'id_aset' => 'required|id',
            'barang_id' => 'required|string',
            'nama_brg' => 'required|string',
            'periode' => 'required|date',
            'merk' => 'required|string',
            'seri' => 'required|string',
            'bahan' => 'required|string',
            'ukuran' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak Berat',
            'code_ruang' => 'nullable|string|max:20',
            'code_kategori' => 'nullable|string|max:20',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $aset = Aset::findOrFail($id);
        // Ambil data
        $data = [
            'id_aset' => $request->id_aset,
            'barang_id' => $request->barang_id,
            'nama_brg' => $request->nama_brg,
            'periode' => $request->periode,
            'merk' => $request->merk,
            'seri' => $request->seri,
            'bahan' => $request->bahan,
            'ukuran' => $request->ukuran,
            'harga' => $request->harga,
            'kondisi' => $request->kondisi,
            'code_ruang' => $request->code_ruang,
            'code_kategori' => $request->code_kategori,
            'foto' => $request->file('foto')->getClientOriginalName()
        ];

        // Konversi ke JSON
        $jsonData = json_encode($data);

        // Generate nama file QR image
        $qrImageName = 'qr_' . time() . '.jpg';

        // Generate QR Code (isi: data JSON)
        $qr = QrCode::format('jpg')->size(300)->generate($jsonData);

        // Simpan QR Code ke local storage
        Storage::put('public/qr/' . $qrImageName, $qr);

        // Simpan foto ke storage
        $request->file('foto')->storeAs('public/foto', $data['foto']);

        $qr_path = 'storage/qr/' . $qrImageName;

        $aset = Aset::all(); // ← tambahkan ini di submit
        return view('scanqr.index', compact('aset', 'qr_path'));
    }
}
