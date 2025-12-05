@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-file-export me-3"></i> Data Rekapitulasi Seluruh Ruangan
        </span>
    </h2>
    <h6 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-file-export me-2"></i> Data Rekapitulasi Seluruh Ruangan
        </span>
    </h6>

    <hr class="section-divider">

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-end flex-wrap filter-header">
                <div class="d-flex align-items-center gap-2 filter-tanggal">
                    <!-- Placeholder agar layout sama seperti halaman aset -->
                </div>
                <div class="d-flex justify-content-md-end align-items-center gap-2 aksi-filter aksi-cetak">
                    @can('rekap-print')
                    <a href="{{ route('rekap.print') }}" target="_blank" class="btn btn-primary btn-sm" style="min-width: 120px;">
                        <i class="fas fa-print me-1"></i> Cetak
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="myTable" class="table table-hover table-bordered display nowrap w-100">
                    <thead class="table-gradient-header">
                        <tr class="text-center">
                            <th class="text-center" style="width: 2%;">No</th>
                            <th class="text-center" style="width: 20%;">Ruang</th>
                            <th class="text-center" style="width: 10%;">Kode Ruang</th>
                            <th class="text-center" style="width: 10%;">Jumlah</th>
                            <th class="text-center" style="width: 10%;">Harga Beli</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @php $no = 1; @endphp
                        @foreach ($ruangs as $ruang)
                        @php
                        $totalJumlah = $ruang->asets->sum('jumlah_brg');
                        $totalHargaBeli = $ruang->asets->sum(function ($aset) {
                        return ($aset->jumlah_brg ?? 0) * ($aset->harga ?? 0);
                        });
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $ruang->name }}</td>
                            <td class="text-center">{{ $ruang->code }}</td>
                            <td class="text-center">{{ $totalJumlah ?: '-' }}</td>
                            <td class="text-center">{{ $totalHargaBeli ? number_format($totalHargaBeli, 0, ',', '.') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            autoWidth: false,
            responsive: true,
            scrollX: true
        });
    });
</script>
@endpush

@push('styles')
<style>
    table-gradient-header {
        /* Hapus background dari thead itu sendiri agar tidak menghalangi */
        background-color: transparent !important;
        background-image: none !important;
    }

    /* Target sel header (th) di dalam thead untuk menerapkan gradien */
    .table-gradient-header th {
        background-image: linear-gradient(to bottom, #b3e5fc, #81d4fa) !important;
        background-color: transparent !important;
        color: #000;
        border-color: #9fcdff !important;
    }

    /* Layout tombol (samakan dengan histori/opruang) - Mode PC */
    .aksi-filter .btn,
    .aksi-filter .dropdown {
        width: auto;
        min-width: 120px;
    }

    /* Tambahan style agar konsisten dengan halaman aset */
    .filter-header {
        gap: .5rem;
    }

    .filter-tanggal {
        display: flex !important;
        align-items: center;
        gap: .5rem;
    }

    .filter-tanggal label {
        margin-bottom: 0;
    }

    .filter-tanggal input[type="date"] {
        max-width: 160px;
    }

    .btn-filter-wrap,
    .btn-reset-wrap {
        min-width: 90px;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @media (max-width: 576px) {

        /* Susun kontainer filter bertumpuk */
        .filter-header {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }

        /* Tanggal: label di atas, dua input satu baris dengan 's/d' di tengah */
        .filter-tanggal {
            display: grid !important;
            /* override .d-flex */
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            gap: .5rem;
            align-items: center;
            width: 100%;
            padding: 0 2px;
            box-sizing: border-box;
            margin-left: 0 !important;
            /* override ms-4 */
        }

        .filter-tanggal label {
            grid-column: 1 / -1;
            align-self: start;
            font-weight: 600;
            margin-bottom: 0;
        }

        .filter-tanggal input[type="date"] {
            width: 100%;
            max-width: 100% !important;
            /* override inline max-width */
            margin: 0;
        }

        .filter-tanggal span {
            text-align: center;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
        }

        .btn-filter-wrap,
        .btn-reset-wrap {
            grid-column: 1 / -1;
            width: 100%;
        }

        .btn-filter-wrap .btn,
        .btn-reset-wrap .btn {
            width: 100%;
        }

        /* Tombol aksi: full width bertumpuk */
        .aksi-filter {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
            margin-right: 0 !important;
            /* override me-4 */
            margin-left: 0 !important;
        }

        .aksi-filter .btn,
        .aksi-filter .dropdown,
        .aksi-filter a,
        .aksi-filter form,
        .aksi-filter .btn-group {
            width: 100% !important;
            display: block !important;
        }

        /* Dropdown menu selebar tombol */
        .aksi-filter .dropdown-menu {
            width: 100% !important;
        }
    }

    @media print {

        /* Sembunyikan elemen yang tidak perlu saat print */
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
</style>
@endpush