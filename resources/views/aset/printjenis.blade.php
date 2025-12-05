<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            /* dari 30px jadi 10px */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        h2,
        h3,
        h4 {
            text-align: center;
            margin: 0;
        }

        p {
            margin: 4px 0;
        }

        .header-info {
            margin-bottom: 10px;
            width: 100%;
            display: flex;
            justify-content: space-between;
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


        .header-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 4px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
        }

        .info-value {
            flex: 1;
        }

        .label {
            width: 80px;
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

        .separator {
            width: 10px;
        }

        .header-info-right {
            margin-left: auto;
            width: 20%;
            /* Atur lebar agar tidak terlalu lebar */
            text-align: left;
        }

        .kategori-row td {
            text-align: left;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        @media print {
            .kategori-row td {
                background-color: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            button {
                display: none;
            }

            @page {
                /* F4 Landscape: width x height */
                size: 330mm 210mm;
                margin: 10mm;
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
                height: auto !important;
            }

            table {
                font-size: 10px;
                width: 100%;
                margin-left: auto;
                /* center horizontally */
                margin-right: auto;
                /* center horizontally */
            }

            th,
            td {
                font-size: 8px;
            }

            .signature {
                margin-top: 5px;
                /* Atur sesuai kebutuhan */
                page-break-inside: avoid;
                break-inside: avoid;
                gap: 450px;
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

    <h3>REKAPITULASI BERDASARKAN JENIS BARANG PER 31 DESEMBER {{ now()->year }}</h3>
    <h3>PERALATAN KANTOR, TEKNIK GUDANG, BENGKEL, PERHUBUNGAN, LABORAT DAN ANGKUTAN</h3>

    <div class="header-container">
        <div class="header-info-left">
            <div class="info-row">
                <div class="label">Provinsi</div>
                <div class="separator">:</div>
                <div>Jawa Tengah</div>
            </div>
            <div class="info-row">
                <div class="label">Unit</div>
                <div class="separator">:</div>
                <div>Perumda Air Minum Kota Magelang</div>
            </div>
        </div>

        <div class="header-info-right">
            <div class="info-row">
                <div class="label">Kategori</div>
                <div class="separator">:</div>
                <div>
                    @if(!empty($allKategoriSelected) && $allKategoriSelected)
                    Data semua kategori
                    @elseif(isset($selectedKategori) && count($selectedKategori) > 0)
                    @foreach($selectedKategori as $kode)
                    @if(isset($kategoriList[$kode]))
                    {{ $kode }} - {{ $kategoriList[$kode] }}@if(!$loop->last), @endif
                    @endif
                    @endforeach
                    @else
                    Semua Kategori
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="label">Tahun</div>
                <div class="separator">:</div>
                <div>Per 31 Desember {{ now()->year }}</div>
            </div>
            <div class="info-row">
                <div class="label">Ruangan</div>
                <div class="separator">:</div>
                <div>
                    @if(isset($selectedRuangs) && is_array($selectedRuangs) && count($selectedRuangs) > 0)
                    @foreach($selectedRuangs as $code)
                    {{ $code }} - {{ $selectedRuangsMap[$code] ?? '-' }}@if(!$loop->last), @endif
                    @endforeach
                    @else
                    Data semua ruangan
                    @endif
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:10px;" rowspan="3">No</th>
                <th style="width:140px;" rowspan="3">No. Kode Barang</th>
                <th style="width:110px;" rowspan="3">Nama Barang</th>
                <th style="width:100px;" rowspan="3">Merk / Modal</th>
                <th style="width:100px;" rowspan="3">No Seri Pabrik</th>
                <th style="width:100px;" rowspan="3">Ukuran</th>
                <th style="width:100px;" rowspan="3">Bahan</th>
                <th style="width:20px;" rowspan="3">Tahun Pembelian</th>
                <th colspan="4">Situasi Barang</th>
                <th colspan="3" rowspan="2">Kondisi Barang</th>
                <th style="width:180px;" rowspan="3">Keterangan</th>
            </tr>
            <tr>
                <th colspan="2">{{ now()->subYear()->year }}</th>
                <th colspan="2">{{ now()->year }}</th>
            </tr>
            <tr>
                <th style="width:50px;">Jumlah Brg</th>
                <th style="width:70px;">Harga Beli</th>
                <th style="width:50px;">Jumlah Brg</th>
                <th style="width:70px;">Harga Beli</th>
                <th style="width:50px;">Baik</th>
                <th style="width:50px;">Kurang Baik</th>
                <th style="width:50px;">Rusak</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            $tahunLalu = now()->subYear()->year;
            $tahunIni = now()->year;
            @endphp

            @foreach ($kategoriList as $kodeKategori => $namaKategori)
            <tr class="kategori-row">
                <td style="width:80px;" colspan="16">{{ $namaKategori }}</td>
            </tr>

            @if(isset($asetsByKategori[$kodeKategori]) && count($asetsByKategori[$kodeKategori]) > 0)
            @foreach ($asetsByKategori[$kodeKategori] as $asetData)
            @php
            $aset = $asetData['aset'];
            $tahunAset = $aset->date ? \Carbon\Carbon::parse($aset->date)->year : null;
            @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $aset->barang_id ?? '-' }}</td>
                <td>{{ $aset->nama_brg ?? '-' }}</td>
                <td>{{ $aset->merk ?? '-' }}</td>
                <td>{{ $aset->seri ?? '-' }}</td>
                <td>{{ $aset->ukuran ?? '-' }}</td>
                <td>{{ $aset->bahan ?? '-' }}</td>
                <td>{{ $aset->date ? date('Y', strtotime($aset->date)) : '-' }}</td>

                {{-- Kolom Tahun Lalu --}}
                <td>{{ $tahunAset && $tahunAset <= $tahunLalu ? $aset->jumlah_brg . ' ' . $aset->satuan : '-' }}</td>
                <td class="text-right">{{ $tahunAset && $tahunAset <= $tahunLalu && isset($aset->harga) ? number_format($aset->harga, 0, ',', '.') : '-' }}</td>

                {{-- Kolom Tahun Ini --}}
                <td>{{ $tahunAset && $tahunAset <= $tahunIni ? $aset->jumlah_brg . ' ' . $aset->satuan : '-' }}</td>
                <td class="text-right">{{ $tahunAset && $tahunAset <= $tahunIni && isset($aset->harga) ? number_format($aset->harga, 0, ',', '.') : '-' }}</td>

                {{-- Kondisi Barang --}}
                <td>{{ $aset->kondisi == 'Baik' ? $aset->jumlah_brg : 0 }}</td>
                <td>{{ $aset->kondisi == 'Kurang Baik' ? $aset->jumlah_brg : 0 }}</td>
                <td>{{ $aset->kondisi == 'Rusak Berat' ? $aset->jumlah_brg : 0 }}</td>

                <td class="text-left">{{ $aset->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="16" style="text-align:center;">Tidak ada data</td>
            </tr>
            @endif
            @endforeach
        </tbody>


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