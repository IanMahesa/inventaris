<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\KodeRuang;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data ringkasan
        $totalHarga = Aset::sum('harga');
        $totalBarang = Aset::sum('jumlah_brg');
        $jumlahRuangan = KodeRuang::count();
        $jumlahUser = User::count();

        // Ambil data jumlah barang dan harga per ruang
        $asetPerRuang = \App\Models\Aset::selectRaw('code_ruang, SUM(jumlah_brg) as jumlah_brg, SUM(harga) as harga')
            ->where('is_delete', 0)
            ->whereNotNull('code_ruang')
            ->groupBy('code_ruang')
            ->get();

        $labels = [];
        $data = [];
        foreach ($asetPerRuang as $row) {
            $kodeRuang = \App\Models\KodeRuang::where('code', $row->code_ruang)->first();
            $namaRuang = $kodeRuang ? $kodeRuang->name : 'Ruang Tidak Dikenal';

            // Format: "KODE - Nama Ruang (Rp X)"
            $labels[] = $row->code_ruang . " - " . $namaRuang . " (Rp " . number_format($row->harga, 0, ',', '.') . ")";
            $data[] = (int) $row->jumlah_brg;
        }

        return view('dashboard', compact('totalHarga', 'totalBarang', 'jumlahRuangan', 'jumlahUser', 'labels', 'data'));
    }
}
