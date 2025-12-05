<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
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
            font-size: 8px
        }

        .info-value {
            flex: 1;
            font-size: 8px
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            button {
                display: none;
            }

            .no-print {
                display: none !important;
            }

            @page {
                /* F4 Landscape: width x height */
                size: 330mm 210mm;
                margin: 10mm;
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

            table {
                font-size: 10px;
                width: 100%;
                margin-left: auto;
                margin-right: auto;
            }

            th,
            td {
                font-size: 9px;
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

            #waktu-cetak {
                font-size: 10px;
                margin-bottom: 20px;
                text-align: left;
                margin-right: 10px;
                width: auto;
                margin-top: 5px;
            }

            #print-area {
                width: 330mm;
                min-height: 210mm;
                padding: 5mm;
                box-sizing: border-box;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>OPNAME PER 31 DESEMBER {{ now()->year }}</h2>
        <p>
        <h2>PERALATAN KANTOR, TEKNIK GUDANG, BENGKEL, PERHUBUNGAN, LABORAT DAN ANGKUTAN</h2>
        </p>
    </div>

    <div class="info-section">
        <div class="info-column">
            <div class="info-row">
                <span class="info-label">Provinsi</span>
                <div class="separator">:</div>
                <span class="info-value">Jawa Tengah</span>
            </div>
            <div class="info-row">
                <span class="info-label">Unit</span>
                <div class="separator">:</div>
                <span class="info-value">Perumda Air Minum Kota Magelang</span>
            </div>
        </div>

        <div class="info-column">
            <div class="info-row">
                <span class="info-label">Ruangan</span>
                <div class="separator">:</div>
                <span class="info-value">
                    @if(isset($ruangs) && count($ruangs) > 0)
                    {{ $ruangs->pluck('name')->implode(', ') }}
                    @else
                    -
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Tahun</span>
                <div class="separator">:</div>
                <span class="info-value">Per 31 Desember {{ now()->year }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Ruang</span>
                <div class="separator">:</div>
                <span class="info-value">
                    @if(isset($ruangs) && count($ruangs) > 0)
                    {{ $ruangs->pluck('code')->implode(', ') }}
                    @else
                    -
                    @endif
                </span>
            </div>
        </div>
    </div>

    <table class="table-laporan">
        <thead>
            <tr>
                <th style="width:10px;" rowspan="3">No</th>
                <th style="width:120px;" rowspan="3">No. Kode Barang</th>
                <th style="width:100px;" rowspan="3">Nama Barang</th>
                <th style="width:80px;" rowspan="3">Merk / Modal</th>
                <th style="width:80px;" rowspan="3">No Seri Pabrik</th>
                <th style="width:80px;" rowspan="3">Ukuran</th>
                <th style="width:80px;" rowspan="3">Bahan</th>
                <th rowspan="3" style="width:60px;">Tahun Pembelian</th>
                <th colspan="4">Situasi Barang</th>
                <th colspan="3" rowspan="2">Kondisi Barang</th>
                <!-- <th rowspan="3">Keterangan</th> -->
                <th colspan="3" rowspan="2">Keterangan Pindah</th>
            </tr>
            <tr>
                <th colspan="2">{{ now()->subYear()->year }}</th>
                <th colspan="2">{{ now()->year }}</th>
            </tr>
            <tr>
                <th style="width:40px;">Jumlah Brg</th>
                <th style="width:60px;">Harga Beli</th>
                <th style="width:40px;">Jumlah Brg</th>
                <th style="width:60px;">Harga Beli</th>
                <th style="width:40px;">Baik</th>
                <th style="width:40px;">Kurang Baik</th>
                <th style="width:40px;">Rusak Berat</th>
                <!-- <th>Tgl Sebelum Pindah</th> -->
                <th style="width:120px;">Pindahan dari</th>
                <!-- <th>Tgl Pindah</th> -->
                <!-- <th>Pindah ruang ke</th> -->
                <th style="width:140px;">Ket. Pindah</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($asetsByRuang as $kodeRuang => $items)
            @foreach ($items as $aset)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $aset->barang_id ?? '-' }}</td>
                <td>{{ $aset->nama_brg ?? '-' }}</td>
                <td>{{ $aset->merk ?? '-' }}</td>
                <td>{{ $aset->seri ?? '-' }}</td>
                <td>{{ $aset->ukuran ?? '-' }}</td>
                <td>{{ $aset->bahan ?? '-' }}</td>
                <td>{{ $aset->date ? \Carbon\Carbon::parse($aset->date)->format('Y') : '-' }}</td>
                @php
                $jmlLalu = $aset->was_in_room_last_year ? ($aset->jumlah_brg ?? 0) : 0;
                $hargaLalu = $aset->was_in_room_last_year ? ($aset->harga ?? 0) : 0;
                $jmlIni = $aset->was_in_room_this_year ? ($aset->jumlah_brg ?? 0) : 0;
                $hargaIni = $aset->was_in_room_this_year ? ($aset->harga ?? 0) : 0;
                @endphp
                <td class="text-right">{{ $jmlLalu ? $jmlLalu . ' ' . $aset->satuan : '-' }}</td>
                <td class="text-right">{{ number_format($hargaLalu, 0, ',', '.') }}</td>
                <td class="text-right">{{ $jmlIni ? $jmlIni . ' ' . $aset->satuan : '-' }}</td>
                <td class="text-right">{{ number_format($hargaIni, 0, ',', '.') }}</td>
                <td>{{ $aset->kondisi == 'Baik' ? $aset->jumlah_brg : 0 }}</td>
                <td>{{ $aset->kondisi == 'Kurang Baik' ? $aset->jumlah_brg : 0 }}</td>
                <td>{{ $aset->kondisi == 'Rusak Berat' ? $aset->jumlah_brg : 0 }}</td>
                <!-- {{ $aset->keterangan ?? '-' }}</td> -->
                <td>{{ $aset->ruang_sebelum ?? '-' }}</td>
                <!-- <td>{{ $aset->tanggal_sebelum_pindah ?? '-' }}</td> -->
                <!-- <td>{{ $aset->tanggal_pindah ?? '-' }}</td> -->
                <!-- <td>{{ $aset->ruang_sesudah ?? '-' }}</td> -->
                <td class="text-left">{{ $aset->keterangan_pindah ?? '-' }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>

        <tfoot class="table-laporan">>
            <tr>
                <th colspan="8">Jumlah Sub Total :</th>
                <th class="text-right">{{ $jumlahTotalTahunLalu }} item</th>
                <th class="text-right">{{ number_format($totalHargaTahunLalu, 0, ',', '.') }}</th>
                <th class="text-right">{{ $jumlahTotalTahunIni }} item</th>
                <th class="text-right">{{ number_format($totalHargaTahunIni, 0, ',', '.') }}</th>
                <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Baik')->sum('jumlah_brg') ?: 0 }}</th>
                <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Kurang Baik')->sum('jumlah_brg') ?: 0 }}</th>
                <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Rusak Berat')->sum('jumlah_brg') ?: 0 }}</th>
                <th colspan="3"></th>

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