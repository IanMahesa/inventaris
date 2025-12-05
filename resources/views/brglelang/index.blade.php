@extends('layout.backend')

@section('content')
@include('layout.alert')

@push('styles')
<style>
    table-gradient-header {
        /* Hapus background dari thead itu sendiri agar tidak menghalangi */
        background-color: transparent !important;
        background-image: none !important;
    }

    /* Target sel header (th) di dalam thead untuk menerapkan gradien */
    .table-gradient-header th {
        background-image: linear-gradient(to bottom, #fffde7, #fff59d, #ffeb3b) !important;
        background-color: transparent !important;
        color: #000;
        border-color: rgb(248, 225, 167) !important;
    }

    .accordion-button.collapsed {
        background-color: rgb(70, 124, 210) !important;
        color: #fff !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e9ecef;
        color: #212529;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .container-fluid,
        .container-fluid * {
            visibility: visible;
        }

        .container-fluid {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .btn,
        .dataTables_filter,
        .dataTables_length,
        .dataTables_info,
        .dataTables_paginate {
            display: none !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000 !important;
            padding: 5px !important;
        }
    }

    #filteredTable th:nth-child(1),
    #filteredTable td:nth-child(1) {
        white-space: nowrap;
        width: 10px !important;
    }

    #filteredTable th:nth-child(2),
    #filteredTable td:nth-child(2) {
        white-space: nowrap;
        width: 80px !important;
    }

    #filteredTable th:nth-child(3),
    #filteredTable td:nth-child(3) {
        white-space: nowrap;
        width: 100px !important;
    }

    #filteredTable th:nth-child(4),
    #filteredTable td:nth-child(4) {
        white-space: nowrap;
        width: 150px !important;
    }

    #filteredTable th:nth-child(5),
    #filteredTable td:nth-child(5) {
        white-space: nowrap;
        width: 120px !important;
    }

    #filteredTable th:nth-child(6),
    #filteredTable td:nth-child(6) {
        white-space: nowrap;
        width: 150px !important;
    }

    #filteredTable th:nth-child(7),
    #filteredTable td:nth-child(7) {
        width: 72px !important;
    }

    @media (max-width: 576.98px) {
        #searchForm .btn-row {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: .25rem;
            /* equal to Bootstrap gap-1 */
            width: 100%;
        }

        #searchForm .btn-row .btn {
            width: 100%;
        }

        #judul-brglelang {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-brglelang .fa-cog {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }
</style>
@endpush

