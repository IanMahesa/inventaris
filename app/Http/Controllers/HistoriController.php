<?php

namespace App\Http\Controllers;

use App\Models\Histori;
use App\Models\KodeRuang;
use App\Models\Kategori;
use App\Models\Aset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriController extends Controller
{
    public function index()
    {
        $historis = Histori::with(['dataAset', 'ruangSebelum', 'ruangSesudah', 'kategori'])
            ->where('is_delete', '!=', 1)
            ->orderBy('updated_at', 'desc') // urutkan dari update terbaru
            ->get();

        return view('histori.index', compact('historis'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'       => 'required|date',
            'st_histori'    => 'required|string',
            'name_brg'      => 'required|string',
            'jenis_brg'     => 'required|string',
            'kd_ruang'      => 'required|string',
            'th_oleh'       => 'nullable|date',
            'r_sebelum'     => 'nullable|string',
            'r_sesudah'     => 'nullable|string',
            'ket'    => 'nullable|string',
            'id_asetsblm'   => 'required|integer|exists:tbl_aset,id_aset',
            'hfoto'         => 'required|string',
        ]);

        // Tambahkan kode berikut:
        $aset = Aset::where('id_aset', $validated['id_asetsblm'])->first();
        $validated['tanggal_sblm'] = $aset ? $aset->date : null;

        // Jika histori LLG atau RSK, aset di-soft delete
        if (in_array($validated['st_histori'], ['LLG', 'RSK'])) {
            if ($aset) {
                $aset->is_delete = 1;
                $aset->save();
            }
        }

        $validated['is_delete'] = 0;

        // Generate id_regis secara otomatis setelah create
        $histori = Histori::create($validated);

        // Generate id_regis setelah histori dibuat (agar bisa menggunakan id_histori)
        $tahun = \Carbon\Carbon::parse($validated['tanggal'])->format('y');
        $bulan = \Carbon\Carbon::parse($validated['tanggal'])->format('m');
        $st_history = $validated['st_histori'] ?? 'XX';
        $idFormat = str_pad($histori->id_histori, 4, '0', STR_PAD_LEFT);
        $id_regis = "$st_history-$tahun-$bulan-$idFormat";

        $histori->update(['id_regis' => $id_regis]);

        return response()->json(['message' => 'Data histori berhasil ditambahkan.', 'data' => $histori]);
    }

    public function edit($id)
    {
        // Ambil data histori berdasarkan ID histori
        $histori = Histori::findOrFail($id);
        // Ambil data aset terkait histori
        $aset = Aset::where('id_aset', $histori->id_asetsblm)->first();
        // Ambil data aset terkait histori
        $aset = Aset::where('date', $histori->tanggal_sblm)->first();
        // Ambil semua kategori dan ruangan untuk pilihan dropdown
        $kategori = Kategori::all();
        $ruang = KodeRuang::all();
        // Kirim data ke view
        return view('histori.edit', compact('histori', 'aset', 'kategori', 'ruang'));
    }

    // Update histori berdasarkan ID
    public function update(Request $request, $id)
    {
        $histori = Histori::findOrFail($id);

        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'st_histori' => 'required|string',
            'name_brg'   => 'required|string',
            'jenis_brg'  => 'required|string',
            'kd_ruang'   => 'required|string',
            'th_oleh'    => 'nullable|date',
            'r_sebelum'  => 'nullable|string',
            'r_sesudah'  => 'required|string|exists:tbl_ruang,code',
            'ket' => 'nullable|string',
            'hmerk'      => 'required|string',
            'image_url_1' => 'nullable|string',
            'image_url_2' => 'nullable|string',
            'image_url_3' => 'nullable|string',
            'image_url_4' => 'nullable|string',
            'foto_lama_1' => 'nullable|string',
            'foto_lama_2' => 'nullable|string',
            'foto_lama_3' => 'nullable|string',
            'foto_lama_4' => 'nullable|string',
            'hjum_brg'   => 'nullable|integer',
        ]);
        if (empty($validated['hjum_brg'])) {
            $validated['hjum_brg'] = 1;
        }

        // Generate id_regis secara otomatis
        $tahun = \Carbon\Carbon::parse($validated['tanggal'])->format('y');
        $bulan = \Carbon\Carbon::parse($validated['tanggal'])->format('m');
        $st_history = $validated['st_histori'] ?? 'XX';
        $idFormat = str_pad($histori->id_histori, 4, '0', STR_PAD_LEFT);
        $validated['id_regis'] = "$st_history-$tahun-$bulan-$idFormat";

        // Pastikan is_delete pada histori selalu 0
        $validated['is_delete'] = 0;

        // Update code_ruang pada aset sesuai r_sesudah (langsung pakai code)
        $aset = Aset::where('id_aset', $histori->id_asetsblm)->first();
        if ($aset && !empty($validated['r_sesudah'])) {
            $aset->code_ruang = $validated['r_sesudah'];
            $aset->save();

            // Regenerasi barang_id jika perlu
            $tahun = $aset->periode;
            $bulan = $aset->periode ? \Carbon\Carbon::parse($aset->periode)->format('m') : '';
            $idFormatted = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
            $kodeRuang = $aset->code_ruang;
            $kodeKategori = $aset->code_kategori;
            $aset->barang_id = "{$kodeRuang}-{$tahun}-{$bulan}-{$kodeKategori}-{$idFormatted}";
            $aset->save();
        }

        // Jika st_histori adalah LLG atau RSK, aset di-soft delete
        if (in_array($validated['st_histori'], ['LLG', 'RSK'])) {
            // Ambil ulang aset berdasarkan id_asetsblm
            $aset = Aset::where('id_aset', $histori->id_asetsblm)->first();
            if ($aset) {
                $aset->is_delete = 1;
                $aset->save();
            }
        }

        // Jika st_histori adalah PDH, update juga ke tbl_aset berdasarkan id_reg (id_aset)
        if ($validated['st_histori'] === 'PDH') {
            // id_reg pada histori adalah id_aset pada aset
            $aset = Aset::where('id_aset', $histori->id_asetsblm)->first();
            if ($aset) {
                $aset->update([
                    'nama_brg'      => $validated['name_brg'],
                    'code_kategori' => $validated['jenis_brg'],
                    'code_ruang'    => $validated['r_sesudah'],
                    'keterangan'    => $validated['ket'],
                    'foto'          => $validated['hfoto'] ?? $aset->foto,
                    // id_aset tidak diubah!
                ]);
            }
        }

        // Proses foto histori dari 4 slot image_url
        $finalFotos = [];

        // Ambil foto lama yang tersimpan di DB (array JSON)
        $existingFotos = [];
        if ($aset && $aset->foto) {
            if (is_string($aset->foto)) {
                $existingFotos = json_decode($aset->foto, true) ?: [];
            } elseif (is_array($aset->foto)) {
                $existingFotos = $aset->foto;
            }
        }

        // Hitung nomor foto terakhir yang ada untuk melanjutkan penomoran
        $lastPhotoNumber = 0;
        foreach ($existingFotos as $existingPath) {
            if (!empty($existingPath)) {
                // Extract nomor dari nama file: foto_aset_id_periode_nomor.jpg
                if (preg_match('/foto_aset_\d+_\d{4}-\d{2}-\d{2}_(\d+)\.jpg/', $existingPath, $matches)) {
                    $photoNumber = (int)$matches[1];
                    if ($photoNumber > $lastPhotoNumber) {
                        $lastPhotoNumber = $photoNumber;
                    }
                }
            }
        }
        $nextPhotoNumber = $lastPhotoNumber + 1;

        // Proses 4 slot foto
        for ($i = 1; $i <= 4; $i++) {
            $newField = "image_url_{$i}";
            $oldField = "foto_lama_{$i}";

            if (!empty($validated[$newField])) {
                // Ada foto baru dari kamera
                $data_uri = $validated[$newField];
                $parts = explode(",", $data_uri);
                $encoded_image = count($parts) === 2 ? $parts[1] : $data_uri;
                $decoded_image = base64_decode($encoded_image);

                // Format nama file: foto_aset_idaset_periode_urutan (lanjutkan dari nomor terakhir)
                $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
                $filename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$nextPhotoNumber}.jpg";
                $filePath = 'foto_aset/' . $filename;
                Storage::disk('public')->put($filePath, $decoded_image);
                $finalFotos[] = $filePath;
                $nextPhotoNumber++;
            } elseif (!empty($validated[$oldField])) {
                // Gunakan foto lama dari form
                $oldUrl = $validated[$oldField];
                $pos = strpos($oldUrl, '/storage/');
                if ($pos !== false) {
                    $relative = substr($oldUrl, $pos + strlen('/storage/'));
                    $finalFotos[] = $relative;
                } else {
                    $finalFotos[] = $oldUrl;
                }
            } elseif (isset($existingFotos[$i - 1]) && !empty($existingFotos[$i - 1])) {
                // Gunakan foto lama dari database
                $finalFotos[] = $existingFotos[$i - 1];
            }
        }

        // Bersihkan null di ujung array namun tetap preserve urutan slot yang terisi
        $finalFotos = array_values(array_filter($finalFotos, function ($v) {
            return !empty($v);
        }));

        // Set hfoto untuk histori
        $validated['hfoto'] = !empty($finalFotos) ? json_encode($finalFotos) : null;

        // Update histori dengan hfoto
        $histori->update($validated);

        // Update foto aset juga
        if ($aset) {
            $validated['foto'] = !empty($finalFotos) ? json_encode($finalFotos) : $aset->foto;
            $aset->update($validated);
        }

        // Jika st_histori adalah LLG atau RSK, aset di-soft delete (PASTIKAN INI PALING AKHIR DAN HANYA UPDATE is_delete)
        if (in_array($validated['st_histori'], ['LLG', 'RSK'])) {
            $aset = Aset::where('id_aset', $histori->id_asetsblm)->first();
            if ($aset) {
                $aset->is_delete = 1;
                $aset->save();
            }
        }

        return response()->json(['message' => 'Data histori berhasil diperbarui.', 'data' => $histori]);
    }

    // Soft delete histori
    public function destroy($id)
    {
        $histori = Histori::findOrFail($id);
        $histori->update(['is_delete' => 1]);

        // Update is_delete pada aset terkait
        if ($histori->id_asetsblm) {
            $aset = Aset::where('id_aset', $histori->id_asetsblm)->first();
            if ($aset) {
                $aset->is_delete = 1;
                $aset->save();
            }
        }

        return redirect()->back()->with('success', 'Data histori berhasil dihapus.');
    }

    // Ambil data aset gabungan (ruangan dan kategori)
    public function ambilAset($id)
    {
        $data = (new Histori)->ambilAset($id);
        if ($data) {
            return response()->json($data);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function qrcode($id)
    {
        // logic menampilkan QRCode
    }

    public function downloadQrcode($id)
    {
        // logic download QRCode
    }

    public function create(Request $request)
    {
        $kategori = Kategori::all();
        $ruang = KodeRuang::all();
        $aset = null;

        if ($request->has('id_aset')) {
            $aset = Aset::where('id_aset', $request->id_aset)->first();
            // Cek jika sudah ada histori untuk aset ini
            $histori = Histori::where('id_asetsblm', $request->id_aset)->latest()->first();
            if ($histori) {
                // Redirect ke edit histori terakhir
                return redirect()->route('histori.edit', $histori->id_histori);
            }
        }

        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'st_histori' => 'required|string',
            'name_brg'   => 'required|string',
            'jenis_brg'  => 'required|string',
            'kd_ruang'   => 'required|string',
            'th_oleh'    => 'nullable|date',
            'r_sebelum'  => 'nullable|string',
            'r_sesudah'  => 'nullable|string',
            'ket' => 'nullable|string',
            'hmerk'      => 'required|string',
        ]);


        return view('histori.create', compact('aset', 'kategori', 'ruang'));
    }

    public function show($id)
    {
        // Ambil histori dengan relasi yang diperlukan
        $histori = Histori::with(['kategori', 'ruangSebelum', 'ruangSesudah'])->findOrFail($id);

        // Ambil data aset sebelum (asal histori)
        $aset = Aset::with(['ruang', 'kategori'])->findOrFail($histori->id_asetsblm);

        // Kumpulkan seluruh histori untuk aset ini (urutkan terlama terlebih dulu)
        $allHistForAsset = Histori::with(['ruangSebelum'])
            ->where('id_asetsblm', $histori->id_asetsblm)
            ->orderBy('id_histori', 'asc')
            ->get();

        // --- FOTO SEBELUM ---
        // Sesuai permintaan: Ambil 4 foto pertama dari kolom foto pada tbl_aset.
        // Nama ruangan diambil dari tbl_histori kolom r_sebelum dengan created_at yang paling awal.
        $fotosSebelum = [];
        $firstByCreated = Histori::with('ruangSebelum')
            ->where('id_asetsblm', $histori->id_asetsblm)
            ->orderBy('created_at', 'asc')
            ->first();

        $ruanganSebelumName = $firstByCreated->ruangSebelum->name
            ?? $firstByCreated->r_sebelum
            ?? ($aset->ruang->name ?? 'Ruangan tidak diketahui');

        if (!empty($aset->foto)) {
            $fotoItemsRaw = $aset->foto;
            if (is_array($fotoItemsRaw)) {
                $fotoItems = $fotoItemsRaw;
            } elseif (is_string($fotoItemsRaw)) {
                $decoded = json_decode($fotoItemsRaw, true);
                $fotoItems = is_array($decoded) ? $decoded : (strlen($fotoItemsRaw) ? [$fotoItemsRaw] : []);
            } else {
                $fotoItems = [];
            }
            foreach ($fotoItems as $path) {
                if (count($fotosSebelum) >= 4) break;
                if (!empty($path)) {
                    $relative = ltrim($path, '/');
                    if (strpos($relative, 'storage/') === 0) {
                        $relative = substr($relative, strlen('storage/'));
                    }
                    $url = filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $relative);
                    $fotosSebelum[] = [
                        'url' => $url,
                        'ruangan' => $ruanganSebelumName,
                    ];
                }
            }
        }

        // Pastikan maksimal 4 foto sebelum
        if (count($fotosSebelum) > 4) {
            $fotosSebelum = array_slice($fotosSebelum, 0, 4);
        }

        // Normalisasi daftar path (helper)
        $normalizeList = function ($raw) {
            $items = [];
            if (empty($raw)) return $items;
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                $items = is_array($decoded) ? $decoded : (strlen($raw) ? [$raw] : []);
            } elseif (is_array($raw)) {
                $items = $raw;
            }
            $out = [];
            foreach ($items as $p) {
                if (empty($p)) continue;
                $rel = ltrim($p, '/');
                if (strpos($rel, 'storage/') === 0) {
                    $rel = substr($rel, strlen('storage/'));
                }
                $out[] = $rel;
            }
            return $out;
        };

        $asetNorm = $normalizeList($aset->foto ?? []);
        $asetFirst4Norm = array_slice($asetNorm, 0, 4);
        $histNorm = $normalizeList($histori->hfoto ?? []);

        // 1) Hapus dari "Sebelum" semua foto yang juga muncul pada hfoto histori saat ini (agar diganti placeholder di view)
        if (!empty($fotosSebelum) && !empty($histNorm)) {
            $histSet = array_flip($histNorm);
            $fotosSebelum = array_values(array_filter($fotosSebelum, function ($item) use ($histSet) {
                $url = $item['url'] ?? '';
                $relative = ltrim($url, '/');
                // Robust extraction of relative path within storage
                // Prefer substring starting from 'foto_aset/'
                $marker = 'foto_aset/';
                $posfa = strpos($relative, $marker);
                if ($posfa !== false) {
                    $relative = substr($relative, $posfa);
                } else {
                    $pos = strpos($relative, '/storage/');
                    if ($pos !== false) {
                        $relative = substr($relative, $pos + strlen('/storage/'));
                    }
                }
                $relative = ltrim($relative, '/');
                return !isset($histSet[$relative]);
            }));
        }

        $sameMultiset = false;
        if (!empty($asetFirst4Norm) || !empty($histNorm)) {
            $aCounts = array_count_values($asetFirst4Norm);
            $hCounts = array_count_values($histNorm);
            $sameMultiset = ($aCounts === $hCounts);
        }

        if ($sameMultiset) {
            // kosongkan supaya view mengisi 4 placeholder
            $fotosSebelum = [];
        }

        // --- FOTO SESUDAH ---
        // Kumpulkan semua foto dari setiap histori aset ini, label ruangan dari r_sesudah
        $fotosSesudah = [];
        foreach ($allHistForAsset as $histRow) {
            if (empty($histRow->hfoto)) continue;
            $fotoItemsRaw = $histRow->hfoto;
            if (is_array($fotoItemsRaw)) {
                $fotoItems = $fotoItemsRaw;
            } elseif (is_string($fotoItemsRaw)) {
                $decoded = json_decode($fotoItemsRaw, true);
                $fotoItems = is_array($decoded) ? $decoded : [$fotoItemsRaw];
            } else {
                $fotoItems = [];
            }
            foreach ($fotoItems as $path) {
                if (!empty($path)) {
                    $relative = ltrim($path, '/');
                    if (strpos($relative, 'storage/') === 0) {
                        $relative = substr($relative, strlen('storage/'));
                    }
                    $url = filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $relative);
                    $fotosSesudah[] = [
                        'url' => $url,
                        // Tampilkan NAMA ruangan sesudah
                        'ruangan' => $histRow->ruangSesudah->name ?? $histRow->r_sesudah ?? 'Ruangan tidak diketahui',
                    ];
                }
            }
        }

        return view('histori.show', [
            'histori' => $histori,
            'aset' => $aset,
            'fotosSebelum' => $fotosSebelum,
            'fotosSesudah' => $fotosSesudah,
            'ruanganSebelumName' => $ruanganSebelumName,
        ]);
    }


    public function print(Request $request)
    {
        $tahun_ini = Carbon::now()->year;
        $tahun_lalu = $tahun_ini - 1;

        // Ambil filter dari modal (array code ruang)
        $filterRuangs = $request->input('ruangs', []); // contoh: ['R001', 'R002']

        // Ambil hanya ruang yang dipilih
        $ruangs = KodeRuang::when(!empty($filterRuangs), function ($query) use ($filterRuangs) {
            return $query->whereIn('code', $filterRuangs); // ✅ penting!
        })->get();


        $ruangs->each(function ($ruang) use ($tahun_lalu, $tahun_ini) {
            $ruang->asets = Aset::with('ruang')
                ->where('code_ruang', $ruang->code)
                ->where('is_delete', 0)
                ->whereIn('st_aset', ['LLG', 'PDH'])
                ->get();

            // === Data Tahun Lalu ===
            $aset_awal = Aset::where('code_ruang', $ruang->code)
                ->whereYear('date', '<=', $tahun_lalu)
                ->get();

            $histori_masuk_lalu = Histori::where('st_histori', 'PDH')
                ->where('r_sesudah', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_lalu)
                ->get();

            $histori_keluar_lalu = Histori::where('st_histori', 'PDH')
                ->where('r_sebelum', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_lalu)
                ->get();

            $aset_tahun_lalu = $aset_awal->merge($histori_masuk_lalu);

            foreach ($histori_keluar_lalu as $keluar) {
                $aset_tahun_lalu->push((object) [
                    'jumlah_brg' => -1 * $keluar->hjum_brg,
                    'harga'      => -1 * $keluar->hprice,
                    'kondisi'    => $keluar->hkondisi,
                ]);
            }

            // === Data Tahun Ini ===
            $aset_akhir = Aset::where('code_ruang', $ruang->code)
                ->whereYear('date', '<=', $tahun_ini)
                ->get();

            $histori_masuk_ini = Histori::where('st_histori', 'PDH')
                ->where('r_sesudah', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_ini)
                ->get();

            $histori_keluar_ini = Histori::where('st_histori', 'PDH')
                ->where('r_sebelum', $ruang->code)
                ->whereYear('tanggal', '<=', $tahun_ini)
                ->get();

            $aset_tahun_ini = $aset_akhir->merge($histori_masuk_ini);

            foreach ($histori_keluar_ini as $keluar) {
                $aset_tahun_ini->push((object) [
                    'jumlah_brg' => -1 * $keluar->hjum_brg,
                    'harga'      => -1 * $keluar->hprice,
                    'kondisi'    => $keluar->hkondisi,
                ]);
            }

            $ruang->aset_tahun_lalu = $aset_tahun_lalu;
            $ruang->aset_tahun_ini = $aset_tahun_ini;

            // Akumulasi Total
            $ruang->jumlahTotalTahunLalu = $aset_tahun_lalu->sum('jumlah_brg');
            $ruang->totalHargaTahunLalu = $aset_tahun_lalu->sum('harga');
            $ruang->jumlahTotalTahunIni = $aset_tahun_ini->sum('jumlah_brg');
            $ruang->totalHargaTahunIni = $aset_tahun_ini->sum('harga');
        });

        return view('histori.print', [
            'ruangs' => $ruangs,
            'asetsByRuang' => $ruangs->mapWithKeys(function ($ruang) {
                return [$ruang->code => $ruang->asets];
            }),
            'jumlahTotalTahunLalu' => $ruangs->sum('jumlahTotalTahunLalu'),
            'totalHargaTahunLalu' => $ruangs->sum('totalHargaTahunLalu'),
            'jumlahTotalTahunIni' => $ruangs->sum('jumlahTotalTahunIni'),
            'totalHargaTahunIni' => $ruangs->sum('totalHargaTahunIni'),
        ]);
    }

    public function printByStatus(Request $request, $status)
    {
        // Validasi status yang diizinkan
        if (!in_array($status, ['PDH', 'LLG'])) {
            abort(404, 'Status tidak valid');
        }

        $tahun_ini = Carbon::now()->year;
        $tahun_lalu = $tahun_ini - 1;

        // Ambil parameter tanggal dari query string
        $start = $request->query('start');
        $end = $request->query('end');

        // Query histori dengan relasi
        $query = Histori::where('st_histori', $status)
            ->with(['ruangSebelum', 'ruangSesudah', 'kategori'])
            ->orderBy('tanggal', 'desc');

        // Filter tanggal jika ada
        if ($start) {
            $query->whereDate('tanggal', '>=', $start);
        }
        if ($end) {
            $query->whereDate('tanggal', '<=', $end);
        }

        $historis = $query->get();

        // Hitung total berdasarkan status
        $totalBarang = $historis->sum('hjum_brg');
        $totalHarga = $historis->sum('hprice');

        // Status text untuk judul
        $statusText = $status === 'PDH' ? 'Pindah' : 'Lelang';

        return view('histori.print-status', [
            'historis' => $historis,
            'status' => $status,
            'statusText' => $statusText,
            'totalBarang' => $totalBarang,
            'totalHarga' => $totalHarga,
            'tahun_ini' => $tahun_ini,
            'tahun_lalu' => $tahun_lalu,
            'start' => $start,
            'end' => $end
        ]);
    }
}
