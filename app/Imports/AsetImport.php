<?php

namespace App\Imports;

use App\Models\Aset;
use App\Models\KodeRuang;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AsetImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithChunkReading
{
    use SkipsFailures;

    /**
     * Mapping kolom Excel ke field database
     * Handle berbagai variasi nama kolom
     */
    private function mapColumn($row, $excelNames, $defaultKey)
    {
        // Normalisasi semua key di row untuk pencarian
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(str_replace(['_', ' ', '-'], '', $key));
            $normalizedRow[$normalizedKey] = $value;
        }

        // Cari di normalized row
        foreach ($excelNames as $name) {
            $normalized = strtolower(str_replace(['_', ' ', '-'], '', $name));
            if (isset($normalizedRow[$normalized])) {
                return $normalizedRow[$normalized];
            }
        }

        // Fallback ke default key jika ada
        if (isset($row[$defaultKey])) {
            return $row[$defaultKey];
        }

        return null;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Log untuk debugging
        Log::info('Import row data:', $row);

        // Mapping kolom dengan berbagai variasi nama
        $codeRuang = $this->mapColumn($row, ['code_ruang', 'code_ru', 'code ruang', 'code ru'], 'code_ruang');
        $codeKategori = $this->mapColumn($row, ['code_kategori', 'code_katego', 'code kategori', 'code katego'], 'code_kategori');
        $namaBrg = $this->mapColumn($row, ['nama_brg', 'nama_b', 'nama barang', 'nama brg'], 'nama_brg');
        $jumlahBrg = $this->mapColumn($row, ['jumlah_brg', 'jumlah', 'jumlah barang'], 'jumlah_brg');

        // Validasi code_ruang dan code_kategori
        if (empty($codeRuang)) {
            Log::error('Code ruang kosong', $row);
            throw new \Exception("Code ruang tidak ditemukan di baris ini.");
        }

        if (empty($codeKategori)) {
            Log::error('Code kategori kosong', $row);
            throw new \Exception("Code kategori tidak ditemukan di baris ini.");
        }

        // Convert ke string jika numeric (Excel mungkin membaca sebagai number)
        $codeRuang = (string) $codeRuang;
        $codeKategori = (string) $codeKategori;

        $ruang = KodeRuang::where('code', $codeRuang)->where('is_delete', 0)->first();
        $kategori = Kategori::where('kode', $codeKategori)->where('is_delete', 0)->first();

        if (!$ruang) {
            Log::error("Code ruang tidak ditemukan: {$codeRuang}", $row);
            throw new \Exception("Code ruang '{$codeRuang}' tidak ditemukan atau sudah dihapus.");
        }

        if (!$kategori) {
            Log::error("Code kategori tidak ditemukan: {$codeKategori}", $row);
            throw new \Exception("Code kategori '{$codeKategori}' tidak ditemukan atau sudah dihapus.");
        }

        // Parse tanggal periode - handle berbagai format
        $periode = now();
        if (!empty($row['periode'])) {
            try {
                $periodeValue = $row['periode'];
                // Jika numeric, kemungkinan Excel date serial number
                if (is_numeric($periodeValue)) {
                    // Excel date serial number (dimulai dari 1900-01-01)
                    $excelEpoch = Carbon::create(1899, 12, 30);
                    $periode = $excelEpoch->addDays((int)$periodeValue);
                } else {
                    // Handle format YYYY/MM/DD atau YYYY-MM-DD
                    $periodeValue = str_replace('/', '-', $periodeValue);
                    $periode = Carbon::createFromFormat('Y-m-d', $periodeValue);
                    if (!$periode) {
                        // Fallback ke parse biasa
                        $periode = Carbon::parse($row['periode']);
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Gagal parse periode: {$row['periode']}, menggunakan tanggal sekarang", ['error' => $e->getMessage()]);
                $periode = now();
            }
        }

        // Parse tanggal date - handle berbagai format
        $date = now()->format('Y-m-d');
        if (!empty($row['date'])) {
            try {
                $dateValue = $row['date'];
                // Jika numeric, kemungkinan Excel date serial number
                if (is_numeric($dateValue)) {
                    $excelEpoch = Carbon::create(1899, 12, 30);
                    $date = $excelEpoch->addDays((int)$dateValue)->format('Y-m-d');
                } else {
                    // Handle format YYYY/MM/DD atau YYYY-MM-DD
                    $dateValue = str_replace('/', '-', $dateValue);
                    $parsedDate = Carbon::createFromFormat('Y-m-d', $dateValue);
                    if ($parsedDate) {
                        $date = $parsedDate->format('Y-m-d');
                    } else {
                        // Fallback ke parse biasa
                        $date = Carbon::parse($row['date'])->format('Y-m-d');
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Gagal parse date: {$row['date']}, menggunakan tanggal sekarang", ['error' => $e->getMessage()]);
                $date = now()->format('Y-m-d');
            }
        }

        // Normalisasi harga - handle koma sebagai pemisah ribuan
        $harga = 0;
        if (!empty($row['harga'])) {
            $hargaStr = trim($row['harga']);
            // Hapus semua karakter non-numeric kecuali titik dan koma
            $hargaStr = preg_replace('/[^0-9,.]/', '', $hargaStr);
            // Jika ada koma, kemungkinan pemisah ribuan (format Indonesia: 9,700,000)
            // Jika ada titik, kemungkinan desimal (format: 9700000.00)
            if (strpos($hargaStr, ',') !== false && strpos($hargaStr, '.') === false) {
                // Hanya koma = pemisah ribuan, hapus semua koma
                $harga = floatval(str_replace(',', '', $hargaStr));
            } elseif (strpos($hargaStr, '.') !== false && strpos($hargaStr, ',') !== false) {
                // Ada koma dan titik = format campuran, cek posisi
                $lastComma = strrpos($hargaStr, ',');
                $lastDot = strrpos($hargaStr, '.');
                if ($lastComma > $lastDot) {
                    // Koma terakhir = desimal (format Eropa: 9.700.000,00)
                    $harga = floatval(str_replace(['.', ','], ['', '.'], $hargaStr));
                } else {
                    // Titik terakhir = desimal (format US: 9,700,000.00)
                    $harga = floatval(str_replace(',', '', $hargaStr));
                }
            } else {
                // Hanya titik atau tanpa pemisah
                $harga = floatval(str_replace(',', '', $hargaStr));
            }
        }

        // Normalisasi jumlah_brg
        $jumlah_brg = !empty($jumlahBrg) ? intval($jumlahBrg) : (!empty($row['jumlah_brg']) ? intval($row['jumlah_brg']) : 1);

        // Validasi kondisi - handle case insensitive
        $kondisiRaw = $row['kondisi'] ?? 'Baik';
        $kondisiLower = strtolower(trim($kondisiRaw));
        $kondisiMap = [
            'baik' => 'Baik',
            'kurang baik' => 'Kurang Baik',
            'rusak berat' => 'Rusak Berat',
        ];
        $kondisi = $kondisiMap[$kondisiLower] ?? 'Baik';

        // Validasi st_aset
        $st_aset = $row['st_aset'] ?? 'BL';
        if (!in_array($st_aset, ['BL', 'HBH'])) {
            $st_aset = 'BL';
        }

        // Handle nilai null atau string "null"
        $keterangan = $row['keterangan'] ?? null;
        if ($keterangan === 'null' || $keterangan === '' || strtolower(trim($keterangan)) === 'null') {
            $keterangan = null;
        }

        $seri = $row['seri'] ?? null;
        if ($seri === '-' || $seri === '' || strtolower(trim($seri)) === 'null') {
            $seri = null;
        }

        $ukuran = $row['ukuran'] ?? null;
        if ($ukuran === '-' || $ukuran === '' || strtolower(trim($ukuran)) === 'null') {
            $ukuran = null;
        }

        // Buat aset baru
        // JANGAN set id_aset karena auto-increment
        $aset = new Aset([
            'nama_brg'      => $namaBrg ?: ($row['nama_brg'] ?? ''),
            'merk'          => $row['merk'] ?? '',
            'seri'          => $seri,
            'bahan'         => $row['bahan'] ?? '',
            'ukuran'        => $ukuran,
            'periode'       => $periode ? $periode->format('Y-m-d') : now()->format('Y-m-d'),
            'date'          => $date,
            'keterangan'    => $keterangan,
            'jumlah_brg'    => $jumlah_brg,
            'harga'         => $harga,
            'kondisi'       => $kondisi,
            'code_ruang'    => $codeRuang,
            'code_kategori' => $codeKategori,
            'st_aset'       => $st_aset,
            'satuan'        => $row['satuan'] ?? 'unit',
            'foto'          => json_encode([]),
            'is_delete'     => 0,
            // JANGAN set id_aset atau barang_id dari Excel
            // id_aset akan auto-increment, barang_id akan di-generate setelah save
        ]);

        // Simpan untuk mendapatkan ID
        try {
            $aset->save();
            Log::info("Aset berhasil disimpan: ID {$aset->id_aset}, Nama: {$aset->nama_brg}");
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan aset", ['error' => $e->getMessage(), 'data' => $aset->toArray()]);
            throw $e;
        }

        // Generate barang_id setelah aset disimpan
        // Update langsung ke database untuk menghindari activity log kedua
        $tahun = Carbon::parse($aset->periode)->format('Y');
        $bulan = Carbon::parse($aset->periode)->format('m');
        $idFormatted = str_pad($aset->id_aset, 4, '0', STR_PAD_LEFT);
        $kodeRuang = $aset->code_ruang;
        $kodeKategori = $aset->code_kategori;

        $barang_id = "{$kodeRuang}-{$tahun}-{$bulan}-{$kodeKategori}-{$idFormatted}";

        // Update langsung ke database tanpa memicu Eloquent events
        // Ini mencegah activity log kedua (updated)
        DB::table('tbl_aset')
            ->where('id_aset', $aset->id_aset)
            ->update(['barang_id' => $barang_id]);

        // Update juga di instance model agar konsisten
        $aset->barang_id = $barang_id;

        return $aset;
    }

    /**
     * Validasi rules
     */
    public function rules(): array
    {
        // Validasi lebih fleksibel karena kita handle mapping di model()
        // DenganHeadingRow akan membaca header Excel, jadi kita validasi semua kemungkinan kolom
        return [
            'nama_brg' => 'nullable|string|max:50',
            'nama_b' => 'nullable|string|max:50',
            'merk' => 'nullable|string',
            'bahan' => 'nullable|string',
            'code_ruang' => 'nullable|string|max:20',
            'code_ru' => 'nullable|string|max:20',
            'code_kategori' => 'nullable|string|max:20',
            'code_katego' => 'nullable|string|max:20',
            'harga' => 'nullable|numeric|min:0',
            'kondisi' => 'nullable|string',
            'st_aset' => 'nullable|in:BL,HBH',
            'jumlah_brg' => 'nullable|integer|min:1',
            'jumlah' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'nama_brg.required' => 'Nama barang wajib diisi.',
            'merk.required' => 'Merk wajib diisi.',
            'bahan.required' => 'Bahan wajib diisi.',
            'code_ruang.required' => 'Code ruang wajib diisi.',
            'code_kategori.required' => 'Code kategori wajib diisi.',
        ];
    }

    /**
     * Chunk size untuk membaca file
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
