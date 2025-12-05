<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
            /* Ukuran font default halaman */
            padding-left: 5mm;
            padding-right: 5mm;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            margin-right: 30px;
            margin-left: 30px;
            /* Menjorok masuk dari kanan */
        }

        .info-column {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-row {
            display: flex;
            gap: 8px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
        }

        .info-value {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* Ukuran font khusus tabel */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 5px;
            /* naik lebih dekat ke tabel */
            padding-top: 0;
            /* hilangkan jarak tambahan */
            border-top: none;
            /* hilangkan garis */
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature {
            margin-top: 40px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            /* ganti dari space-between */
            padding: 0 40px;
            font-size: 11px;
        }

        .separator {
            width: 10px;
        }

        @media print {
            .header {
                margin-bottom: 5px;
                /* hilangkan jarak bawah */
                padding-bottom: 5px;
            }

            .header h2 {
                margin: 0;
                font-size: 10px;
                font-weight: bold;
            }

            .header p {
                margin: 0;
                font-size: 10px;
            }

            button {
                display: none;
            }

            @page {
                /* F4 Portrait: width x height */
                size: 210mm 330mm;
                margin: 0;
            }

            html {
                width: auto !important;
                height: auto !important;
            }

            body {
                margin: 0;
                font-size: 9px;
                /* Ukuran font lebih kecil saat print */
                padding-left: 0 !important;
                padding-right: 0 !important;
                width: auto !important;
                height: auto !important;
            }

            html {
                width: auto !important;
                /* biarkan browser menghitung */
                height: auto !important;
            }

            table {
                margin-top: 5px;
                font-size: 10px;
                width: 100%;
                table-layout: fixed;
                border-collapse: collapse;
                margin-left: auto;
                /* center horizontally */
                margin-right: auto;
                /* center horizontally */
            }

            th,
            td {
                font-size: 7px;
                padding: 3px;
                word-break: break-word;
                white-space: normal;
                overflow-wrap: anywhere;
            }

            .table-laporan thead th {
                background-color: rgb(219, 219, 219) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table-laporan tfoot th {
                background-color: rgb(219, 219, 219) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* 1-8 kolom tetap (No s.d. Ukuran) */
            tbody td:nth-child(1) {
                width: 4mm;
                padding: 1px;
                font-size: 6px;
            }

            tbody td:nth-child(2) {
                width: 18mm;
            }

            tbody td:nth-child(3) {
                width: 26mm;
            }

            tbody td:nth-child(4) {
                width: 14mm;
            }

            tbody td:nth-child(5) {
                width: 10mm;
            }

            tbody td:nth-child(6) {
                width: 10mm;
            }

            tbody td:nth-child(7) {
                width: 10mm;
            }

            tbody td:nth-child(8) {
                width: 5mm;
                padding: 1px;
                word-break: break-all;
            }

            /* Kolom dinamis per tahun (mulai kolom ke-9 sampai sebelum 3 kolom terakhir) */
            tbody td:nth-child(n+9):not(:nth-last-child(-n+3)) {
                width: 8mm;
            }

            /* Perkecil kolom "Jumlah Brg" (kolom dinamis ganjil mulai dari ke-9) */
            tbody td:nth-child(2n+9):not(:nth-last-child(-n+3)) {
                width: 2.5mm;
                padding: 1px;
            }

            /* Opsional: sedikit perbesar 3 kolom kondisi di akhir */
            tbody td:nth-last-child(3),
            tbody td:nth-last-child(2),
            tbody td:last-child {
                width: 4mm;
                padding: 1px;
                font-size: 6px;
            }

            /* Header: paksa lebar kolom No dan 3 kolom kondisi saat cetak */
            thead tr:first-child th:first-child {
                /* No */
                width: 4mm !important;
                padding: 1px !important;
            }

            /* Header: paksa lebar kolom ke-8 (Ukuran) agar mengikuti 5mm */
            thead tr:first-child th:nth-child(8) {
                width: 5mm !important;
                padding: 1px !important;
            }

            /* Header: kolom 'Kondisi Barang' (row 1, kolom terakhir) = total 3 kolom kondisi */
            thead tr:first-child th:last-child {
                width: 15mm !important;
                /* 3 x 4mm */
            }

            /* Default untuk Harga Beli */
            thead tr:nth-child(3) th:not(:nth-last-child(-n+3)) {
                width: 10mm !important;
            }

            /* Jumlah Brg */
            thead tr:nth-child(3) th:nth-child(odd):not(:nth-last-child(-n+3)) {
                width: 5mm !important;
                padding: 1px !important;
            }


            thead tr:last-child th:nth-last-child(3),
            thead tr:last-child th:nth-last-child(2),
            thead tr:last-child th:last-child {
                width: 5mm !important;
                padding: 1px !important;
                font-size: 6px !important;
            }

            /* Foot: samakan lebar pada baris total */
            tfoot th:nth-last-child(3),
            tfoot th:nth-last-child(2),
            tfoot th:last-child {
                width: 5mm !important;
                padding: 1px !important;
                font-size: 6px !important;
            }

            .signature {
                margin-top: 10px;
                /* Jarak ke bawah */
                display: flex;
                justify-content: space-between;
                text-align: center;
                font-size: 8px;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .signature>div:first-child {
                padding-left: 1px;
                /* geser Koordinator Opname ke kanan */
            }

            .signature>div:last-child {
                padding-right: 1px;
                /* geser Sekretaris Opname ke kiri */
            }

            /* atur area cetak agar pas ukuran F4 */
            #print-area {
                width: 210mm;
                min-height: 330mm;
                padding: 5mm;
                box-sizing: border-box;
            }

            #waktu-cetak {
                font-size: 10px;
                margin-bottom: 20px;
                text-align: left;
                margin-right: 10px;
                width: auto;
                margin-top: 5px;
            }
        }
    </style>

</head>

<body>
    <div class="header">
        <h2>Rekap Aset Per (31 Desember {{ now()->year }})</h2>
        <p>
        <h2>Peralatan Kantor, Teknik, Gudang, Bengkel, Perhubungan, Laborat dan Angkutan</h2>
        </p>
    </div>

    <table class="table-laporan">
        <colgroup>
            <col style="width:4mm">
            <col style="width:18mm">
            <col style="width:26mm">
            <col style="width:14mm">
            <col style="width:10mm">
            <col style="width:10mm">
            <col style="width:10mm">
            <col style="width:10mm">
            @for($tahun = $tahun_awal; $tahun <= $tahun_akhir; $tahun++)
                <col style="width:6mm"> <!-- Jumlah Brg -->
                <col style="width:10mm"> <!-- Harga Beli -->
                @endfor
                <col style="width:5mm"> <!-- Baik -->
                <col style="width:5mm"> <!-- Kurang Baik -->
                <col style="width:5mm"> <!-- Rusak Berat -->
        </colgroup>
        <thead>
            <tr>
                <th rowspan="3">No</th>
                <th rowspan="3">Kode Aset</th>
                <th rowspan="3">Bagian/Ruang</th>
                <th rowspan="3">Nama Brg</th>
                <th rowspan="3">Merk</th>
                <th rowspan="3">No. Seri</th>
                <th rowspan="3">Bahan</th>
                <th rowspan="3">Ukuran</th>
                <th colspan="{{ ($tahun_akhir - $tahun_awal + 1) * 2 }}">Situasi Barang</th>
                <th colspan="3" rowspan="2">Kondisi Barang</th>
            </tr>
            <tr>
                @for($tahun = $tahun_awal; $tahun <= $tahun_akhir; $tahun++)
                    <th colspan="2">{{ $tahun }}</th>
                    @endfor
            </tr>
            <tr>
                @for($tahun = $tahun_awal; $tahun <= $tahun_akhir; $tahun++)
                    <th>Jumlah Brg</th>
                    <th>Harga Beli</th>
                    @endfor
                    <th style="width:80px;">Baik</th>
                    <th style="width:80px;">Kurang Baik</th>
                    <th style="width:80px;">Rusak Berat</th>
            </tr>
        </thead>

        <tbody>
            @php $no = 1; @endphp
            @foreach ($ruangs as $ruang)
            @foreach ($ruang->asets as $aset)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $aset->barang_id }}</td>
                <td class="text-left">{{ $ruang->name }}</td>
                <td>{{ $aset->nama_brg }}</td>
                <td>{{ $aset->merk }}</td>
                <td>{{ $aset->seri }}</td>
                <td>{{ $aset->bahan }}</td>
                <td>{{ $aset->ukuran }}</td>

                {{-- Loop per tahun --}}
                @for($tahun = $tahun_awal; $tahun <= $tahun_akhir; $tahun++)
                    @php
                    // Cek apakah aset ini ada pada tahun tersebut
                    $asetTahun=\Carbon\Carbon::parse($aset->date)->year;
                    $periodeTahun = \Carbon\Carbon::parse($aset->periode)->year;

                    // Tampilkan data aset jika tahun input <= tahun yang ditampilkan
                        if ($asetTahun <=$tahun) {
                        $jumlah=$aset->jumlah_brg;
                        $harga = $aset->harga;
                        } else {
                        $jumlah = 0;
                        $harga = 0;
                        }
                        @endphp
                        <td class="text-right">{{ $jumlah ? $jumlah . ' ' . $aset->satuan : '-' }}</td>
                        <td class="text-right">{{ $harga ? number_format($harga, 0, ',', '.') : '-' }}</td>
                        @endfor

                        {{-- Kondisi (hanya tahun terakhir, misalnya) --}}
                        @php
                        $kondisiBaik = $aset->kondisi == 'Baik' ? $aset->jumlah_brg : 0;
                        $kondisiKurangBaik = $aset->kondisi == 'Kurang Baik' ? $aset->jumlah_brg : 0;
                        $kondisiRusakBerat = $aset->kondisi == 'Rusak Berat' ? $aset->jumlah_brg : 0;
                        @endphp
                        <td>{{ $kondisiBaik }}</td>
                        <td>{{ $kondisiKurangBaik }}</td>
                        <td>{{ $kondisiRusakBerat }}</td>
            </tr>
            @endforeach
            @endforeach

        </tbody>
        <tfoot class="table-laporan">
            <tr>
                <th colspan="8">Total</th>
                @for($tahun = $tahun_awal; $tahun <= $tahun_akhir; $tahun++)
                    @php
                    $totalJumlah=0;
                    $totalHarga=0;
                    foreach($ruangs as $r) {
                    $totalJumlah +=$r->aset_per_tahun[$tahun]['jumlah'] ?? 0;
                    $totalHarga += $r->aset_per_tahun[$tahun]['harga'] ?? 0;
                    }
                    @endphp
                    <th class="text-right">{{ $totalJumlah }} item</th>
                    <th class="text-right">Rp.{{ number_format($totalHarga, 0, ',', '.') }}</th>
                    @endfor

                    <th>{{ $ruangs->flatMap->asets->where('kondisi', 'Baik')->sum('jumlah_brg') }}</th>
                    <th>{{ $ruangs->flatMap->asets->where('kondisi', 'Kurang Baik')->sum('jumlah_brg') }}</th>
                    <th>{{ $ruangs->flatMap->asets->where('kondisi', 'Rusak Berat')->sum('jumlah_brg') }}</th>
            </tr>
        </tfoot>

    </table>
    <div class="summary">
        <div class="summary-row">
            <span class="info-label">Dicetak pada</span>
            <div class="separator">:</div>
            <span class="info-value"> {{ date('d-m-Y H:i:s') }}</span>
        </div>
        <div class="summary-row">
            <span class="info-label">Dicetak Oleh</span>
            <div class="separator">:</div>
            <span class="info-value"> {{ auth()->user()->name ?? 'System' }}</span>
        </div>
    </div>

    <div class="signature">
        <div style="text-align: center; width: 300px;">
            Koordinator Opname,<br><br><br><br>
            <strong>Nama</strong><br>
            NIK. 12345
        </div>
        <div style="text-align: center; width: 300px;">
            Sekretaris Opname,<br><br><br><br>
            <strong>Nama</strong><br>
            NIK. 12345
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <script>
        const now = new Date();
        const pad = n => n.toString().padStart(2, '0');
        const tgl = `${pad(now.getDate())}-${pad(now.getMonth() + 1)}-${now.getFullYear()}`;
        const jam = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
        document.getElementById('waktu-cetak').textContent = `Dicetak pada: ${tgl} ${jam}`;
    </script>
</body>

</html>