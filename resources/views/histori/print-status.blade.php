<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Histori - {{ $statusText }}</title>
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
        }

        .info-value {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

            @page {
                /* F4 Landscape: width x height */
                size: 210mm 330mm;
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
                margin-right: auto;
            }

            th,
            td {
                font-size: 8px;
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
        <h2>LAPORAN {{ strtoupper($statusText) }} ASET INVENTARIS Per (31 Desember {{ now()->year }})</h2>
        <p>
        <h2>Peralatan Kantor, Teknik, Gudang, Bengkel, Perhubungan, Laborat dan Angkutan</h2>
        </p>
    </div>

    <div class="info-section">
        <div class="info-column">
            <div class="info-row">
                <span class="info-label">Provinsi</span>
                <span class="info-value">: Jawa Tengah</span>
            </div>
            <div class="info-row">
                <span class="info-label">Unit</span>
                <span class="info-value">: Perumda Air Minum Kota Magelang</span>
            </div>
            <div class="info-row">
                <div class="info-label">Tahun</div>
                <div class="info-value">: Per 31 Desember {{ now()->year }}</div>
            </div>
        </div>

        <div class="info-column">
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">: {{ $statusText }} </span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Data:</span>
                <span class="info-value">: {{ $historis->count() }} item</span>
            </div>
        </div>
    </div>

    <table class="table-laporan">
        <thead>
            <tr>
                <th style="width: 10px;">No</th>
                <th style="width: 50px;">Tgl Input</th>
                <th style="width: 100px;">Nama Barang</th>
                <th style="width: 80px;">Kode Aset</th>
                <th style="width: 150px;">Ruang Sebelum</th>
                <th style="width: 150px;">Ruang Sesudah</th>
                <th style="width: 20px;">Jumlah</th>
                <th style="width: 70px;">Harga</th>
                <th style="width: 180px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($historis as $index => $item)
            @php
            $tahun = \Carbon\Carbon::parse($item->tanggal)->format('y');
            $bulan = \Carbon\Carbon::parse($item->tanggal)->format('m');
            $st_history = $item->st_histori ?? 'XX';
            $kodeKategori = $item->kategori->kode ?? 'XX';
            $idFormat = str_pad($item->id_histori ?? 0, 4, '0', STR_PAD_LEFT);
            $id_regis = "$st_history-$tahun-$bulan-$idFormat";
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->name_brg ?? '-' }}</td>
                <td>{{ $id_regis }}</td>
                <td class="text-left">{{ $item->ruangSebelum->name ?? '-' }}</td>
                <td class="text-left">{{ $item->ruangSesudah->name ?? '-' }}</td>
                <td>{{ number_format($item->hjum_brg ?? 0) }}</td>
                <td class="text-right">{{ number_format($item->hprice ?? 0) }}</td>
                <td class="text-left">{{ $item->ket ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="table-laporan">
            <tr>
                <th colspan="6">Total</th>
                <th>{{ number_format($totalBarang) }} item</th>
                <th class="text-right">Rp.{{ number_format($totalHarga) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span class="info-label">Dicetak pada</span>
            <span class="info-value">: {{ date('d-m-Y H:i:s') }}</span>
        </div>
        <div class="summary-row">
            <span class="info-label">Dicetak Oleh</span>
            <span class="info-value">: {{ auth()->user()->name ?? 'System' }}</span>
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
</body>

</html>

@push('scripts')
<script>
    // Aktifkan flatpickr dengan format dd-mm-yyyy
    flatpickr("#startDate", {
        dateFormat: "d-m-Y"
    });
    flatpickr("#endDate", {
        dateFormat: "d-m-Y"
    });

    // Filter DataTables berdasarkan rentang tanggal
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var min = $('#startDate').val();
        var max = $('#endDate').val();
        var dateStr = data[1]; // asumsikan kolom tanggal ada di index ke-1 tabel

        if (!dateStr) return true;

        // Parse format d-m-Y (dari tabel & input)
        function parseDate(str) {
            var parts = str.split('-'); // [dd, mm, yyyy]
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }

        var date = parseDate(dateStr);
        var minDate = min ? parseDate(min) : null;
        var maxDate = max ? parseDate(max) : null;

        if (minDate && date < minDate) return false;
        if (maxDate && date > maxDate) return false;

        return true;
    });

    // Trigger filter ulang ketika input tanggal berubah
    $('#startDate, #endDate').on('change', function() {
        table.draw();
    });
</script>
@endpush