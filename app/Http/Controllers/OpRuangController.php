<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\Histori;
use App\Models\KodeRuang;
use Carbon\Carbon;

class OpRuangController extends Controller
{
    public function index(Request $request)
    {
        $ruangs = KodeRuang::where('is_delete', 0)->orderBy('code')->get();

        if ($request->filled('ruang_id')) {
            // Ambil ruang berdasarkan kode
            $ruang = KodeRuang::where('code', $request->ruang_id)->where('is_delete', 0)->first();

            if (!$ruang) {
                abort(404); // Jika ruang tidak ditemukan
            }

            // Query aset aktif di ruang tersebut
            $query = Aset::with('ruang')
                ->where('is_delete', 0)
                ->whereIn('st_aset', ['BL', 'HBH', 'PDH'])
                ->where('code_ruang', $ruang->code);

            // Tambahkan filter pencarian jika ada
            if ($request->filled('search')) {
                $query->where('nama_brg', 'like', '%' . $request->search . '%');
            }

            $result = $query->paginate(10)->withQueryString();

            // Jika tidak ada aset, tampilkan dummy
            if ($result->isEmpty()) {
                $asets = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([(object) [
                        'id' => 'dummy_' . $ruang->code,
                        'nama_brg' => '-',
                        'barang_id' => '-',
                        'periode' => now(),
                        'jumlah_brg' => 0,
                        'harga' => 0,
                        'kondisi' => '-',
                        'kondisi_baik' => 0,
                        'kondisi_kurang' => 0,
                        'kondisi_rusak' => 0,
                        'ruang' => $ruang,
                    ]]),
                    1, // total item
                    10, // per halaman
                    $request->get('page', 1),
                    ['path' => $request->url(), 'query' => $request->query()]
                );
            } else {
                $asets = $result;
            }
        } else {
            // Jika tidak ada filter ruang, tampilkan semua ruang + aset aktif (atau dummy jika kosong)
            $asets = collect();

            foreach ($ruangs as $ruang) {
                $ruangAsets = Aset::with('ruang')
                    ->where('is_delete', 0)
                    ->whereIn('st_aset', ['BL', 'HBH', 'PDH'])
                    ->where('code_ruang', $ruang->code)
                    ->get();

                if ($ruangAsets->isNotEmpty()) {
                    $asets = $asets->merge($ruangAsets);
                } else {
                    $asets->push((object) [
                        'id' => 'dummy_' . $ruang->code,
                        'nama_brg' => '-',
                        'barang_id' => '-',
                        'periode' => null,
                        'jumlah_brg' => 0,
                        'harga' => 0,
                        'kondisi' => '-',
                        'kondisi_baik' => 0,
                        'kondisi_kurang' => 0,
                        'kondisi_rusak' => 0,
                        'ruang' => $ruang,
                    ]);
                }
            }

            // Manual pagination
            $perPage = 10;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;

            $paginatedAsets = $asets->slice($offset, $perPage);

            $asets = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedAsets,
                $asets->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return view('opruang.index', compact('asets', 'ruangs'));
    }


