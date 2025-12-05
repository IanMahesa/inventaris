<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset; // Pastikan model Aset sudah dibuat
use App\Models\KodeRuang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Histori;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AsetImport;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data kategori & ruangan
        $kategori = Kategori::where('is_delete', 0)
            ->orderBy('kode', 'asc')
            ->get();
        $ruang = KodeRuang::where('is_delete', 0)
            ->orderBy('code', 'asc')
            ->get();

        // Query dasar untuk aset
        $query = Aset::with(['ruang', 'kategori'])
            ->where('is_delete', 0)
            ->orderBy('id_aset', 'asc')
            ->orderBy('updated_at', 'desc');


        // Filter berdasarkan rentang tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filter berdasarkan pencarian nama barang
        if ($request->filled('search')) {
            $query->where('nama_brg', 'like', '%' . $request->search . '%');
        }

        // Cek apakah ada filter lain (untuk kompatibilitas dengan filter lama)
        if (
            $request->filled('date') ||
            $request->filled('periode') ||
            $request->filled('nama_brg') ||
            $request->filled('merk') ||
            $request->filled('seri') ||
            $request->filled('kondisi') ||
            $request->filled('code_kategori') ||
            $request->filled('code_ruang')
        ) {
            if ($request->filled('date')) {
                $query->whereDate('date', \Carbon\Carbon::parse($request->date));
            }

            if ($request->filled('periode')) {
                $tahun = $request->periode;
                $query->whereYear('periode', $tahun);
            }

            if ($request->filled('nama_brg')) {
                $query->where('nama_brg', 'like', '%' . $request->nama_brg . '%');
            }

            if ($request->filled('merk')) {
                $query->where('merk', 'like', '%' . $request->merk . '%');
            }

            if ($request->filled('seri')) {
                $query->where('no_seri', 'like', '%' . $request->seri . '%');
            }

            if ($request->filled('kondisi')) {
                $query->where('kondisi', $request->kondisi);
            }

            if ($request->filled('code_kategori')) {
                $query->where('code_kategori', $request->code_kategori);
            }

            if ($request->filled('code_ruang')) {
                $query->where('code_ruang', $request->code_ruang);
            }
        }

        // Ambil data dengan pagination
        $asets = $query->get();

        return view('aset.index', compact('asets', 'kategori', 'ruang'));
    }

    public function create()
    {
        $kategori = Kategori::where('is_delete', 0)
            ->orderBy('kode', 'asc')
            ->get();
        $ruang = KodeRuang::where('is_delete', 0)
            ->orderBy('code', 'asc') // atau ganti 'id' dengan nama kolom kode ruang
            ->get();
        return view('aset.create', compact('kategori', 'ruang'));
    }

    public function store(Request $request)
    {
        // Normalisasi harga agar sesuai format decimal
        if ($request->has('harga')) {
            $rawHarga = $request->input('harga');
            $normalizedHarga = str_replace(['.', ' '], '', $rawHarga);
            $normalizedHarga = str_replace(',', '.', $normalizedHarga);
            $request->merge(['harga' => $normalizedHarga]);
        }

        $validated = $request->validate([
            'nama_brg'      => 'required|string|max:50',
            'merk'          => 'required|string',
            'seri'          => 'nullable|string',
            'bahan'         => 'required|string',
            'ukuran'        => 'nullable|string',
            'periode'       => 'required|date',
            'barang_id'     => 'nullable|integer',
            'keterangan'    => 'nullable|string',
            'jumlah_brg'    => 'nullable|integer',
            'harga'         => 'required|numeric|min:0',
            'kondisi'       => 'required|in:Baik,Kurang Baik,Rusak Berat',
            'code_ruang'    => 'required|string|max:20',
            'code_kategori' => 'required|string|max:20',
            'image_url_1'   => 'nullable|string',
            'image_url_2'   => 'nullable|string',
            'image_url_3'   => 'nullable|string',
            'image_url_4'   => 'nullable|string',
            'st_aset'       => 'required|in:BL,HBH',
        ]);

        // Set tanggal input otomatis ke tanggal saat ini
        $validated['date'] = now()->format('Y-m-d');

        // Default jumlah_brg = 1
        if (empty($validated['jumlah_brg'])) {
            $validated['jumlah_brg'] = 1;
        }

        // Loop simpan semua foto ke storage
        $fotoPaths = [];
        $periodeStr = \Carbon\Carbon::parse($validated['periode'])->format('Y-m-d');
        for ($i = 1; $i <= 4; $i++) {
            $field = "image_url_$i";
            if (!empty($validated[$field])) {
                $parts = explode(",", $validated[$field]);
                if (count($parts) == 2) {
                    $encoded_image = $parts[1];
                    $decoded_image = base64_decode($encoded_image);

                    // Format nama file sesuai permintaan - akan diupdate setelah aset dibuat
                    $filename = "foto_aset_temp_{$periodeStr}_{$i}.jpg";

                    Storage::disk('public')->put('foto_aset/' . $filename, $decoded_image);
                    $fotoPaths[] = "foto_aset/" . $filename;
                }
            }
        }

        // Jika tidak ada foto, simpan sebagai array kosong

        // Simpan data aset
        $aset = Aset::create([
            'nama_brg'      => $validated['nama_brg'],
            'merk'          => $validated['merk'],
            'seri'          => $validated['seri'],
            'foto'          => json_encode($fotoPaths), // JSON array
            'bahan'         => $validated['bahan'],
            'ukuran'        => $validated['ukuran'],
            'periode'       => $validated['periode'],
            'barang_id'     => $validated['barang_id'] ?? null,
            'date'          => $validated['date'], // Menggunakan tanggal yang sudah diset otomatis
            'keterangan'    => $validated['keterangan'] ?? null,
            'jumlah_brg'    => $validated['jumlah_brg'],
            'harga'         => $validated['harga'],
            'kondisi'       => $validated['kondisi'],
            'code_ruang'    => $validated['code_ruang'],
            'code_kategori' => $validated['code_kategori'],
            'st_aset'       => $validated['st_aset'], // <-- TAMBAHKAN INI
        ]);

        // Generate barang_id
        $tahun       = \Carbon\Carbon::parse($aset->periode)->format('Y');
        $bulan       = \Carbon\Carbon::parse($aset->periode)->format('m');
        $idFormatted = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
        $kodeRuang   = $aset->code_ruang;
        $kodeKategori = $aset->code_kategori;

        $aset->barang_id = "{$kodeRuang}-{$tahun}-{$bulan}-{$kodeKategori}-{$idFormatted}";
        $aset->save();

        // Rename temporary photo files to include aset ID
        if (!empty($fotoPaths)) {
            $finalFotoPaths = [];
            foreach ($fotoPaths as $index => $tempPath) {
                $newFilename = "foto_aset_{$aset->id_aset}_{$periodeStr}_" . ($index + 1) . ".jpg";
                $newPath = "foto_aset/" . $newFilename;

                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);
                    $finalFotoPaths[] = $newPath;
                }
            }

            // Update aset with final photo paths
            $aset->foto = json_encode($finalFotoPaths);
            $aset->save();
        }

        // Generate QR code otomatis
        $qrContent  = $aset->id_aset;
        $safeName   = preg_replace('/[^A-Za-z0-9_\-]/', '_', $qrContent);
        $fileName   = 'qrcode_' . $safeName . '.png';
        $qrDir      = storage_path('app/public/qrcodes');
        if (!file_exists($qrDir)) mkdir($qrDir, 0777, true);

        $fullPath = $qrDir . '/' . $fileName;
        if (!file_exists($fullPath)) {
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(300)
                ->margin(4)
                ->errorCorrection('H')
                ->generate($qrContent, $fullPath);
        }

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil ditambahkan.');
    }


    public function edit($id)
    {
        // Ambil data aset berdasarkan ID
        $aset = Aset::findOrFail($id);

        // Ambil semua kategori dan ruangan untuk pilihan dropdown
        $kategori = Kategori::where('is_delete', 0)
            ->orderBy('kode', 'asc')
            ->get();
        $ruang = KodeRuang::where('is_delete', 0)
            ->orderBy('code', 'asc') // atau ganti 'id' dengan nama kolom kode ruang
            ->get();

        // Kirim data ke view
        return view('aset.edit', compact('aset', 'kategori', 'ruang'));
    }

    public function update(Request $request, $id)
    {
        // Normalisasi harga agar sesuai format decimal
        if ($request->has('harga')) {
            $rawHarga = $request->input('harga');
            // Hilangkan spasi
            $rawHarga = str_replace(' ', '', $rawHarga);

            // Jika ada koma, anggap sebagai desimal → ganti jadi titik
            if (strpos($rawHarga, ',') !== false) {
                $rawHarga = str_replace('.', '', $rawHarga); // hapus pemisah ribuan
                $rawHarga = str_replace(',', '.', $rawHarga); // koma → titik desimal
            } else {
                // Kalau tidak ada koma, berarti mungkin sudah format 2500000.00 (pakai titik desimal)
                // Maka hapus titik ribuan tapi jangan hapus titik terakhir desimal
                if (substr_count($rawHarga, '.') > 1) {
                    // Ada lebih dari 1 titik → berarti ribuan + desimal → hapus semua titik ribuan
                    $lastDot = strrpos($rawHarga, '.');
                    $intPart = substr($rawHarga, 0, $lastDot);
                    $decPart = substr($rawHarga, $lastDot + 1);
                    $intPart = str_replace('.', '', $intPart); // hapus titik ribuan
                    $rawHarga = $intPart . '.' . $decPart;
                }
            }

            $request->merge(['harga' => $rawHarga]);
        }

        $validated = $request->validate([
            'nama_brg' => 'required|string|max:50',
            'merk' => 'required|string',
            'seri' => 'nullable|string',
            'bahan' => 'required|string',
            'ukuran' => 'nullable|string',
            'periode' => 'required|date',
            'barang_id' => 'nullable|integer',
            'date' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'jumlah_brg' => 'nullable|integer',
            'harga' => 'required|numeric|min:0',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak Berat',
            'code_ruang' => 'nullable|string|max:20',
            'code_kategori' => 'nullable|string|max:20',
            'satuan' => 'required|string|in:unit,buah,set,lbr,lsn,roll,psng',
            'image_url_1'   => 'nullable|string',
            'image_url_2'   => 'nullable|string',
            'image_url_3'   => 'nullable|string',
            'image_url_4'   => 'nullable|string',
            'st_aset' => 'nullable|in:BL,HBH',
            'foto_lama_1' => 'nullable|string',
            'foto_lama_2' => 'nullable|string',
            'foto_lama_3' => 'nullable|string',
            'foto_lama_4' => 'nullable|string',
        ]);

        $aset = Aset::findOrFail($id);

        // Ambil foto lama yang tersimpan di DB (array JSON)
        $existingFotos = is_array($aset->foto) ? $aset->foto : (empty($aset->foto) ? [] : (array) $aset->foto);

        // Siapkan array hasil akhir
        $finalFotos = [];

        // Loop 4 slot: gunakan foto baru jika ada; jika tidak, pakai foto_lama_# dari form; jika kosong biarkan null
        for ($i = 1; $i <= 4; $i++) {
            $newField = "image_url_{$i}";
            $oldField = "foto_lama_{$i}";

            if (!empty($validated[$newField])) {
                $data_uri = $validated[$newField];
                $parts = explode(',', $data_uri);
                $encoded_image = count($parts) === 2 ? $parts[1] : $data_uri;
                $decoded_image = base64_decode($encoded_image);

                // Format nama file: foto_aset_idaset_periode_urutan
                $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
                $filename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$i}.jpg";
                $filePath = 'foto_aset/' . $filename;
                Storage::disk('public')->put($filePath, $decoded_image);
                $finalFotos[$i - 1] = $filePath;
            } else if (!empty($validated[$oldField])) {
                // nilai foto_lama_# di form adalah full URL asset('storage/...'), ambil path relative setelah storage/
                $oldUrl = $validated[$oldField];
                $pos = strpos($oldUrl, '/storage/');
                if ($pos !== false) {
                    $relative = substr($oldUrl, $pos + strlen('/storage/'));
                    $finalFotos[$i - 1] = $relative;
                } else {
                    // fallback: jika sudah relative
                    $finalFotos[$i - 1] = $oldUrl;
                }
            } else {
                // tidak ada foto baru maupun lama untuk slot ini
                $finalFotos[$i - 1] = null;
            }
        }

        // Bersihkan null di ujung array namun tetap preserve urutan slot yang terisi
        // Hapus null di tengah juga agar JSON rapih berisi hanya path valid
        $finalFotos = array_values(array_filter($finalFotos, function ($v) {
            return !empty($v);
        }));

        // Update field lain
        $aset->nama_brg = $validated['nama_brg'];
        $aset->merk = $validated['merk'];
        $aset->seri = $validated['seri'];
        $aset->bahan = $validated['bahan'];
        $aset->ukuran = $validated['ukuran'];
        $aset->periode = $validated['periode'];
        $aset->barang_id = $validated['barang_id'] ?? $aset->barang_id;
        $aset->date = $validated['date'] ?? $aset->date;
        $aset->keterangan = $validated['keterangan'] ?? $aset->keterangan;
        $aset->jumlah_brg = $validated['jumlah_brg'] ?? $aset->jumlah_brg;
        $aset->harga = $validated['harga'];
        $aset->kondisi = $validated['kondisi'];
        $aset->code_ruang = $validated['code_ruang'] ?? $aset->code_ruang;
        $aset->code_kategori = $validated['code_kategori'] ?? $aset->code_kategori;
        // Pastikan field satuan selalu diupdate
        if (array_key_exists('satuan', $validated)) {
            $aset->satuan = $validated['satuan'];
        }
        // Update st_aset hanya jika dikirim (untuk kasus aset berstatus PDH tidak perlu mengirim st_aset)
        if (array_key_exists('st_aset', $validated) && !is_null($validated['st_aset'])) {
            $aset->st_aset = $validated['st_aset'];
        }
        $aset->foto = $finalFotos; // simpan sebagai array (akan cast ke JSON)
        $aset->save();

        // Regenerasi barang_id
        $tahun = \Carbon\Carbon::parse($aset->periode)->format('Y');
        $bulan = $aset->periode ? \Carbon\Carbon::parse($aset->periode)->format('m') : '';
        $idFormatted = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
        $kodeRuang = $aset->code_ruang;
        $kodeKategori = $aset->code_kategori;
        $aset->barang_id = "{$kodeRuang}-{$tahun}-{$bulan}-{$kodeKategori}-{$idFormatted}";
        $aset->save();

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy($code)
    {
        try {
            $aset = Aset::findOrFail($code);
            $aset->is_delete = 1;
            $aset->save();
            return redirect()->route('aset.index')->with('success', 'Ruang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('aset.index')->with('error', 'Gagal menghapus ruang.');
        }
    }

    public function show($id)
    {
        $aset = Aset::findOrFail($id);

        $fotosFinal = [];
        $fotos = [];
        if (!empty($aset->foto)) {
            if (is_string($aset->foto)) {
                $decoded = json_decode($aset->foto, true);
                $fotos = is_array($decoded) ? $decoded : [];
            } elseif (is_array($aset->foto)) {
                $fotos = $aset->foto;
            }
        }

        // Bersihkan nilai kosong dan ambil hanya 4 foto terakhir
        $fotos = array_values(array_filter($fotos, function ($v) {
            return !empty($v);
        }));
        $fotosFinal = array_slice($fotos, -4);

        return view('aset.show', compact('aset', 'fotosFinal'));
    }

    public function generateQrCode($id)
    {
        $aset = Aset::findOrFail($id);
        // Pastikan hanya karakter aman
        $qrContent = preg_replace('/[^A-Za-z0-9_\-]/', '_', $aset->id_aset); // Use id_aset as QR code content
        $fileName = 'qrcode_' . $qrContent . '.png';
        $labelFileName = 'qrcode_label_' . $qrContent . '.png';
        $dir = storage_path('app/public/qrcodes');
        $path = $dir . '/' . $fileName;
        $labelPath = $dir . '/' . $labelFileName;

        // Pastikan folder ada
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($path)) {
            QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($aset->id_aset, $path);
        }

        return response()->file($path);
    }


    public function downloadQr($id)
    {
        $aset = Aset::findOrFail($id);

        $qrContent = preg_replace('/[^A-Za-z0-9_\-]/', '_', $aset->id_aset);
        $fileName = 'qrcode_' . $qrContent . '.png';

        $dir = storage_path('app/public/qrcodes');
        $path = $dir . '/' . $fileName;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($path)) {
            QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($aset->id_aset, $path);
        }

        return response()->download($path, $fileName);
    }

    public function editHistori($id)
    {
        $aset = Aset::with('ruang')->findOrFail($id);
        $ruang = KodeRuang::where('is_delete', 0)
            ->orderBy('code', 'asc') // atau ganti 'id' dengan nama kolom kode ruang
            ->get();
        return view('histori.edit', compact('aset', 'ruang'));
    }

    public function updateHistori(Request $request, $id)
    {
        $request->validate([
            'r_sesudah'   => 'required|string|exists:tbl_ruang,code',
            // Tambahkan validasi untuk bidang foto berbasis base64 dan fallback foto lama
            'image_url_1' => 'nullable|string',
            'image_url_2' => 'nullable|string',
            'image_url_3' => 'nullable|string',
            'image_url_4' => 'nullable|string',
            'foto_lama_1' => 'nullable|string',
            'foto_lama_2' => 'nullable|string',
            'foto_lama_3' => 'nullable|string',
            'foto_lama_4' => 'nullable|string',
        ]);

        $aset = Aset::with('ruang')->findOrFail($id);

        // Set tanggal input ke hari ini
        $validated['tanggal'] = now()->format('Y-m-d');

        // Normalisasi nilai harga agar sesuai format decimal MySQL
        $rawHprice = $request->input('hprice');
        if (!is_null($rawHprice)) {
            // Hapus pemisah ribuan dan ubah koma menjadi titik untuk desimal
            $normalizedHprice = str_replace(['.', ' '], '', $rawHprice);
            $normalizedHprice = str_replace(',', '.', $normalizedHprice);
            $request->merge(['hprice' => $normalizedHprice]);
        }

        $fotoPath = null;
        if ($request->hasFile('hfoto')) {
            $fotoPath = $request->file('hfoto')->store('histori_foto', 'public');
        }

        // Ambil foto lama yang tersimpan di DB (array JSON)
        $existingFotos = [];
        if ($aset->foto) {
            if (is_string($aset->foto)) {
                $existingFotos = json_decode($aset->foto, true) ?: [];
            } elseif (is_array($aset->foto)) {
                $existingFotos = $aset->foto;
            }
        }
        // Ambil hanya 4 foto terakhir untuk proses edit
        $existingCleaned = array_values(array_filter($existingFotos, function ($v) {
            return !empty($v);
        }));
        $lastFour = array_slice($existingCleaned, -4);

        // Proses foto aset (4 slot) dari kamera/webcam berbasis base64
        $finalFotos = [];

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

        Log::info("updateHistori - Last photo number found: {$lastPhotoNumber}, nextPhotoNumber: {$nextPhotoNumber}");

        for ($i = 1; $i <= 4; $i++) {
            $newField = "image_url_{$i}";
            $oldField = "foto_lama_{$i}";

            $dataUri = $request->input($newField);
            $oldUrl  = $request->input($oldField);

            // Debug log untuk troubleshooting
            Log::info("updateHistori - Processing slot {$i}: newField={$newField}, oldField={$oldField}");
            Log::info("updateHistori - dataUri empty: " . (empty($dataUri) ? 'true' : 'false'));
            Log::info("updateHistori - oldUrl empty: " . (empty($oldUrl) ? 'true' : 'false'));

            if (!empty($dataUri)) {
                // Ada foto baru dari kamera
                $parts = explode(',', $dataUri);
                $encodedImage = count($parts) === 2 ? $parts[1] : $dataUri;
                $decodedImage = base64_decode($encodedImage);

                // Format nama file: foto_aset_idaset_periode_urutan (lanjutkan dari nomor terakhir)
                $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
                $filename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$nextPhotoNumber}.jpg";
                $filePath = 'foto_aset/' . $filename;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $decodedImage);
                $finalFotos[] = $filePath;
                Log::info("updateHistori - Foto baru disimpan di slot {$i} dengan nomor {$nextPhotoNumber}");
                $nextPhotoNumber++; // Increment untuk foto berikutnya
            } elseif (!empty($oldUrl)) {
                // Gunakan foto lama dari form
                $pos = strpos($oldUrl, '/storage/');
                if ($pos !== false) {
                    $relative = substr($oldUrl, $pos + strlen('/storage/'));
                    $finalFotos[] = $relative;
                } else {
                    $finalFotos[] = $oldUrl;
                }
                Log::info("updateHistori - Foto lama dari form digunakan di slot {$i}");
            } elseif (isset($lastFour[$i - 1]) && !empty($lastFour[$i - 1])) {
                // Gunakan foto lama dari database (4 foto terakhir)
                $finalFotos[] = $lastFour[$i - 1];
                Log::info("updateHistori - Foto lama (last 4) dari database digunakan di slot {$i}");
            } else {
                Log::info("updateHistori - Slot {$i} kosong");
            }
        }

        // Bersihkan null di ujung array namun tetap preserve urutan slot yang terisi
        $finalFotos = array_values(array_filter($finalFotos, function ($v) {
            return !empty($v);
        }));



        // Jika TIDAK ada foto baru sama sekali, duplikasi foto yang dipakai dengan nama baru
        $anyNewPhoto = collect([$request->input('image_url_1'), $request->input('image_url_2'), $request->input('image_url_3'), $request->input('image_url_4')])
            ->filter(function ($v) {
                return !empty($v);
            })
            ->isNotEmpty();
        if (!$anyNewPhoto && !empty($finalFotos)) {
            $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
            $renamed = [];
            foreach ($finalFotos as $relPath) {
                // Pastikan path relatif
                $relative = ltrim($relPath, '/');
                $marker = 'foto_aset/';
                $posfa = strpos($relative, $marker);
                if ($posfa !== false) {
                    $relative = substr($relative, $posfa);
                }
                $newFilename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$nextPhotoNumber}.jpg";
                $newPath = 'foto_aset/' . $newFilename;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relative)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->copy($relative, $newPath);
                    $renamed[] = $newPath;
                    $nextPhotoNumber++;
                } else {
                    // Jika file lama tidak ditemukan, tetap gunakan path lama agar tidak putus
                    $renamed[] = $relative;
                }
            }
            $finalFotos = $renamed;
            Log::info('updateHistori - Tidak ada foto baru, menduplikasi foto dengan nama baru: ' . json_encode($finalFotos));
        }

        // Isi placeholder agar finalFotos genap 4 foto (duplikasi dari foto terakhir atau dari existingCleaned)
        $missingCount = 4 - count($finalFotos);
        if ($missingCount > 0) {
            $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
            $seedList = !empty($finalFotos) ? $finalFotos : $existingCleaned;
            for ($j = 0; $j < $missingCount && !empty($seedList); $j++) {
                $baseIndex = min($j, count($seedList) - 1);
                $basePath = $seedList[$baseIndex];
                $relative = ltrim($basePath, '/');
                $marker = 'foto_aset/';
                $posfa = strpos($relative, $marker);
                if ($posfa !== false) {
                    $relative = substr($relative, $posfa);
                }
                $newFilename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$nextPhotoNumber}.jpg";
                $newPath = 'foto_aset/' . $newFilename;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relative)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->copy($relative, $newPath);
                    $finalFotos[] = $newPath;
                    $nextPhotoNumber++;
                }
            }
        }

        // Tentukan path untuk kolom hfoto di histori
        $hfotoPath = '';
        if (!empty($fotoPath)) {
            $hfotoPath = $fotoPath;
        } elseif (!empty($finalFotos)) {
            $hfotoPath = $finalFotos[0];
        } else {
            foreach ($existingFotos as $p) {
                if (!empty($p)) {
                    $hfotoPath = $p;
                    break;
                }
            }
        }

        // Generate id_regis secara otomatis
        $tahun = \Carbon\Carbon::parse($request->tanggal)->format('y');
        $bulan = \Carbon\Carbon::parse($request->tanggal)->format('m');
        $st_history = $request->st_histori ?? 'XX';
        $idFormat = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
        $id_regis = "$st_history-$tahun-$bulan-$idFormat";

        Histori::create([
            'id_asetsblm'   => $aset->id_aset,
            'st_histori'    => $request->st_histori,
            'id_regis'      => $id_regis,
            'tanggal_sblm'  => $aset->date,
            'tanggal'       => $request->tanggal,
            'name_brg'      => $aset->nama_brg,
            'jenis_brg'     => $request->jenis_brg,
            'th_oleh'       => $request->th_oleh,
            'r_sebelum'     => $request->r_sebelum,
            'r_sesudah'     => $request->r_sesudah,
            'hkondisi'      => $request->hkondisi,
            'hmerk'         => $request->hmerk,
            'hseri'         => $request->hseri,
            'hfoto'         => json_encode($finalFotos),
            'hbahan'        => $request->hbahan,
            'hsize'         => $request->hsize,
            'hjum_brg'      => $request->hjum_brg,
            'hprice'        => $request->hprice,
            'ket'           => $request->ket,
            'is_delete'     => 0,
        ]);

        // 🟢 Inilah bagian penting yang perlu kamu tambahkan:
        $aset->code_ruang = $request->r_sesudah;
        $aset->st_aset = $request->st_histori;
        $aset->date = $request->tanggal;
        $aset->jumlah_brg = $request->hjum_brg;
        $aset->kondisi = $request->hkondisi;
        $aset->keterangan = $request->ket;

        // Tambahkan ini:
        if (in_array($request->st_histori, ['RSK', 'LLG'])) {
            $aset->is_delete = 1;
        }

        // Simpan foto baru hasil proses base64 jika ada
        if (!empty($finalFotos)) {
            // Gabungkan foto lama dengan foto baru
            $allFotos = array_merge($existingFotos, $finalFotos);
            $aset->foto = json_encode($allFotos); // Simpan sebagai JSON string
        }

        // Regenerasi barang_id mengikuti pola di update aset
        $tahun = $aset->periode ? \Carbon\Carbon::parse($aset->periode)->format('Y') : '';
        $bulan = $aset->periode ? \Carbon\Carbon::parse($aset->periode)->format('m') : '';
        $idFormatted = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
        $kodeRuang = $aset->code_ruang;
        $kodeKategori = $aset->code_kategori;
        $aset->barang_id = "{$kodeRuang}-{$tahun}-{$bulan}-{$kodeKategori}-{$idFormatted}";

        $aset->save();

        return redirect()->route('histori.index')->with('success', 'Perubahan berhasil disimpan ke histori dan ruangan aset telah diperbarui.');
    }

    public function editHistoriLlg($id)
    {
        $aset = Aset::with('ruang')->findOrFail($id);
        $ruang = KodeRuang::where('is_delete', 0)->get();
        return view('histori.editlelang', compact('aset', 'ruang'));
    }

    public function updateHistoriLlg(Request $request, $id)
    {
        $request->validate([
            'r_sesudah' => 'nullable|string|exists:tbl_ruang,code',
            'image_url_1' => 'nullable|string',
            'image_url_2' => 'nullable|string',
            'image_url_3' => 'nullable|string',
            'image_url_4' => 'nullable|string',
            'foto_lama_1' => 'nullable|string',
            'foto_lama_2' => 'nullable|string',
            'foto_lama_3' => 'nullable|string',
            'foto_lama_4' => 'nullable|string',
        ]);

        $aset = Aset::with('ruang')->findOrFail($id);

        // Set tanggal input ke hari ini
        $validated['tanggal'] = now()->format('Y-m-d');

        // Normalisasi nilai harga agar sesuai format decimal MySQL
        $rawHprice = $request->input('hprice');
        if (!is_null($rawHprice)) {
            // Hapus pemisah ribuan dan ubah koma menjadi titik untuk desimal
            $normalizedHprice = str_replace(['.', ' '], '', $rawHprice);
            $normalizedHprice = str_replace(',', '.', $normalizedHprice);
            $request->merge(['hprice' => $normalizedHprice]);
        }

        $fotoPath = null;
        if ($request->hasFile('hfoto')) {
            $fotoPath = $request->file('hfoto')->store('histori_foto', 'public');
        }

        // Ambil foto lama yang tersimpan di DB (array JSON)
        $existingFotos = [];
        if ($aset->foto) {
            if (is_string($aset->foto)) {
                $existingFotos = json_decode($aset->foto, true) ?: [];
            } elseif (is_array($aset->foto)) {
                $existingFotos = $aset->foto;
            }
        }

        // Proses foto aset (4 slot) dari kamera/webcam berbasis base64
        $finalFotos = [];

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

        // Siapkan 4 foto terakhir sebagai fallback (konsisten dengan updateHistori)
        $existingCleaned = array_values(array_filter($existingFotos, function ($v) {
            return !empty($v);
        }));
        $lastFour = array_slice($existingCleaned, -4);

        for ($i = 1; $i <= 4; $i++) {
            $newField = "image_url_{$i}";
            $oldField = "foto_lama_{$i}";

            $dataUri = $request->input($newField);
            $oldUrl  = $request->input($oldField);

            if (!empty($dataUri)) {
                // Ada foto baru dari kamera
                $parts = explode(',', $dataUri);
                $encodedImage = count($parts) === 2 ? $parts[1] : $dataUri;
                $decodedImage = base64_decode($encodedImage);

                // Format nama file: foto_aset_idaset_periode_urutan (lanjutkan dari nomor terakhir)
                $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
                $filename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$nextPhotoNumber}.jpg";
                $filePath = 'foto_aset/' . $filename;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $decodedImage);
                $finalFotos[] = $filePath;
                $nextPhotoNumber++; // Increment untuk foto berikutnya
            } elseif (!empty($oldUrl)) {
                // Gunakan foto lama dari form
                $pos = strpos($oldUrl, '/storage/');
                if ($pos !== false) {
                    $relative = substr($oldUrl, $pos + strlen('/storage/'));
                    $finalFotos[] = $relative;
                } else {
                    $finalFotos[] = $oldUrl;
                }
            } elseif (isset($lastFour[$i - 1]) && !empty($lastFour[$i - 1])) {
                // Gunakan foto lama dari database (4 foto terakhir)
                $finalFotos[] = $lastFour[$i - 1];
            }
        }

        // Bersihkan null di ujung array namun tetap preserve urutan slot yang terisi
        $finalFotos = array_values(array_filter($finalFotos, function ($v) {
            return !empty($v);
        }));

        // Jika TIDAK ada foto baru sama sekali (LLG), duplikasi foto yang dipakai dengan nama baru
        $anyNewPhotoLLG = collect([
            $request->input('image_url_1'),
            $request->input('image_url_2'),
            $request->input('image_url_3'),
            $request->input('image_url_4')
        ])->filter(function ($v) {
            return !empty($v);
        })->isNotEmpty();
        if (!$anyNewPhotoLLG && !empty($finalFotos)) {
            $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
            $renamed = [];
            foreach ($finalFotos as $relPath) {
                $relative = ltrim($relPath, '/');
                $marker = 'foto_aset/';
                $posfa = strpos($relative, $marker);
                if ($posfa !== false) {
                    $relative = substr($relative, $posfa);
                }
                $newFilename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$nextPhotoNumber}.jpg";
                $newPath = 'foto_aset/' . $newFilename;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relative)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->copy($relative, $newPath);
                    $renamed[] = $newPath;
                    $nextPhotoNumber++;
                } else {
                    $renamed[] = $relative;
                }
            }
            $finalFotos = $renamed;
        }

        // Isi placeholder agar finalFotos genap 4 foto (LLG)
        $missingCountLLG = 4 - count($finalFotos);
        if ($missingCountLLG > 0) {
            $periodeStr = \Carbon\Carbon::parse($aset->periode)->format('Y-m-d');
            // seed dari finalFotos jika ada, kalau tidak dari 4 foto terakhir aset
            $existingCleanedLLG = array_values(array_filter($existingFotos, function ($v) {
                return !empty($v);
            }));
            $seedList = !empty($finalFotos) ? $finalFotos : array_slice($existingCleanedLLG, -4);
            for ($j = 0; $j < $missingCountLLG && !empty($seedList); $j++) {
                $baseIndex = min($j, count($seedList) - 1);
                $basePath = $seedList[$baseIndex];
                $relative = ltrim($basePath, '/');
                $marker = 'foto_aset/';
                $posfa = strpos($relative, $marker);
                if ($posfa !== false) {
                    $relative = substr($relative, $posfa);
                }
                $newFilename = "foto_aset_{$aset->id_aset}_{$periodeStr}_{$nextPhotoNumber}.jpg";
                $newPath = 'foto_aset/' . $newFilename;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relative)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->copy($relative, $newPath);
                    $finalFotos[] = $newPath;
                    $nextPhotoNumber++;
                }
            }
        }

        // Tentukan path untuk kolom hfoto di histori (LLG)
        $hfotoPath = '';
        if (!empty($fotoPath)) {
            $hfotoPath = $fotoPath;
        } elseif (!empty($finalFotos)) {
            $hfotoPath = $finalFotos[0];
        } else {
            foreach ($existingFotos as $p) {
                if (!empty($p)) {
                    $hfotoPath = $p;
                    break;
                }
            }
        }

        // Generate id_regis secara otomatis
        $tahun = \Carbon\Carbon::parse($request->tanggal)->format('y');
        $bulan = \Carbon\Carbon::parse($request->tanggal)->format('m');
        $st_history = $request->st_histori ?? 'XX';
        $idFormat = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
        $id_regis = "$st_history-$tahun-$bulan-$idFormat";

        Histori::create([
            'id_asetsblm'   => $aset->id_aset,
            'st_histori'    => $request->st_histori,
            'id_regis'      => $id_regis,
            'tanggal_sblm'  => $aset->date,
            'tanggal'       => $request->tanggal,
            'name_brg'      => $aset->nama_brg,
            'jenis_brg'     => $request->jenis_brg,
            'th_oleh'       => $request->th_oleh,
            'r_sebelum'     => $request->r_sebelum,
            'r_sesudah'     => $request->r_sesudah ?? $aset->code_ruang,
            'hkondisi'      => $request->hkondisi,
            'hmerk'         => $request->hmerk,
            'hseri'         => $request->hseri,
            'hfoto'         => json_encode($finalFotos),
            'hbahan'        => $request->hbahan,
            'hsize'         => $request->hsize,
            'hjum_brg'      => $request->hjum_brg,
            'hprice'        => $request->hprice,
            'ket'           => $request->ket,
            'is_delete'     => 0,
        ]);

        // 🟢 Inilah bagian penting yang perlu kamu tambahkan:
        if ($request->filled('r_sesudah')) {
            $aset->code_ruang = $request->r_sesudah;
        }
        $aset->st_aset = $request->st_histori;
        $aset->date = $request->tanggal;
        $aset->jumlah_brg = $request->hjum_brg;
        $aset->kondisi = $request->hkondisi;
        $aset->keterangan = $request->ket;

        // Tambahkan ini:
        if (in_array($request->st_histori, ['RSK', 'LLG'])) {
            $aset->is_delete = 1;
        }

        // Perbarui kolom foto pada tbl_aset untuk proses LLG juga (permintaan user)
        if (!empty($finalFotos)) {
            $allFotos = array_merge($existingFotos, $finalFotos);
            $aset->foto = json_encode($allFotos);
        }

        // Regenerasi barang_id
        $tahun = $aset->periode ? \Carbon\Carbon::parse($aset->periode)->format('Y') : '';
        $bulan = $aset->periode ? \Carbon\Carbon::parse($aset->periode)->format('m') : '';
        $idFormatted = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
        $kodeRuang = $aset->code_ruang;
        $kodeKategori = $aset->code_kategori;
        $aset->barang_id = "{$kodeRuang}-{$tahun}-{$bulan}-{$kodeKategori}-{$idFormatted}";

        $aset->save();

        return redirect()->route('histori.index')->with('success', 'Perubahan berhasil disimpan ke histori dan ruangan aset telah diperbarui.');
    }


    public function print(Request $request)
    {
        $tahun_ini = Carbon::now()->year;
        $tahun_awal = $tahun_ini - 1; // tahun sebelumnya

        // Jika user memilih baris via checkbox, hanya cetak ID tersebut
        $selectedIds = (array) $request->input('selected_asets', []);
        if (!empty($selectedIds)) {
            $selectedAsets = Aset::with(['ruang', 'kategori'])
                ->whereIn('id_aset', $selectedIds)
                ->where('is_delete', 0)
                ->get();

            // Kelompokkan per ruang (hanya dari aset terpilih)
            $grouped = $selectedAsets->groupBy('code_ruang');
            $ruangCodes = $grouped->keys()->all();
            $ruangs = KodeRuang::whereIn('code', $ruangCodes)->get();

            $ruangs->each(function ($ruang) use ($grouped, $tahun_awal, $tahun_ini) {
                $ruang->asets = $grouped->get($ruang->code, collect());
                $ruang->aset_per_tahun = [];

                // Hitung total per tahun hanya dari aset TERPILIH untuk ruangan ini
                foreach ([$tahun_ini - 1, $tahun_ini] as $tahun) {
                    $jumlah = 0;
                    $harga = 0;

                    $ruang->asets->each(function ($aset) use ($tahun, &$jumlah, &$harga) {
                        $tahunInput = $aset->date ? \Carbon\Carbon::parse($aset->date)->year : null;
                        if (!is_null($tahunInput) && $tahunInput <= $tahun) {
                            $jumlah += (int) ($aset->jumlah_brg ?? 0);
                            $harga  += (float) ($aset->harga ?? 0);
                        }
                    });

                    $ruang->aset_per_tahun[$tahun] = [
                        'jumlah' => $jumlah,
                        'harga'  => $harga,
                    ];
                }
            });

            return view('aset.print', [
                'ruangs' => $ruangs,
                'tahun_awal' => $tahun_awal,
                'tahun_akhir' => $tahun_ini,
            ]);
        }

        // Default: logika untuk filter ruang
        $filterRuangs = $request->input('ruangs', []);

        $ruangs = KodeRuang::when(!empty($filterRuangs), function ($query) use ($filterRuangs) {
            return $query->whereIn('code', $filterRuangs);
        })->get();

        $ruangs->each(function ($ruang) use ($tahun_awal, $tahun_ini) {
            $ruang->asets = Aset::with('ruang')
                ->where('code_ruang', $ruang->code)
                ->where('is_delete', 0)
                ->whereIn('st_aset', ['BL', 'HBH', 'PDH'])
                ->get();

            $ruang->aset_per_tahun = [];

            foreach ([$tahun_ini - 1, $tahun_ini] as $tahun) {
                $aset_awal = Aset::where('code_ruang', $ruang->code)
                    ->where('is_delete', 0)
                    ->whereYear('date', '<=', $tahun)
                    ->get();

                $histori_masuk = Histori::where('st_histori', 'PDH')
                    ->where('r_sesudah', $ruang->code)
                    ->whereYear('tanggal', '<=', $tahun)
                    ->get();

                $histori_keluar = Histori::where('st_histori', 'PDH')
                    ->where('r_sebelum', $ruang->code)
                    ->whereYear('tanggal', '<=', $tahun)
                    ->get();

                $aset_tahun = collect($aset_awal)->merge($histori_masuk);

                foreach ($histori_keluar as $keluar) {
                    $aset_tahun->push((object) [
                        'jumlah_brg' => -1 * $keluar->hjum_brg,
                        'harga'      => -1 * $keluar->hprice,
                        'kondisi'    => $keluar->hkondisi,
                    ]);
                }

                $ruang->aset_per_tahun[$tahun] = [
                    'jumlah' => $aset_tahun->sum('jumlah_brg'),
                    'harga'  => $aset_tahun->sum('harga'),
                ];
            }
        });

        return view('aset.print', [
            'ruangs' => $ruangs,
            'tahun_awal' => $tahun_awal,
            'tahun_akhir' => $tahun_ini,
        ]);
    }

    /**
     * Print aset dikelompokkan per kategori
     */
    public function printJenis(Request $request)
    {
        $tahun_ini  = Carbon::now()->year; // contoh: 2025
        $tahun_lalu = $tahun_ini - 1;      // contoh: 2024

        // Ambil kategori
        $selectedKategori = (array) $request->input('selected_kategori', []);
        $selectedKategori = array_values(array_unique($selectedKategori));
        if (empty($selectedKategori)) {
            $kategoriList = Kategori::where('is_delete', 0)->pluck('nama', 'kode')->toArray();
        } else {
            $kategoriList = Kategori::where('is_delete', 0)->whereIn('kode', $selectedKategori)
                ->pluck('nama', 'kode')
                ->toArray();
        }
        // Deteksi apakah semua kategori dipilih (untuk tampilan header "Data semua kategori")
        $allKategoriCodes = Kategori::where('is_delete', 0)->pluck('kode')->toArray();
        $allKategoriSelected = !empty($selectedKategori) && count(array_diff($allKategoriCodes, $selectedKategori)) === 0;

        // Ambil pilihan ruangan (opsional, mendukung multi-select) dan nama ruangnya
        $selectedRuangs = (array) $request->input('selected_ruang', []);
        $selectedRuangs = array_values(array_filter(array_unique($selectedRuangs)));
        $selectedRuangsMap = [];
        if (!empty($selectedRuangs)) {
            $selectedRuangsMap = KodeRuang::whereIn('code', $selectedRuangs)
                ->pluck('name', 'code')
                ->toArray();
        }

        // Ambil aset
        $asetsQuery = Aset::with(['kategori', 'ruang', 'histori'])
            ->where('is_delete', 0)
            ->whereIn('st_aset', ['BL', 'HBH', 'PDH']);

        if (!empty($selectedKategori)) {
            $asetsQuery->whereIn('code_kategori', $selectedKategori);
        }

        if ($request->filled('date_from')) {
            $asetsQuery->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $asetsQuery->whereDate('date', '<=', $request->date_to);
        }

        // Filter tambahan: ruangan jika dipilih di modal (multi)
        if (!empty($selectedRuangs)) {
            $asetsQuery->whereIn('code_ruang', $selectedRuangs);
        }

        $asets = $asetsQuery->get();

        // Kelompokkan
        $asetsByKategori = [];

        foreach ($asets as $aset) {
            // histori per tahun lalu (<= 31 Des 2024)
            $historiLalu = $aset->histori && $aset->histori->count() > 0
                ? $aset->histori
                ->where('tanggal', '<=', $tahun_lalu . '-12-31')
                ->sortByDesc('tanggal')
                ->first()
                : null;

            // histori per tahun ini (<= 31 Des 2025)
            $historiIni = $aset->histori && $aset->histori->count() > 0
                ? $aset->histori
                ->where('tanggal', '<=', $tahun_ini . '-12-31')
                ->sortByDesc('tanggal')
                ->first()
                : null;

            // default ke ruang awal jika belum ada histori
            $ruangLalu = $historiLalu->r_sesudah ?? $aset->code_ruang;
            $ruangIni  = $historiIni->r_sesudah ?? $aset->code_ruang;

            $asetsByKategori[$aset->code_kategori][] = [
                'aset'       => $aset,
                'ruang_lalu' => $ruangLalu,
                'ruang_ini'  => $ruangIni,
            ];
        }

        // --- Hitung total ---
        $jumlahTotalTahunLalu = 0;
        $totalHargaTahunLalu  = 0;
        $jumlahTotalTahunIni  = 0;
        $totalHargaTahunIni   = 0;

        foreach ($asets as $aset) {
            $tahunInput = $aset->date ? Carbon::parse($aset->date)->year : null;

            if ($tahunInput <= $tahun_lalu) {
                $jumlahTotalTahunLalu += $aset->jumlah_brg;
                $totalHargaTahunLalu  += $aset->harga;
            }

            if ($tahunInput <= $tahun_ini) {
                $jumlahTotalTahunIni += $aset->jumlah_brg;
                $totalHargaTahunIni  += $aset->harga;
            }
        }

        return view('aset.printjenis', [
            'asetsByKategori'       => $asetsByKategori,
            'kategoriList'          => $kategoriList,
            'selectedKategori'      => $selectedKategori,
            'selectedRuangs'        => $selectedRuangs,
            'selectedRuangsMap'     => $selectedRuangsMap,
            'allKategoriSelected'   => $allKategoriSelected,
            'jumlahTotalTahunLalu'  => $jumlahTotalTahunLalu,
            'totalHargaTahunLalu'   => $totalHargaTahunLalu,
            'jumlahTotalTahunIni'   => $jumlahTotalTahunIni,
            'totalHargaTahunIni'    => $totalHargaTahunIni,
            'tahun_lalu'            => $tahun_lalu,
            'tahun_ini'             => $tahun_ini,
        ]);
    }


    public function printQrcodeall($id_ruang = null)
    {
        $selectedIds = (array) request()->input('selected_asets', []);
        $query = Aset::with('ruang')->where('is_delete', 0);
        if (!empty($selectedIds)) {
            $query->whereIn('id_aset', $selectedIds);
        } elseif (!is_null($id_ruang)) {
            $query->where('code_ruang', $id_ruang);
        }
        $asets = $query->get();

        $regen = request()->boolean('regen');
        foreach ($asets as $aset) {
            $qrContent = preg_replace('/[^A-Za-z0-9_\-]/', '_', $aset->id_aset);
            $fileName = 'qrcode_' . $qrContent . '.png';
            $path = storage_path('app/public/qrcodes/' . $fileName);

            // Pastikan file QR ada; jika belum ada, buat dengan margin & ECC
            if ($regen || !file_exists($path)) {
                QrCode::format('png')
                    ->size(300)
                    ->margin(4)
                    ->errorCorrection('H')
                    ->generate($aset->id_aset, $path);
            }

            $aset->setAttribute('qr_path', asset('storage/qrcodes/' . $fileName));
            $aset->setAttribute('ruang_label', $aset->ruang->name);
            $aset->setAttribute('kode_label', "KODE {$aset->barang_id}");
        }

        return view('aset.printqrcodeall', compact('asets'));
    }

    public function cetakQrcode($id_aset = null)
    {
        $query = Aset::with('ruang')->where('is_delete', 0);
        // Jika dipanggil dengan parameter id_aset, cetak hanya aset tersebut
        if (!is_null($id_aset)) {
            $query->where('id_aset', $id_aset);
        }
        $asets = $query->get();

        $regen = request()->boolean('regen');
        foreach ($asets as $aset) {
            $qrContent = preg_replace('/[^A-Za-z0-9_\-]/', '_', $aset->id_aset);
            $fileName = 'qrcode_' . $qrContent . '.png';
            $path = storage_path('app/public/qrcodes/' . $fileName);

            // Pastikan file QR ada; jika belum ada, buat dengan margin & ECC
            if ($regen || !file_exists($path)) {
                QrCode::format('png')
                    ->size(300)
                    ->margin(4)
                    ->errorCorrection('H')
                    ->generate($aset->id_aset, $path);
            }

            $aset->setAttribute('qr_path', asset('storage/qrcodes/' . $fileName));
            $aset->setAttribute('ruang_label', $aset->ruang->name);
            $aset->setAttribute('kode_label', "KODE {$aset->barang_id}");
        }

        return view('aset.cetakqrcode', compact('asets'));
    }
    /**
     * Tampilkan halaman opname histori index
     */
    public function opnamHistoriIndex(Request $request)
    {
        $kategori = Kategori::all();
        $ruang = KodeRuang::where('is_delete', 0)->get();
        $filteredAsets = null;

        if (
            $request->filled('date') ||
            $request->filled('periode') ||
            $request->filled('nama_brg') ||
            $request->filled('merk') ||
            $request->filled('seri') ||
            $request->filled('kondisi') ||
            $request->filled('code_kategori') ||
            $request->filled('code_ruang')
        ) {
            $query = Aset::with(['ruang', 'kategori'])->where('is_delete', 0);

            if ($request->filled('date')) {
                $query->whereDate('date', \Carbon\Carbon::parse($request->date));
            }
            if ($request->filled('periode')) {
                $query->whereYear('periode', \Carbon\Carbon::parse($request->periode)->format('Y'));
            }
            if ($request->filled('nama_brg')) {
                $query->where('nama_brg', 'like', '%' . $request->nama_brg . '%');
            }
            if ($request->filled('merk')) {
                $query->where('merk', 'like', '%' . $request->merk . '%');
            }
            if ($request->filled('seri')) {
                $query->where('no_seri', 'like', '%' . $request->seri . '%');
            }
            if ($request->filled('kondisi')) {
                $query->where('kondisi', $request->kondisi);
            }
            if ($request->filled('code_kategori')) {
                $query->where('code_kategori', $request->code_kategori);
            }
            if ($request->filled('code_ruang')) {
                $query->where('code_ruang', $request->code_ruang);
            }

            $filteredAsets = $query->get();
        }

        return view('opnamhistori.index', compact('kategori', 'ruang', 'filteredAsets'));
    }

    public function lelangHistoriIndex(Request $request)
    {
        $kategori = Kategori::all();
        $ruang = KodeRuang::where('is_delete', 0)->get();
        $filteredAsets = null;

        if (
            $request->filled('date') ||
            $request->filled('periode') ||
            $request->filled('nama_brg') ||
            $request->filled('merk') ||
            $request->filled('seri') ||
            $request->filled('kondisi') ||
            $request->filled('code_kategori') ||
            $request->filled('code_ruang')
        ) {
            $query = Aset::with(['ruang', 'kategori'])->where('is_delete', 0);

            if ($request->filled('date')) {
                $query->whereDate('date', \Carbon\Carbon::parse($request->date));
            }
            if ($request->filled('periode')) {
                $query->whereYear('periode', \Carbon\Carbon::parse($request->periode)->format('Y'));
            }
            if ($request->filled('nama_brg')) {
                $query->where('nama_brg', 'like', '%' . $request->nama_brg . '%');
            }
            if ($request->filled('merk')) {
                $query->where('merk', 'like', '%' . $request->merk . '%');
            }
            if ($request->filled('seri')) {
                $query->where('no_seri', 'like', '%' . $request->seri . '%');
            }
            if ($request->filled('kondisi')) {
                $query->where('kondisi', $request->kondisi);
            }
            if ($request->filled('code_kategori')) {
                $query->where('code_kategori', $request->code_kategori);
            }
            if ($request->filled('code_ruang')) {
                $query->where('code_ruang', $request->code_ruang);
            }

            $filteredAsets = $query->get();
        }

        return view('brglelang.index', compact('kategori', 'ruang', 'filteredAsets'));
    }

    /**
     * Tampilkan form import Excel
     */
    public function importForm()
    {
        return view('aset.import');
    }

    /**
     * Proses import Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');

            Log::info('Mulai import Excel', ['filename' => $file->getClientOriginalName()]);

            // Import data
            Excel::import(new AsetImport, $file);

            Log::info('Import Excel selesai');

            return redirect()->route('aset.index')
                ->with('success', 'Data aset berhasil diimpor dari Excel.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            Log::error('Validation error saat import', ['errors' => $errors]);

            return redirect()->route('aset.index')
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . implode(' | ', $errors));
        } catch (\Exception $e) {
            Log::error('Error saat import Excel', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('aset.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' (Cek log untuk detail)');
        }
    }
}
