@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container mt-4">
    <h3 class="text-center role-title">Scan QR Code Aset</h3>
    <hr class="section-divider">

    @cannot('scanqr-create')
    <div class="alert alert-warning text-center mt-3" role="alert">
        Anda tidak memiliki izin untuk melakukan scan QR. Hubungi administrator jika membutuhkan akses.
    </div>
    @endcannot

    @can('scanqr-create')
    <div class="d-flex justify-content-center">
        <div id="scan-container" style="width:100%; max-width:400px;">
            <div id="reader" class="rounded-4"></div>
        </div>
    </div>

    <div id="scan-result" class="mt-4" style="display:none;">
        <h4 class="text-center">Hasil Scan:</h4>
        <div class="section-body my-4">
            {{-- Menambahkan class 'rounded-3' ke card untuk border yang lebih rounded --}}
            <div class="card rounded-3">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        {{-- Menambahkan class 'rounded-3' ke table untuk border yang lebih rounded --}}
                        <div class="table-wrapper">
                            <table class="table table-bordered text-first align-middle mb-0">
                                <thead class="table-gradient-header">
                                    <thead class="table-gradient-header">
                                        <tr class="table-info text-dark">
                                            <th width="30%"><strong>No:</strong></th>
                                            <td class="td-mode" id="result-aset-id"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Kode Aset:</strong></th>
                                            <td class="td-mode" id="result-barang-id"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Nama Barang:</strong></th>
                                            <td class="td-mode" id="result-nama-brg"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Tahun Perolehan:</strong></th>
                                            <td class="td-mode" id="result-periode"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Merk Barang:</strong></th>
                                            <td class="td-mode" id="result-merk"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>No Seri:</strong></th>
                                            <td class="td-mode" id="result-seri"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Bahan:</strong></th>
                                            <td class="td-mode" id="result-bahan"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Ukuran:</strong></th>
                                            <td class="td-mode" id="result-ukuran"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Kondisi:</strong></th>
                                            <td class="td-mode" id="result-kondisi"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Harga:</strong></th>
                                            <td class="td-mode" id="result-harga"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Ruangan:</strong></th>
                                            <td class="td-mode" id="result-code-ruang"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Jenis Barang:</strong></th>
                                            <td class="td-mode" id="result-code-kategori"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Keterangan:</strong></th>
                                            <td class="td-mode" id="result-keterangan"></td>
                                        </tr>
                                        <tr class="table-info text-dark">
                                            <th><strong>Foto:</strong></th>
                                            <td class="td-mode" colspan="2">
                                                <div id="foto-preview" class="text-center"></div>
                                            </td>
                                        </tr>
                                    </thead>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-3 aksi-back">
                        <button class="btn btn-primary btn-sm" onclick="location.reload()"> Kembali ke Scan QR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection

