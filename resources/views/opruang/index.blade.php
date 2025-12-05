@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-file-export me-3"></i> Data Opname Akhir Tahun
        </span>
    </h2>
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-file-export me-2"></i> Data Opname Akhir Tahun
        </span>
    </h5>

    <hr class="section-divider">

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-end flex-wrap gap-2 me-4 aksi-filter aksi-reset aksi-cetak">
                <!-- Tombol Modal Cari Ruangan -->
                <button type="button" class="btn btn-info btn-sm" style="min-width: 120px;" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fas fa-search me-1"></i> Cari Ruangan
                </button>

                <form action="{{ route('opruang.index') }}" method="GET" style="display:inline;">
                    <button type="submit" class="btn btn-secondary btn-sm" style="min-width: 140px;">
                        <i class="fas fa-times me-1"></i> Reset Filter
                    </button>
                </form>

                @can('opruang-print')
                <form method="GET" action="{{ route('opruang.print') }}" target="_blank" style="display:inline;">
                    @if(request('ruang_id'))
                    <input type="hidden" name="ruangs[]" value="{{ request('ruang_id') }}">
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm" style="min-width: 120px;">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    {{-- Modal Pencarian --}}
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pencarian Nama Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover table-bordered display">
                            <thead class="table-gradient-header">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Ruangan</th>
                                    <th class="text-center">Nama Ruangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ruangs as $index => $ruang)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $ruang->code }}</td>
                                    <td>{{ $ruang->name }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success btn-sm btn-pilih-ruang"
                                            data-id="{{ $ruang->code }}"
                                            data-code="{{ $ruang->code }}"
                                            data-nama="{{ $ruang->name }}">
                                            <i class="fas fa-check-square"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-gradient-red-tutup btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Opname --}}
    <div class="card">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered" id="opruang-table">
                    <thead class="table-gradient-header">
                        <tr>
                            <th class="text-center align-middle" rowspan="2" style="width: 2%;">No</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 20%;">Bagian/Ruangan</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 10%;">Nama Barang</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 10%;">Kode Aset</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 5%;">Tahun</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 5%;">Jumlah</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 10%;">Harga</th>
                            <th class="text-center" colspan="3">Kondisi</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="width: 5%;">Baik</th>
                            <th class="text-center" style="width: 5%;">Kurang</th>
                            <th class="text-center" style="width: 5%;">Rusak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $rowNumber = ($asets->currentPage() - 1) * $asets->perPage() + 1;
                        $grouped = $asets->groupBy(function($item) {
                        return $item->ruang ? $item->ruang->name : ($item->ruang_name ?? 'Lainnya');
                        });
                        @endphp

                        @foreach ($grouped as $ruangName => $items)
                        @php $first = true; @endphp
                        @foreach ($items as $aset)
                        <tr data-ruang="{{ $ruangName }}">
                            @if ($first)
                            <td class="text-center" rowspan="{{ $items->count() }}">{{ $rowNumber++ }}</td>
                            <td rowspan="{{ $items->count() }}">{{ $ruangName }}</td>
                            @php $first = false; @endphp
                            @endif
                            <td class="text-center">{{ $aset->nama_brg }}</td>
                            <td class="text-center">{{ $aset->barang_id }}</td>
                            <td class="text-center">{{ $aset->periode ? \Carbon\Carbon::parse($aset->periode)->format('Y') : '-' }}</td>
                            <td class="text-center">{{ $aset->jumlah_brg ?? '-' }}</td>
                            <td class="text-center">{{ $aset->harga ? number_format($aset->harga, 0, ',', '.') : '-' }}</td>
                            <td class="text-center">{{ $aset->kondisi == 'Baik' ? $aset->jumlah_brg : 0 }}</td>
                            <td class="text-center">{{ $aset->kondisi == 'Kurang Baik' ? $aset->jumlah_brg : 0 }}</td>
                            <td class="text-center">{{ $aset->kondisi == 'Rusak Berat' ? $aset->jumlah_brg : 0 }}</td>
                        </tr>
                        @endforeach
                        @endforeach

                        @if($asets->isEmpty())
                        <tr>
                            <td class="text-center" colspan="10">Tidak ada data aset ditemukan.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-1 d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center overflow-auto pagination-toolbar">
                <!-- INFO di kiri -->
                <div class="medium me-md-3 page-info text-md-start w-100 w-md-auto">
                    <p class="mb-0"> Showing: {{ $asets->currentPage() }} to {{ $asets->lastPage() }} of {{ $asets->total() }} entries</p>
                </div>

                <!-- NAVIGASI di kanan -->
                <div class="page-nav d-flex justify-content-center justify-content-md-end w-100 w-md-auto">
                    @if($asets->hasPages())
                    {{ $asets->links() }}
                    @else
                    <p class="text-muted mb-0">Tidak ada halaman tambahan</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $('#filteredTable').DataTable();

        // Event delegation untuk tombol pilih ruangan
        $('#myTable').on('click', '.btn-pilih-ruang', function() {
            const ruangId = $(this).data('id');
            const ruangName = $(this).data('nama');

            // Close modal first
            $('#searchModal').modal('hide');

            // Small delay to ensure modal is closed before redirect
            setTimeout(function() {
                window.location.href = "{{ route('opruang.index') }}" + "?ruang_id=" + ruangId;
            }, 300);
        });

        // Hover seluruh grup (ruangan) saat salah satu baris dihover
        const $tbody = $('#opruang-table tbody');
        $tbody.on('mouseenter', 'tr', function() {
            const group = $(this).data('ruang');
            if (group !== undefined) {
                $tbody.find('tr[data-ruang="' + group + '"]').addClass('group-hover');
            }
        });
        $tbody.on('mouseleave', 'tr', function() {
            const group = $(this).data('ruang');
            if (group !== undefined) {
                $tbody.find('tr[data-ruang="' + group + '"]').removeClass('group-hover');
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .table-gradient-header {
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

    /* Debug info styling */
    .debug-info {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    /* Layout tombol (samakan dengan histori) - Mode PC */
    .aksi-filter .btn,
    .aksi-filter .dropdown {
        width: auto;
        min-width: 120px;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @media (max-width: 768px) {
        #myTable {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    /* Nonaktifkan pointer/ikon sort untuk kolom tertentu (misal kolom 6 dan 7) */
    #myTable th:nth-child(7),
    #myTable th:nth-child(8) {
        pointer-events: none;
        background-image: none !important;
    }

    #myTable {
        width: 100% !important;
        table-layout: fixed;
        /* mirip efek autoWidth: false */
    }

    /* Supaya tabel bisa di-scroll horizontal */
    .dataTables_wrapper {
        overflow-x: auto;
    }

    /* Highlight satu grup ruangan saat hover */
    #opruang-table tbody tr.group-hover td,
    #opruang-table tbody tr.group-hover th {
        background-color: rgb(235, 235, 235) !important;
        /* soft highlight */
        transition: background-color .15s ease-in-out;
    }

    /* Hover baris biasa (non-group) */
    #opruang-table tbody tr:hover td,
    #opruang-table tbody tr:hover th {
        background-color: rgba(0, 0, 0, 0.05) !important;
        /* abu lembut transparan */
        transition: background-color .15s ease-in-out;
    }

    /* Matikan hover bawaan Bootstrap/DataTables */
    #opruang-table.table-hover tbody tr:hover>td,
    #opruang-table.table-hover tbody tr:hover>th,
    #opruang-table.dataTable.display tbody tr:hover>td,
    #opruang-table.dataTable.display tbody tr:hover>th,
    #opruang-table.dataTable.hover tbody tr:hover>td,
    #opruang-table.dataTable.hover tbody tr:hover>th {
        background-color: transparent !important;
    }

    /* Border tabel tetap terlihat (light mode) */
    #opruang-table,
    #opruang-table th,
    #opruang-table td {
        border-color: #dee2e6 !important;
        /* bootstrap default */
        border-style: solid;
        border-width: 1.5px;
    }

    /* Highlight grup ruangan di dark mode */
    body.dark-mode #opruang-table tbody tr.group-hover td,
    body.dark-mode #opruang-table tbody tr.group-hover th {
        background-color: #2a2a3d !important;
        /* abu gelap lembut */
        transition: background-color .15s ease-in-out;
    }

    /* Hover baris biasa di dark mode */
    body.dark-mode #opruang-table tbody tr:hover td,
    body.dark-mode #opruang-table tbody tr:hover th {
        background-color: rgba(255, 255, 255, 0.08) !important;
        /* putih transparan lembut */
        transition: background-color .15s ease-in-out;
    }

    /* Border kontras di dark mode */
    body.dark-mode #opruang-table,
    body.dark-mode #opruang-table th,
    body.dark-mode #opruang-table td {
        border-color: #333 !important;
    }

    /* Mode HP */
    @media (max-width: 768px) {
        .aksi-filter {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
            margin-right: 0 !important;
            /* override me-4 */
            margin-left: 0 !important;
        }

        .aksi-filter .btn,
        .aksi-filter .dropdown {
            width: 100% !important;
        }

        /* Form pembungkus tombol agar bisa full width */
        .aksi-filter form {
            width: 100% !important;
            display: block !important;
        }

        /* Stack info text above pagination on mobile */
        .pagination-toolbar {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .pagination-toolbar .page-info {
            margin-right: 0 !important;
        }

        .pagination-toolbar .page-nav {
            margin-top: .5rem;
        }

        /* Center align text and pagination on mobile */
        .pagination-toolbar .page-info,
        .pagination-toolbar .page-nav {
            width: 100%;
            text-align: center !important;
        }

        .pagination-toolbar .page-info p {
            width: 100%;
            text-align: center !important;
        }

        .pagination-toolbar .page-nav nav {
            justify-content: center !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .pagination-toolbar .page-nav .d-flex {
            justify-content: center !important;
        }

        .pagination-toolbar .page-nav .pagination {
            justify-content: center !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }

    @media screen and (min-width: 992px) {
        #searchModal .table-responsive {
            overflow-x: auto;
            /* scroll horizontal jika perlu */
        }

        #searchModal #myTable {
            table-layout: auto;
            /* kolom menyesuaikan konten */
            width: 100%;
        }

        /* Kolom No */
        #searchModal #myTable th:nth-child(1),
        #searchModal #myTable td:nth-child(1) {
            min-width: 50px;
            /* cukup untuk nomor */
            width: 50px;
            text-align: center;
            white-space: nowrap;
        }

        /* Kolom Kode Ruangan */
        #searchModal #myTable th:nth-child(2),
        #searchModal #myTable td:nth-child(2) {
            min-width: 120px;
            width: 120px;
            white-space: nowrap;
            text-align: center;
        }

        /* Kolom Nama Ruang */
        #searchModal #myTable th:nth-child(3),
        #searchModal #myTable td:nth-child(3) {
            min-width: 150px;
            width: auto;
            /* fleksibel */
            white-space: normal;
            /* bisa melipat teks */
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }

        /* Kolom Aksi */
        #searchModal #myTable th:nth-child(4),
        #searchModal #myTable td:nth-child(4) {
            min-width: 120px;
            width: 120px;
            text-align: center;
            white-space: nowrap;
        }
    }

    @media screen and (min-width: 992px) {
        #searchModal .table-responsive {
            overflow-x: auto;
            /* scroll horizontal jika perlu */
        }

        #searchModal #myTable {
            table-layout: auto;
            /* kolom menyesuaikan konten */
            width: 100%;
        }

        /* Kolom No */
        #searchModal #myTable th:nth-child(1),
        #searchModal #myTable td:nth-child(1) {
            min-width: 5px;
            /* cukup untuk nomor */
            width: 2px;
            text-align: center;
            white-space: nowrap;
        }

        /* Kolom Kode Ruangan */
        #searchModal #myTable th:nth-child(2),
        #searchModal #myTable td:nth-child(2) {
            min-width: 10px;
            width: 10px;
            white-space: nowrap;
            text-align: center;
        }

        /* Kolom Nama Ruang */
        #searchModal #myTable th:nth-child(3),
        #searchModal #myTable td:nth-child(3) {
            min-width: 250px;
            width: auto;
            /* fleksibel */
            white-space: normal;
            /* bisa melipat teks */
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }

        /* Kolom Aksi */
        #searchModal #myTable th:nth-child(4),
        #searchModal #myTable td:nth-child(4) {
            min-width: 5px;
            width: 5px;
            text-align: center;
            white-space: nowrap;
        }
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
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        console.log('Document ready');

        // Debug: Check if buttons exist
        console.log('Total btn-pilih-ruang buttons:', $('.btn-pilih-ruang').length);

        // Handle room selection button click with proper event delegation
        $(document).on('click', '.btn-pilih-ruang', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const ruangId = $(this).data('id');
            const ruangName = $(this).data('nama');

            console.log('Button clicked:', ruangId, ruangName); // Debug log

            // Close modal first
            $('#searchModal').modal('hide');

            // Small delay to ensure modal is closed before redirect
            setTimeout(function() {
                window.location.href = "{{ route('opruang.index') }}" + "?ruang_id=" + ruangId;
            }, 300);
        });

        // Debug: Check modal functionality
        $('#searchModal').on('shown.bs.modal', function() {
            console.log('Modal shown');
        });

        $('#searchModal').on('hidden.bs.modal', function() {
            console.log('Modal hidden');
        });
    });
</script>
@endpush