<div id="brglelang-page" class="container-fluid px-3 px-md-4" data-anyfilled="{{ request()->anyFilled(['date', 'periode', 'code_kategori', 'nama_brg', 'merk', 'seri', 'code_ruang']) ? '1' : '0' }}">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-3"></i> Lelang Aset Inventaris
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h4 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-2"></i> Lelang Aset Inventaris
        </span>
    </h4>

    <hr class="section-divider">

    <div class="row">
        <!-- Filter di kiri (lebih kecil) -->
        <div class="col-md-3">
            <div class="card mb-4" style="max-height: 90vh; overflow-y: auto; padding: 10px;">
                <div class="card-body p-2">
                    <form id="searchForm" method="GET" action="{{ route('brglelang.index') }}">
                        <div class="mb-2">
                            <label class="form-label small">Tanggal Input/Brg Masuk</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Tahun Perolehan</label>
                            <input type="text" name="periode" class="form-control form-control-sm" value="{{ request('periode') }}" placeholder="Contoh: 2023" min="1900" max="2100">
                        </div>
                        <div class="mb-2 position-relative">
                            <label class="form-label small">Jenis Barang</label>
                            <div class="position-relative">
                                <select name="code_kategori" class="form-control form-control-sm">
                                    <option value="">-- Semua Jenis Barang --</option>
                                    @foreach($kategori as $kat)
                                    <option value="{{ $kat->kode }}" {{ request('code_kategori') == $kat->kode ? 'selected' : '' }}>
                                        {{ $kat->kode }} - {{ $kat->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                <i class="fa fa-chevron-down position-absolute end-0 top-50 translate-middle-y me-2 text-muted"></i>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Nama Barang</label>
                            <input type="text" name="nama_brg" class="form-control form-control-sm" value="{{ request('nama_brg') }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Merk</label>
                            <input type="text" name="merk" class="form-control form-control-sm" value="{{ request('merk') }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">No. Seri</label>
                            <input type="text" name="seri" class="form-control form-control-sm" value="{{ request('seri') }}">
                        </div>
                        <div class="mb-2 position-relative">
                            <label class="form-label small">Kode Ruangan</label>
                            <div class="position-relative">
                                <select name="code_ruang" class="form-control form-control-sm">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($ruang as $r)
                                    <option value="{{ $r->code }}" {{ request('code_ruang') == $r->code ? 'selected' : '' }}>
                                        {{ $r->code }} - {{ $r->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <i class="fa fa-chevron-down position-absolute end-0 top-50 translate-middle-y me-2 text-muted"></i>
                            </div>
                        </div>
                        <div class="btn-row d-flex gap-1 mt-3 w-100 aksi-filter aksi-reset">
                            <button type="submit" class="btn btn-info btn-sm w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="{{ route('brglelang.index') }}" class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel hasil pencarian di kanan -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body p-4">
                    <h4 class="mb-3"><i class="fas fa-list me-2"></i> Hasil Pencarian</h4>
                    <div class="table-responsive">
                        <table id="filteredTable" class="table table-hover table-bordered display nowrap w-100">
                            <thead class="table-gradient-header">
                                <tr>
                                    <th class="text-center" style="width: 5px;">No</th>
                                    <th class="text-center" style="width: 10px;">Nama</th>
                                    <th class="text-center" style="width: 10px;">Tgl Input</th>
                                    <th class="text-center" style="width: 17px;">Ruangan</th>
                                    <th class="text-center" style="width: 15px;">Kode Aset</th>
                                    <th class="text-center" style="width: 25px;">Deskripsi</th>
                                    <th class="text-center" style="width: 5px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filteredAsets ?? [] as $key => $item)
                                @php
                                $tahun = \Carbon\Carbon::parse($item->periode)->format('Y');
                                $bulan = \Carbon\Carbon::parse($item->periode)->format('m');
                                $kodeRuang = $item->ruang->code ?? 'XX';
                                $kodeKategori = $item->kategori->kode ?? 'XX';
                                $idFormatted = str_pad($item->id_aset, 4, '0', STR_PAD_LEFT);
                                $barang_id = "$kodeRuang-$tahun-$bulan-$kodeKategori-$idFormatted";
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $item->nama_brg }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ $item->ruang->name ?? '-' }}</td>
                                    <td class="text-center">{{ $barang_id }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td class="text-center td-aksi">
                                        @can('histori-edit')
                                        <a href="{{ route('aset.histori.editlelang', $item->id_aset) }}" class="btn btn-sm btn-warning" title="Edit Barang Lelang">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Belum ada data ditampilkan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        // --- 1. Validasi form sebelum submit ---
        $('#searchForm').on('submit', function(e) {
            let inputs = $(this).find('input[type="text"], input[type="date"], select');
            let isEmpty = true;

            inputs.each(function() {
                if ($(this).val().trim() !== '') {
                    isEmpty = false;
                    return false; // keluar dari loop jika ada input terisi
                }
            });

            if (isEmpty) {
                e.preventDefault(); // cegah submit
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Maaf, Anda belum mengisi data untuk dicari!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            }
        });

        // --- 2. Alert hasil pencarian kosong ---
        const anyFilled = document.getElementById('brglelang-page').dataset.anyfilled === '1';
        if (anyFilled) {
            let rowCount = $('#filteredTable tbody tr').length;
            let firstCell = $('#filteredTable tbody tr:first td');

            if (rowCount === 1 && firstCell.hasClass('text-muted')) {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Maaf, barang yang Anda cari tidak ada!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            }
        }

        // --- 3. DataTable jika ada data ---
        let rowCount = $('#filteredTable tbody tr').length;
        let firstCell = $('#filteredTable tbody tr:first td');
        if (rowCount > 0 && !firstCell.hasClass('text-muted')) {
            $('#filteredTable').DataTable({
                autoWidth: false,
                responsive: true,
                scrollX: true
            });
        }
    });
</script>
@endpush