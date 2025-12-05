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
            margin-top: 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
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

            .no-print {
                display: none !important;
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

            table {
                margin-top: 5px;
                font-size: 10px;
            }

            th,
            td {
                font-size: 7px;
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

            /* atur area cetak agar pas ukuran F4 */
            #print-area {
                width: 210mm;
                min-height: 330mm;
                padding: 5mm;
                box-sizing: border-box;
            }
        }
    </style>

</head>

<body>
    <div id="print-area">
        <div class="header">
            <h2>Rekapitulasi Hasil Opname Per (31 Desember {{ now()->year }})</h2>
            <p>
            <h2>PERALATAN KANTOR, TEKNIK GUDANG, BENGKEL, PERHUBUNGAN, LABORAT DAN ANGKUTAN</h2>
            </p>
        </div>

        <table class="table-laporan">
            <thead>
                <tr>
                    <th style="width: 10px;" rowspan="3">No</th>
                    <th style="width: 155px;" rowspan="3">Bagian/Ruang</th>
                    <th style="width: 50px;" rowspan="3">Kode Ruang</th>
                    <th colspan="4">Situasi Barang</th>
                    <th colspan="3" rowspan="2">Kondisi Barang</th>
                </tr>
                <tr>
                    <th colspan="2">{{ now()->subYear()->year }}</th>
                    <th colspan="2">{{ now()->year }}</th>
                </tr>
                <tr>
                    <th style="width: 10px;">Jumlah Brg</th>
                    <th style="width: 60px;">Harga Beli</th>
                    <th style="width: 10px;">Jumlah Brg</th>
                    <th style="width: 60px;">Harga Beli</th>
                    <th style="width: 20px;">Baik</th>
                    <th style="width: 20px;">Kurang Baik</th>
                    <th style="width: 20px;">Rusak Berat</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($ruangs as $ruang)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td class="text-left">{{ $ruang->name }}</td>
                    <td>{{ $ruang->code }}</td>

                    {{-- Tahun lalu --}}
                    <td class="text-right">{{ $ruang->aset_tahun_lalu->sum('jumlah_brg') ?: '-' }}</td>
                    @php
                    $hargaBeliTahunLalu = $ruang->aset_tahun_lalu->sum(function ($aset) {
                    return $aset->jumlah_brg * $aset->harga;
                    });
                    @endphp
                    <td class="text-right">{{ $hargaBeliTahunLalu ? number_format($hargaBeliTahunLalu, 0, ',', '.') : '-' }}</td>

                    {{-- Tahun ini --}}
                    <td class="text-right">{{ $ruang->aset_tahun_ini->sum('jumlah_brg') ?: '-' }}</td>
                    @php
                    $hargaBeliTahunIni = $ruang->aset_tahun_ini->sum(function ($aset) {
                    return $aset->jumlah_brg * $aset->harga;
                    });
                    @endphp
                    <td class="text-right">{{ $hargaBeliTahunIni ? number_format($hargaBeliTahunIni, 0, ',', '.') : '-' }}</td>

                    {{-- Kondisi barang (dari aset tahun ini) --}}
                    <td>{{ $ruang->aset_tahun_ini->where('kondisi', 'Baik')->sum('jumlah_brg') ?: 0 }}</td>
                    <td>{{ $ruang->aset_tahun_ini->where('kondisi', 'Kurang Baik')->sum('jumlah_brg') ?: 0 }}</td>
                    <td>{{ $ruang->aset_tahun_ini->where('kondisi', 'Rusak Berat')->sum('jumlah_brg') ?: 0 }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-laporan">
                <tr>
                    <th colspan="3">Total</th>
                    <th class="text-right">{{ $jumlahTotalTahunLalu }}</th>
                    <th class="text-right">{{ number_format($totalHargaTahunLalu, 0, ',', '.') }}</th>
                    <th class="text-right">{{ $jumlahTotalTahunIni }}</th>
                    <th class="text-right">{{ number_format($totalHargaTahunIni, 0, ',', '.') }}</th>
                    <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Baik')->sum('jumlah_brg') ?: 0 }}</th>
                    <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Kurang Baik')->sum('jumlah_brg') ?: 0 }}</th>
                    <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Rusak Berat')->sum('jumlah_brg') ?: 0 }}</th>
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
    </div> <!-- end #print-area -->
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>


</body>

</html>