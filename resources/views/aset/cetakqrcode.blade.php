<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak QR Code</title>
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .page {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 kolom */
            grid-template-rows: repeat(5, auto);
            /* 5 baris */
            gap: 5px;
            /* Jarak antar kartu */
            page-break-after: always;
        }

        .qr-card {
            width: 70mm;
            /* Lebar mendekati A7 */
            height: 50mm;
            /* Tinggi mendekati A7 */
            border: 2px dashed #000;
            padding: 2mm;
            box-sizing: border-box;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .qr-card img {
            width: 40mm;
            height: 40mm;
            margin: 0 auto;
        }

        .label {
            margin-top: 5px;
            font-size: 10px;
            word-wrap: break-word;
            line-height: 1.2;
        }

        .label div:first-child {
            font-weight: bold;
            margin-bottom: 2px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="page">
        @foreach($asets as $aset)
        <div class="qr-card">
            <div class="label">
                <div>{{ $aset->ruang_label }}</div>
            </div>
            <img src="{{ $aset->qr_path }}" alt="QR">
            <div class="label">
                <div>{{ $aset->kode_label }}</div>
            </div>
        </div>
        @endforeach
    </div>
</body>

</html>