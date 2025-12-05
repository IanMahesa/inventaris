@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-eye me-3"></i> Detail Aset Inventaris
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-eye me-2"></i> Detail Aset Inventaris
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('aset.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="section-body" style="margin-top: 10px; margin-bottom: 3rem;">
        <div class="card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <div class="table-wrapper">
                                <table class="table table-bordered text-first align-middle mb-0">
                                    <thead class="table-gradient-header">
                                        <th width="30%"><strong>Tanggal Input :</strong></th>
                                        <td class="td-mode">{{ \Carbon\Carbon::parse($aset->date)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Tahun Perolehan :</strong></th>
                                            <td class="td-mode">{{ \Carbon\Carbon::parse($aset->periode)->format('Y') }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Kode Aset :</strong></th>
                                            <td class="td-mode">{{ $aset->barang_id }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Nama Barang :</strong></th>
                                            <td class="td-mode">{{ $aset->nama_brg }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Jumlah Barang :</strong></th>
                                            <td class="td-mode">{{ $aset->jumlah_brg }} {{ $aset->satuan }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Merk Barang :</strong></th>
                                            <td class="td-mode">{{ $aset->merk }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>No. Seri :</strong></th>
                                            <td class="td-mode">{{ $aset->seri }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Bahan :</strong></th>
                                            <td class="td-mode">{{ $aset->bahan }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Ukuran :</strong></th>
                                            <td class="td-mode">{{ $aset->ukuran }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Jenis Barang :</strong></th>
                                            <td class="td-mode">{{ $aset->kategori->nama ?? '-' }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Kondisi :</strong></th>
                                            <td class="td-mode">{{ $aset->kondisi }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Harga Beli :</strong></th>
                                            <td class="td-mode">Rp {{ number_format($aset->harga, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Ruangan :</strong></th>
                                            <td class="td-mode">{{ $aset->ruang->name ?? '-' }}</td>
                                        </tr>
                                        <tr class="table-gradient-header">
                                            <th><strong>Keterangan :</strong></th>
                                            <td class="td-mode">{{ $aset->keterangan ?? '-' }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-start">
                        <div class="mb-4 text-center w-100">
                            <h5><strong>Foto Barang</strong></h5>
                            {{-- Tampilkan 4 slot: foto jika ada, jika tidak tampilkan placeholder --}}
                            @php
                            $placeholderPath = asset('assets/img/aset-placeholder.png');
                            $slides = [];
                            $cleanFotos = is_array($fotosFinal ?? null) ? $fotosFinal : [];
                            for ($i = 0; $i < 4; $i++) {
                                $slides[$i]=isset($cleanFotos[$i]) && !empty($cleanFotos[$i])
                                ? asset('storage/' . trim($cleanFotos[$i]))
                                : $placeholderPath;
                                }
                                @endphp

                                <div id="fotoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                <div class="carousel-indicators">
                                    @for($i = 0; $i < 4; $i++)
                                        <button type="button" data-bs-target="#fotoCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}" aria-current="{{ $i == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $i + 1 }}"></button>
                                        @endfor
                                </div>

                                <div class="carousel-inner">
                                    @foreach($slides as $index => $src)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ $src }}" class="d-block mx-auto img-fluid aset-img" alt="Foto Aset {{ $index + 1 }}"
                                            onerror="this.src='https://via.placeholder.com/300x200/007bff/ffffff?text=Foto+{{ $index + 1 }}+{{ $aset->nama_brg }}'">
                                        <div class="carousel-caption d-none d-md-block">
                                            <small class="text-white bg-dark bg-opacity-75 px-2 py-1 rounded">Foto {{ $index + 1 }} dari 4</small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#fotoCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#fotoCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                        </div>

                        <div class="mt-2 text-center">
                            <small class="text-muted">
                                <i class="fas fa-images me-1"></i>
                                {{ count($fotosFinal ?? []) }} foto tersedia - Total slot 4
                            </small>
                        </div>
                    </div>

                    <div class="text-center aksi-filter">
                        <h5><strong>QR Code</strong></h5>
                        @php
                        $qrCodeValue = $aset->id_aset ? route('aset.show', ['aset' => $aset->id_aset]) : null;
                        @endphp
                        @if($qrCodeValue)
                        {!! QrCode::size(220)->generate($qrCodeValue) !!}
                        <div class="mt-2">
                            <a href="{{ route('aset.qrcode.download', $aset->id_aset) }}" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-download me-1"></i> Download QR Code
                            </a>
                        </div>
                        @else
                        <span class="text-muted">QR Code tidak tersedia</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
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

    .td-mode:hover {
        background-color: rgb(240, 240, 240) !important;
        /* sedikit abu terang */
        color: #000 !important;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1) grayscale(100%) brightness(0);
    }

    #fotoCarousel {
        max-width: 300px;
        max-height: 300px;
        margin: 0 auto;
        background: transparent !important;
        /* ini penting */
    }

    /* Samakan ukuran foto & placeholder */
    .aset-img {
        height: 250px;
        max-height: 250px;
        width: 100%;
        object-fit: contain;
        background-color: transparent !important;
        /* transparan */
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }
</style>
@endpush


@push('styles')
<style>
    /* bikin ikon panah default jadi hitam */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1) grayscale(100%) brightness(0);
    }
</style>
@endpush

@push('scripts')
<style>
    /* CSS Kustom untuk menyesuaikan ukuran di layar berukuran mobile */
    @media (max-width: 767.98px) {
        #judul-aset {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-aset .fa-eye {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }
</style>
@endpush