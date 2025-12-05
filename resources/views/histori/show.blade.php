@extends('layout.backend')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-eye me-2"></i> Detail Aset Inventaris
        </span>
    </h2>
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-eye me-2"></i> Detail Aset Inventaris
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('histori.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
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
                                        <td class="td-mode">{{ \Carbon\Carbon::parse($histori->tanggal)->format('d-m-Y') }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Tanggal Sebelum :</strong></th>
                                            <td class="td-mode">
                                                {{ $histori->tanggal_sblm ? \Carbon\Carbon::parse($histori->tanggal_sblm)->format('d-m-Y') : '-' }}
                                            </td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>ID Registrasi :</strong></th>
                                            <td class="td-mode">{{ $histori->id_regis ?? '-' }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Status Histori :</strong></th>
                                            <td class="td-mode">
                                                @if($histori->st_histori == 'PDH')
                                                <span class="badge bg-primary">Pindah</span>
                                                @elseif($histori->st_histori == 'LLG')
                                                <span class="badge bg-warning text-dark">Lelang</span>
                                                @elseif($histori->st_histori == 'RSK')
                                                <span class="badge bg-danger">Rusak</span>
                                                @else
                                                <span class="badge bg-secondary">{{ $histori->st_histori }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Tahun Perolehan :</strong></th>
                                            <td class="td-mode">{{ is_numeric($histori->th_oleh) ? $histori->th_oleh : \Carbon\Carbon::parse($histori->th_oleh)->format('Y') }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Nama Barang :</strong></th>
                                            <td class="td-mode">{{ $aset->nama_brg }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Jumlah Barang :</strong></th>
                                            <td class="td-mode">{{ $aset->jumlah_brg }} {{ $aset->satuan }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Merk Barang :</strong></th>
                                            <td class="td-mode">{{ $histori->hmerk }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>No. Seri :</strong></th>
                                            <td class="td-mode">{{ $histori->hseri }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Bahan :</strong></th>
                                            <td class="td-mode">{{ $histori->hbahan }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Ukuran :</strong></th>
                                            <td class="td-mode">{{ $histori->hsize }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Jenis Barang :</strong></th>
                                            <td class="td-mode">{{ $histori->kategori->nama ?? $aset->kategori->nama ?? '-' }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Kondisi :</strong></th>
                                            <td class="td-mode">{{ $histori->hkondisi }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Harga Beli :</strong></th>
                                            <td class="td-mode">Rp {{ number_format($histori->hprice, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Ruangan Sebelumnya :</strong></th>
                                            <td class="td-mode">{{ $histori->ruangSebelum->name ?? $histori->r_sebelum ?? '-' }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Ruangan Sesudahnya :</strong></th>
                                            <td class="td-mode">{{ $histori->ruangSesudah->name ?? $aset->ruang->name ?? '-' }}</td>
                                        </tr>
                                        <tr class="table-info text-white">
                                            <th><strong>Keterangan :</strong></th>
                                            <td class="td-mode">{{ $histori->ket ?? '-' }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-start">

                        {{-- Carousel Foto Sebelum --}}
                        <div class="mb-4 text-center w-100">
                            <h5><strong>Foto Barang Sebelum</strong></h5>
                            @php
                            $placeholderPath = asset('assets/img/aset-placeholder.png');
                            $slidesSebelum = $fotosSebelum ?? [];
                            // Lengkapi sampai 4 dengan placeholder
                            while (count($slidesSebelum) < 4) {
                                $slidesSebelum[]=[ 'url'=> $placeholderPath, 'ruangan' => $ruanganSebelumName ?? 'Ruangan tidak diketahui' ];
                                }
                                @endphp
                                <div id="fotoSebelumCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                    <div class="carousel-indicators">
                                        @for($i = 0; $i < 4; $i++)
                                            <button type="button" data-bs-target="#fotoSebelumCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}" aria-current="{{ $i == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $i + 1 }}"></button>
                                            @endfor
                                    </div>
                                    <div class="carousel-inner">
                                        @foreach($slidesSebelum as $index => $foto)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ $foto['url'] }}" class="d-block mx-auto img-fluid histori-img"
                                                alt="Foto Sebelum {{ $index + 1 }}">
                                            <div class="carousel-caption d-none d-md-block lower-caption">
                                                <small class="caption-text">
                                                    foto ke {{ $index + 1 }} dari 4
                                                </small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#fotoSebelumCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#fotoSebelumCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                </div>
                                <!-- badge ruangan awal -->
                                <p class="mt-2">
                                    <span class="badge bg-primary">{{ $ruanganSebelumName ?? ($fotosSebelum[0]['ruangan'] ?? 'Ruangan tidak diketahui') }}</span>
                                </p>
                        </div>

                        {{-- Carousel Foto Sesudah --}}
                        <div class="mb-4 text-center w-100 mt-4">
                            <h5><strong>
                                    @if($histori->st_histori == 'PDH')
                                    Foto Barang Sesudah Pindah
                                    @elseif($histori->st_histori == 'LLG')
                                    Foto Barang Sesudah Lelang
                                    @elseif($histori->st_histori == 'RSK')
                                    Foto Barang Sesudah Rusak
                                    @else
                                    Foto Barang Sesudah
                                    @endif
                                </strong></h5>

                            @if(count($fotosSesudah) > 0)
                            <div id="fotoSesudahCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">

                                {{-- indikator (titik di bawah foto) --}}
                                <div class="carousel-indicators">
                                    @foreach($fotosSesudah as $i => $foto)
                                    <button type="button" data-bs-target="#fotoSesudahCarousel"
                                        data-bs-slide-to="{{ $i }}"
                                        class="{{ $i == 0 ? 'active' : '' }}"
                                        aria-current="{{ $i == 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $i + 1 }}"></button>
                                    @endforeach
                                </div>

                                {{-- isi foto --}}
                                <div class="carousel-inner">
                                    @foreach($fotosSesudah as $index => $foto)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}"
                                        data-ruangan="{{ $foto['ruangan'] ?? 'Tidak diketahui' }}">
                                        <img src="{{ $foto['url'] }}" class="d-block mx-auto img-fluid histori-img"
                                            alt="Foto Sesudah {{ $index + 1 }}">
                                        <div class="carousel-caption d-none d-md-block lower-caption">
                                            <small class="caption-text">
                                                foto ke {{ $index + 1 }} dari {{ count($fotosSesudah) }}
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- tombol prev/next hanya muncul kalau ada lebih dari 1 foto --}}
                                @if(count($fotosSesudah) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#fotoSesudahCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#fotoSesudahCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                                @endif
                            </div>

                            <!-- badge ruangan sesudah (dinamis mengikuti slide aktif) -->
                            <p class="mt-2">
                                <span class="badge bg-success" id="ruanganSesudahBadge">{{ $fotosSesudah[0]['ruangan'] ?? 'Ruangan tidak diketahui' }}</span>
                            </p>
                            @else
                            <span class="text-muted">Tidak ada foto sesudah</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sesudahCarousel = document.getElementById('fotoSesudahCarousel');
        var badge = document.getElementById('ruanganSesudahBadge');
        if (sesudahCarousel && badge) {
            function updateBadge() {
                var active = sesudahCarousel.querySelector('.carousel-item.active');
                if (active) {
                    var r = active.getAttribute('data-ruangan') || 'Ruangan tidak diketahui';
                    badge.textContent = r;
                }
            }
            sesudahCarousel.addEventListener('slid.bs.carousel', updateBadge);
            // Inisialisasi pertama
            updateBadge();
        }
    });
</script>
@endpush

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

    .table-gradient-header {
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

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1) grayscale(100%) brightness(0);
    }

    /* Samakan ukuran semua gambar (foto asli dan placeholder) */
    .histori-img {
        height: 250px;
        max-height: 250px;
        width: 100%;
        object-fit: contain;
        background-color: #f8f9fa;
        /* latar netral untuk placeholder */
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

    /* Caption lebih ke bawah dan transparan */
    .carousel-caption.lower-caption {
        bottom: 15px;
        /* makin kecil makin ke bawah, bisa coba -20px juga */
    }

    .caption-text {
        background-color: rgba(0, 0, 0, 0.5);
        /* hitam dengan transparansi 50% */
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
    }
</style>
@endpush