    public function print(Request $request)
    {
        $tahun_ini  = now()->year;
        $tahun_lalu = $tahun_ini - 1;

        // Ambil hanya ruang yang dipilih (jika ada), selain itu ambil semua
        $filterRuangs = $request->input('ruangs', []);
        $ruangs = KodeRuang::when(!empty($filterRuangs), function ($query) use ($filterRuangs) {
            return $query->whereIn('code', $filterRuangs);
        })->where('is_delete', 0)->get();
        $kodeRuangMap = $ruangs->pluck('name', 'code')->toArray();

        // Ambil semua aset untuk semua ruang sekaligus
        $allAsets = Aset::with('ruang')
            ->where('is_delete', 0)
            ->whereIn('st_aset', ['BL', 'HBH', 'PDH'])
            ->get();

        // Ambil semua histori pindah untuk semua aset sekaligus
        $allHistoriPindah = Histori::where('st_histori', 'PDH')
            ->where('is_delete', 0)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy('id_asetsblm');

        // Ambil histori untuk tahun lalu dan tahun ini sekaligus
        $historiMasukLalu = Histori::where('st_histori', 'PDH')
            ->where('is_delete', 0)
            ->where('tanggal', '<=', $tahun_lalu . '-12-31')
            ->get()
            ->groupBy('r_sesudah');

        $historiKeluarLalu = Histori::where('st_histori', 'PDH')
            ->where('is_delete', 0)
            ->where('tanggal', '<=', $tahun_lalu . '-12-31')
            ->get()
            ->groupBy('r_sebelum');

        $historiMasukIni = Histori::where('st_histori', 'PDH')
            ->where('is_delete', 0)
            ->where('tanggal', '<=', $tahun_ini . '-12-31')
            ->get()
            ->groupBy('r_sesudah');

        $historiKeluarIni = Histori::where('st_histori', 'PDH')
            ->where('is_delete', 0)
            ->where('tanggal', '<=', $tahun_ini . '-12-31')
            ->get()
            ->groupBy('r_sebelum');

        // Variabel total
        $jumlahTotalTahunLalu = 0;
        $totalHargaTahunLalu  = 0;
        $jumlahTotalTahunIni  = 0;
        $totalHargaTahunIni   = 0;

        // Inisialisasi
        $asetsByRuang = [];

        foreach ($ruangs as $ruang) {
            // === Aset per ruang ===
            $items = $allAsets->where('code_ruang', $ruang->code);

            // Tambahkan histori pindah dan status keberadaan per 31/12 (tahun lalu & tahun ini) untuk setiap aset
            foreach ($items as $aset) {
                $historiGroup = $allHistoriPindah->get($aset->id_aset);
                $historiSorted = $historiGroup ? $historiGroup->sortBy('tanggal')->values() : collect();

                // Info pindah terbaru (untuk kolom keterangan)
                $histori_pindah = $historiGroup ? $historiGroup->first() : null; // first() karena $allHistoriPindah sudah diorder desc sebelum group
                if ($histori_pindah) {
                    $aset->ruang_sebelum = $kodeRuangMap[$histori_pindah->r_sebelum] ?? $histori_pindah->r_sebelum;
                    $aset->ruang_sesudah = $kodeRuangMap[$histori_pindah->r_sesudah] ?? $histori_pindah->r_sesudah;
                    $aset->tanggal_pindah = $histori_pindah->tanggal ? date('d/m/Y', strtotime($histori_pindah->tanggal)) : '-';
                    $aset->tanggal_sebelum_pindah = $aset->date ? date('d/m/Y', strtotime($aset->date)) : '-';
                    $aset->keterangan_pindah = $histori_pindah->ket ?? '-';
                } else {
                    $aset->ruang_sebelum = '-';
                    $aset->ruang_sesudah = '-';
                    $aset->tanggal_pindah = '-';
                    $aset->tanggal_sebelum_pindah = $aset->date ? date('d/m/Y', strtotime($aset->date)) : '-';
                    $aset->keterangan_pindah = '-';
                }

                // Tentukan ruang aset per 31/12 tahun lalu
                $cutoffLastYear = $tahun_lalu . '-12-31';
                $cutoffThisYear = $tahun_ini . '-12-31';

                $lastBeforeOrOnLY = $historiSorted->where('tanggal', '<=', $cutoffLastYear)->last();
                if ($lastBeforeOrOnLY) {
                    $roomAsOfLastYear = $lastBeforeOrOnLY->r_sesudah;
                } else {
                    $firstAfterLY = $historiSorted->firstWhere('tanggal', '>', $cutoffLastYear);
                    $roomAsOfLastYear = $firstAfterLY ? $firstAfterLY->r_sebelum : $aset->code_ruang;
                }

                $lastBeforeOrOnTY = $historiSorted->where('tanggal', '<=', $cutoffThisYear)->last();
                if ($lastBeforeOrOnTY) {
                    $roomAsOfThisYear = $lastBeforeOrOnTY->r_sesudah;
                } else {
                    $firstAfterTY = $historiSorted->firstWhere('tanggal', '>', $cutoffThisYear);
                    $roomAsOfThisYear = $firstAfterTY ? $firstAfterTY->r_sebelum : $aset->code_ruang;
                }

                $aset->was_in_room_last_year = (
                    $aset->date && strtotime($aset->date) <= strtotime($cutoffLastYear)
                    && $roomAsOfLastYear === $ruang->code
                );
                $aset->was_in_room_this_year = (
                    $aset->date && strtotime($aset->date) <= strtotime($cutoffThisYear)
                    && $roomAsOfThisYear === $ruang->code
                );
            }

            $asetsByRuang[$ruang->code] = $items;

            // === Data Tahun Lalu ===
            $aset_awal = $allAsets
                ->where('code_ruang', $ruang->code)
                ->where('date', '<=', $tahun_lalu . '-12-31');

            $masuk_lalu  = $historiMasukLalu->get($ruang->code, collect());
            $keluar_lalu = $historiKeluarLalu->get($ruang->code, collect());

            $aset_tahun_lalu = $aset_awal->merge($masuk_lalu);
            foreach ($keluar_lalu as $keluar) {
                $aset_tahun_lalu->push((object) [
                    'jumlah_brg' => -1 * $keluar->hjum_brg,
                    'harga'      => -1 * $keluar->hprice,
                    'kondisi'    => $keluar->hkondisi,
                ]);
            }

            // === Data Tahun Ini ===
            $aset_akhir = $allAsets
                ->where('code_ruang', $ruang->code)
                ->where('date', '<=', $tahun_ini . '-12-31');

            $masuk_ini  = $historiMasukIni->get($ruang->code, collect());
            $keluar_ini = $historiKeluarIni->get($ruang->code, collect());

            $aset_tahun_ini = $aset_akhir->merge($masuk_ini);
            foreach ($keluar_ini as $keluar) {
                $aset_tahun_ini->push((object) [
                    'jumlah_brg' => -1 * $keluar->hjum_brg,
                    'harga'      => -1 * $keluar->hprice,
                    'kondisi'    => $keluar->hkondisi,
                ]);
            }

            // Simpan hasil
            $ruang['aset_tahun_lalu'] = $aset_tahun_lalu;
            $ruang['aset_tahun_ini']  = $aset_tahun_ini;

            // Akumulasi total
            $jumlahTotalTahunLalu += $aset_tahun_lalu->sum('jumlah_brg');
            $totalHargaTahunLalu  += $aset_tahun_lalu->sum('harga');
            $jumlahTotalTahunIni  += $aset_tahun_ini->sum('jumlah_brg');
            $totalHargaTahunIni   += $aset_tahun_ini->sum('harga');
        }

        return view('opruang.print', [
            'ruangs'                => $ruangs,
            'asetsByRuang'          => $asetsByRuang,
            'jumlahTotalTahunLalu'  => $jumlahTotalTahunLalu,
            'totalHargaTahunLalu'   => $totalHargaTahunLalu,
            'jumlahTotalTahunIni'   => $jumlahTotalTahunIni,
            'totalHargaTahunIni'    => $totalHargaTahunIni,
        ]);
    }
}