@push('styles')
<style>
    #auto-breadcrumbs {
        display: none !important;
    }

    /* Override the automatic wrapper styling for this page */
    .container-fluid .d-flex.flex-column.flex-md-row {
        justify-content: center !important;
    }

    /* Ensure the h3 title is centered */
    .role-title {
        text-align: center !important;
        width: 100%;
    }

    .table-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
    }

    /* Buat garis luar dengan pseudo-element */
    .table-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border: 2px solid #c5e1a5;
        /* warna hijau lembut */
        border-radius: 12px;
        pointer-events: none;
        /* biar tidak mengganggu klik */
        box-sizing: border-box;
        z-index: 2;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #c5e1a5 !important;
    }

    table-gradient-header {
        /* Hapus background dari thead itu sendiri agar tidak menghalangi */
        background-color: transparent !important;
        background-image: none !important;
    }

    /* Target sel header (th) di dalam thead untuk menerapkan gradien */
    .table-gradient-header th {
        background-image: linear-gradient(135deg, #f1f8e9, #aed581, #7cb342) !important;
        background-color: transparent !important;
        color: #000;
        border-color: rgb(212, 248, 167) !important;
    }

    /* Mengatur tampilan video kamera agar memenuhi container */
    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Penambahan CSS untuk membuat div #reader itu sendiri rounded */
    #reader {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background-color: transparent;
        aspect-ratio: 1 / 1;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.4);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @keyframes neonPulse {
        0% {
            box-shadow: 0 0 6px 2px rgb(72, 0, 255),
                0 0 8px 2px rgba(72, 0, 255, 0.6);
            opacity: 0.8;
        }

        50% {
            box-shadow: 0 0 8px 3px rgb(72, 0, 255),
                0 0 10px 3px rgba(72, 0, 255, 0.4);
            opacity: 1;
        }

        100% {
            box-shadow: 0 0 6px 2px rgb(72, 0, 255),
                0 0 8px 2px rgba(72, 0, 255, 0.6);
            opacity: 0.8;
        }
    }

    #reader div[style*="background-color: rgb(255, 255, 255)"] {
        background-color: rgb(72, 0, 255) !important;
        animation: neonPulse 2s ease-in-out infinite;
        transition: all 0.3s ease !important;
    }

    /* tombol prev/next carousel warna hitam */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: transparent;
        mask-image: none;
        -webkit-mask-image: none;
        filter: invert(1);
    }

    /* Overlay caption di tengah bawah gambar */
    #foto-preview {
        max-width: 510px;
        margin: 0 auto;
    }

    #foto-preview .carousel-item img {
        max-height: 300px;
        height: 300px;
        width: auto;
        margin: 0 auto;
        object-fit: contain;
        border-radius: 8px;
        /* Sudah ada */
        background: #f8f9fa;
        display: block;
    }

    #foto-preview .foto-caption-overlay {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: 40px;
        background: rgba(0, 0, 0, 0.75);
        color: #fff;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 13px;
        line-height: 1.2;
        pointer-events: none;
        z-index: 3;
    }

    /* Ubah indikator menjadi garis putus-putus */
    #foto-preview .carousel-indicators {
        bottom: -10px;
        z-index: 2;
    }

    #foto-preview .carousel-indicators [data-bs-target] {
        width: 28px;
        height: 0;
        background: transparent !important;
        border-top: 4px solid rgba(255, 255, 255, 0.5);
        border-radius: 0;
        opacity: 1;
        margin: 0 3px;
    }

    #foto-preview .carousel-indicators .active {
        border-top-color: #ffffff;
    }

    /* Sembunyikan kontrol dan indikator jika hanya 1 foto */
    #foto-preview .carousel.single .carousel-control-prev,
    #foto-preview .carousel.single .carousel-control-next,
    #foto-preview .carousel.single .carousel-indicators {
        display: none;
    }

    /* CSS tambahan untuk membuat border tabel menjadi rounded */
    /* Table container */
    .table-responsive {
        border-radius: 0;
        /* Tidak perlu rounding di luar tabel */
        overflow: hidden;
    }

    /* Table utama */
    .table-bordered {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 0;
        /* Default tanpa radius */
    }

    /* Hanya baris pertama dan terakhir yang rounded */
    .table-bordered tr:first-child th:first-child,
    .table-bordered tr:first-child td:first-child {
        border-top-left-radius: 8px;
    }

    .table-bordered tr:first-child th:last-child,
    .table-bordered tr:first-child td:last-child {
        border-top-right-radius: 8px;
    }

    .table-bordered tr:last-child th:first-child,
    .table-bordered tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }

    .table-bordered tr:last-child th:last-child,
    .table-bordered tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    /* Hilangkan efek rounded di baris/baris tengah */
    .table-bordered tr:not(:first-child):not(:last-child) th:first-child,
    .table-bordered tr:not(:first-child):not(:last-child) td:first-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .table-bordered tr:not(:first-child):not(:last-child) th:last-child,
    .table-bordered tr:not(:first-child):not(:last-child) td:last-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    #reader,
    #scan-result {
        transition: opacity 0.4s ease;
    }

    .hidden {
        opacity: 0;
        pointer-events: none;
    }

    .table-bordered th,
    .table-bordered td {
        vertical-align: middle !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/html5-qrcode/minified/html5-qrcode.min.js') }}"></script>
<script>
    let screenWidth = window.innerWidth;
    let qrBoxSize = screenWidth < 400 ? screenWidth * 0.8 : 250;
    const html5QrCode = new Html5Qrcode("reader");

    function formatRupiah(nominal) {
        let number = parseFloat(nominal);
        if (isNaN(number)) return "-";
        return "Rp. " + number.toLocaleString("id-ID") + ",00";
    }

    async function fetchAset(id_aset) {
        try {
            const response = await fetch(`/api/aset/${id_aset}`);
            if (!response.ok) return null;
            return await response.json();
        } catch (error) {
            console.error("Fetch error:", error);
            return null;
        }
    }

    async function onScanSuccess(decodedText, decodedResult) {
        const reader = document.getElementById('reader');
        const scanResult = document.getElementById('scan-result');

        // Tambahkan efek transisi halus
        reader.classList.add('hidden');

        setTimeout(() => {
            reader.style.display = 'none';
            scanResult.style.display = 'block';
        }, 400);

        document.getElementById('result-aset-id').textContent = decodedText;

        const data = await fetchAset(decodedText);
        if (data) {
            document.getElementById('result-barang-id').textContent = data.barang_id || '-';
            document.getElementById('result-nama-brg').textContent = data.nama_brg || '-';
            document.getElementById('result-periode').textContent = data.periode || '-';
            document.getElementById('result-merk').textContent = data.merk || '-';
            document.getElementById('result-seri').textContent = data.seri || '-';
            document.getElementById('result-bahan').textContent = data.bahan || '-';
            document.getElementById('result-ukuran').textContent = data.ukuran || '-';
            document.getElementById('result-kondisi').textContent = data.kondisi || '-';
            document.getElementById('result-harga').textContent = data.harga ? formatRupiah(data.harga) : '-';
            document.getElementById('result-code-ruang').textContent = data.code_ruang?.name || '-';
            document.getElementById('result-code-kategori').textContent = data.code_kategori?.nama_brg || '-';
            document.getElementById('result-keterangan').textContent = data.keterangan || '-';

            // Tampilkan foto
            if (data.foto) {
                let fotos = [];
                try {
                    fotos = JSON.parse(data.foto);
                    if (!Array.isArray(fotos)) fotos = [data.foto];
                } catch (e) {
                    fotos = [data.foto];
                }

                let carouselId = "fotoCarousel";
                let indicators = "";
                let innerSlides = "";
                const isSingle = fotos.length <= 1;

                fotos.forEach((foto, index) => {
                    let activeClass = index === 0 ? "active" : "";
                    indicators += `
                    <button type="button" data-bs-target="#${carouselId}" data-bs-slide-to="${index}" class="${activeClass}" aria-current="true" aria-label="Slide ${index + 1}"></button>
                `;
                    innerSlides += `
                    <div class="carousel-item ${activeClass}">
                        <img src="/storage/${foto}" class="d-block w-100" alt="Foto ${index + 1}">
                        <div class="foto-caption-overlay">foto ke ${index + 1} dari ${fotos.length}</div>
                    </div>
                `;
                });

                document.getElementById('foto-preview').innerHTML = `
                <div id="${carouselId}" class="carousel slide ${isSingle ? 'single' : ''}" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        ${indicators}
                    </div>
                    <div class="carousel-inner">
                        ${innerSlides}
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sebelumnya</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Selanjutnya</span>
                    </button>
                </div>
            `;
            } else {
                document.getElementById('foto-preview').innerHTML = '<p class="text-muted">Tidak ada foto</p>';
            }
        } else {
            const fields = [
                'result-barang-id', 'result-nama-brg', 'result-periode', 'result-merk',
                'result-seri', 'result-bahan', 'result-ukuran', 'result-kondisi', 'result-harga',
                'result-code-ruang', 'result-code-kategori', 'result-keterangan'
            ];
            fields.forEach(id => document.getElementById(id).textContent = '-');
            document.getElementById('foto-preview').innerHTML = '<p class="text-muted">Tidak ada foto</p>';
        }

        try {
            await html5QrCode.stop();
        } catch (e) {
            console.error('Failed stopping camera:', e);
        }
    }

    async function startScanner() {
        try {
            const config = {
                fps: 10,
                rememberLastUsedCamera: true,
                qrbox: {
                    width: qrBoxSize,
                    height: qrBoxSize
                },
                aspectRatio: 1
            };
            const constraints = {
                facingMode: "environment"
            };
            await html5QrCode.start(constraints, config, onScanSuccess);
        } catch (err) {
            console.error('Unable to start scanner:', err);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startScanner);
    } else {
        startScanner();
    }
</script>
@endpush