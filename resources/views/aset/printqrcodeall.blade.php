<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }

        body {
            font-family: sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 kolom */
            grid-template-rows: repeat(4, auto);
            /* 4 baris */
            gap: 5px;
            /* jarak antar kartu */
            page-break-inside: auto;
        }

        .qr-card {
            width: 50mm;
            height: 70mm;
            border: 2px dashed #000;
            padding: 2mm;
            box-sizing: border-box;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .qr-card img {
            width: 45mm;
            height: 45mm;
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
    <div class="container">
        @foreach($asets as $aset)
        <div class="qr-card">
            <img src="{{ $aset->qr_path }}" alt="QR">
            <div class="label">
                <div>{{ $aset->ruang_label }}</div>
                <div>{{ $aset->kode_label }}</div>
            </div>
        </div>
        @endforeach
    </div>
</body>

</html>