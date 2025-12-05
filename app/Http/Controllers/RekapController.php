<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\Histori;
use App\Models\KodeRuang;
use Carbon\Carbon;

class RekapController extends Controller
{

    public function index()
    {
        // Ambil semua ruangan beserta asetnya yang belum dihapus
        $ruangs = KodeRuang::with(['asets' => function ($q) {
            $q->where('is_delete', 0);
        }])->where('is_delete', 0)
            ->orderBy('code', 'asc')
            ->get();

        return view('rekap.index', compact('ruangs'));
    }

    public function print()
    {
        $tahun_ini = Carbon::now()->year;
        $tahun_lalu = $tahun_ini - 1;

        $ruangs = KodeRuang::where('is_delete', 0)->orderBy('code', 'asc')->get();

        $jumlahTotalTahunLalu = 0;
        $totalHargaTahunLalu = 0;
        $jumlahTotalTahunIni = 0;
        $totalHargaTahunIni = 0;

        foreach ($ruangs as $ruang) {
            // Ambil aset yang diinput pada tahun lalu
            $aset_awal = Aset::where('code_ruang', $ruang->code)
                ->where('is_delete', 0)
                ->whereYear('date', '<=', $tahun_lalu)
                ->get();

            // Ambil histori perpindahan ke ruangan ini pada tahun lalu (PDH = pindah)
            $histori_masuk_lalu = Histori::where('st_histori', 'PDH')
                ->where('is_delete', 0)
                ->where('r_sesudah', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_lalu)
                ->get();

            // Barang yang keluar dari ruangan ini pada tahun lalu
            $histori_keluar_lalu = Histori::where('st_histori', 'PDH')
                ->where('is_delete', 0)
                ->where('r_sebelum', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_lalu)
                ->get();

            // Hitung total barang hingga tahun lalu
            $aset_tahun_lalu = collect();
            $aset_tahun_lalu = $aset_awal->merge($histori_masuk_lalu);

            foreach ($histori_keluar_lalu as $keluar) {
                $aset_tahun_lalu->push((object) [
                    'jumlah_brg' => -1 * $keluar->hjum_brg,
                    'harga'      => -1 * $keluar->hprice,
                    'kondisi'    => $keluar->hkondisi,
                ]);
            }

            // --- Tahun ini ---
            $aset_akhir = Aset::where('code_ruang', $ruang->code)
                ->where('is_delete', 0)
                ->whereYear('date', '<=', $tahun_ini)
                ->get();

            $histori_masuk_ini = Histori::where('st_histori', 'PDH')
                ->where('is_delete', 0)
                ->where('r_sesudah', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_ini)
                ->get();

            $histori_keluar_ini = Histori::where('st_histori', 'PDH')
                ->where('is_delete', 0)
                ->where('r_sebelum', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_ini)
                ->get();

            $aset_tahun_ini = collect();
            $aset_tahun_ini = $aset_akhir->merge($histori_masuk_ini);

            foreach ($histori_keluar_ini as $keluar) {
                $aset_tahun_ini->push((object) [
                    'jumlah_brg' => -1 * $keluar->hjum_brg,
                    'harga'      => -1 * $keluar->hprice,
                    'kondisi'    => $keluar->hkondisi,
                ]);
            }

            // Simpan hasilnya ke model ruang
            $ruang['aset_tahun_lalu'] = $aset_tahun_lalu;
            $ruang['aset_tahun_ini']  = $aset_tahun_ini;

            // Akumulasi total
            $jumlahTotalTahunLalu += $aset_tahun_lalu->sum('jumlah_brg');
            $totalHargaTahunLalu += $aset_tahun_lalu->sum('harga');
            $jumlahTotalTahunIni += $aset_tahun_ini->sum('jumlah_brg');
            $totalHargaTahunIni += $aset_tahun_ini->sum('harga');
        }

        return view('rekap.print', [
            'ruangs' => $ruangs,
            'jumlahTotalTahunLalu' => $jumlahTotalTahunLalu,
            'totalHargaTahunLalu' => $totalHargaTahunLalu,
            'jumlahTotalTahunIni' => $jumlahTotalTahunIni,
            'totalHargaTahunIni' => $totalHargaTahunIni,
        ]);
    }
}
