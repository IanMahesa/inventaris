<!DOCTYPE html>
<html>

<head>
    <title>Cetak Histori</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            font-size: 12px;
            /* Ukuran font default halaman */
            padding-left: 5mm;
            padding-right: 5mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 11px;
            /* Ukuran font khusus tabel */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            /* diperkecil dari 6px */
            text-align: center;
        }

        h2,
        h4 {
            text-align: center;
            margin: 0;
            font-size: 14px;
            /* perkecil juga jika ingin */
        }

        .signature {
            margin-top: 40px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            font-size: 11px;
            gap: 450px;
            /* Tambahkan jarak antar kolom signature */
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        @media print {
            button {
                display: none;
            }

            body {
                margin: 0;
                font-size: 9px;
                /* Ukuran font lebih kecil saat print */
            }

            table {
                font-size: 10px;
            }

            th,
            td {
                padding: 2px;
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
                margin-top: 5px;
                /* Atur sesuai kebutuhan */
                page-break-inside: avoid;
                break-inside: avoid;
                gap: 450px;
                /* Pastikan jarak signature tetap saat print */
            }

            #waktu-cetak {
                font-size: 10px;
                margin-bottom: 20px;
                text-align: left;
                margin-right: 10px;
                width: auto;
                margin-top: 5px;
            }

            @page {
                size: 330mm 210mm;
                /* F4 landscape */
                margin: 15mm;
            }
        }
    </style>
</head>

<body>
    <h2>Rekap Aset Per (31 Desember {{ now()->year }})</h2>
    <h2>Peralatan Kantor, Teknik, Gudang, Bengkel, Perhubungan, Laborat dan Angkutan</h2>


    <table class="table-laporan">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kode Aset</th>
                <th rowspan="2">Bagian/Ruang</th>
                <th rowspan="2">Nama Brg</th>
                <th rowspan="2">Merk</th>
                <th rowspan="2">No. Seri</th>
                <th rowspan="2">Bahan</th>
                <th rowspan="2">Ukuran</th>
                <th rowspan="2">Status</th>
                <th colspan="3">Kondisi Barang</th>
            </tr>
            <tr>
                <th>Baik</th>
                <th>Kurang Baik</th>
                <th>Rusak Berat</th>
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
                <td class="text-left">{{ $aset->nama_brg }}</td>
                <td>{{ $aset->merk }}</td>
                <td>{{ $aset->seri }}</td>
                <td>{{ $aset->bahan }}</td>
                <td>{{ $aset->ukuran }}</td>
                @php
                $statusMap = [
                'PDH' => 'Pindah',
                'LLG' => 'Lelang',
                ];
                @endphp
                <td>{{ $statusMap[$aset->st_aset] ?? '-' }}</td>
                <td>{{ $ruang->aset_tahun_ini->where('id_aset', $aset->id_aset)->where('kondisi', 'Baik')->sum('jumlah_brg') ?: 0 }}</td>
                <td>{{ $ruang->aset_tahun_ini->where('id_aset', $aset->id_aset)->where('kondisi', 'Kurang Baik')->sum('jumlah_brg') ?: 0 }}</td>
                <td>{{ $ruang->aset_tahun_ini->where('id_aset', $aset->id_aset)->where('kondisi', 'Rusak Berat')->sum('jumlah_brg') ?: 0 }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
        <tfoot class="table-laporan">
            <tr>
                <th colspan="9">Total</th>
                <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Baik')->sum('jumlah_brg') ?: 0 }}</th>
                <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Kurang Baik')->sum('jumlah_brg') ?: 0 }}</th>
                <th>{{ $ruangs->flatMap->aset_tahun_ini->where('kondisi', 'Rusak Berat')->sum('jumlah_brg') ?: 0 }}</th>
            </tr>
        </tfoot>
    </table>
    <h4 id="waktu-cetak" style="padding-left: 10px; width: 300px;"></h4>

    <div class="signature">
        <div class="signature-left" style="text-align: right; width: 300px;">Koordinator Opname,</div>
        <div class="signature-right" style="text-align: left; width: 400px;">Sekretaris Opname,</div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Cetak</button>
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