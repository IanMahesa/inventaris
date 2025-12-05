@extends('layout.backend')

@section('content')
@include('layout.alert')

<div class="container-fluid px-3 px-md-4">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-history me-3"></i> Data Histori
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-history me-2"></i> Data Histori
        </span>
    </h5>

    <hr class="section-divider">

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <!-- Filter tanggal -->
                <div class="col-12 col-md-auto d-flex align-items-center gap-2 filter-tanggal">
                    <label for="startDate" class="form-label mb-0">Tanggal</label>
                    <input type="date" id="startDate" class="form-control form-control-sm" style="max-width:160px"
                        value="{{ request('startDate') }}">
                    <span>s/d</span>
                    <input type="date" id="endDate" class="form-control form-control-sm" style="max-width:160px"
                        value="{{ request('endDate') }}">
                </div>

                <!-- Aksi Filter -->
                <div class="col-12 col-md d-flex justify-content-md-end gap-2 mt-2 mt-md-0 aksi-filter aksi-filter aksi-cetak aksi-reset">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle btn-sm" type="button" id="dropdownMenu2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <strong>Pilih Status</strong>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <li><button class="dropdown-item" type="button" onclick="filterByStatus('PDH')">Pindah</button></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><button class="dropdown-item" type="button" onclick="filterByStatus('LLG')">Lelang</button></li>
                        </ul>
                    </div>

                    <button class="btn btn-secondary btn-sm" type="button" onclick="resetFilter()">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>

                    @can('histori-print')
                    <button id="btnCetak" class="btn btn-primary btn-sm" onclick="cetakHistori()" disabled>
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
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
                        <tr>
                            <th class="text-center" style="width: 2%;">No</th>
                            <th class="text-center" style="width: 7%;">Tgl Input</th>
                            <th class="text-center" style="width: 7%;">Nama Barang</th>
                            <th class="text-center" style="width: 7%;">Kode Aset</th>
                            <th class="text-center" style="width: 10%;">Ruang Sebelum</th>
                            <th class="text-center" style="width: 10%;">Ruang Sesudah</th>
                            <th class="text-center" style="width: 3%;">Status</th>
                            <th class="text-center" style="width: 15%;">Keterangan</th>
                            <th class="text-center" style="width: 4%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($historis as $item)
                        @php
                        $tahun = \Carbon\Carbon::parse($item->tanggal)->format('y');
                        $bulan = \Carbon\Carbon::parse($item->tanggal)->format('m');
                        $st_history = $item->st_histori ?? 'XX';
                        $kodeKategori = $item->kategori->kode ?? 'XX';
                        $idFormat = str_pad($item->id_asetsblm ?? 0, 4, '0', STR_PAD_LEFT);
                        $id_regis = "$st_history-$tahun-$bulan-$idFormat";
                        $tglYmd = \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d');
                        $tglDmy = \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y');
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center" data-date="{{ $tglYmd }}">{{ $tglDmy }}</td>
                            <td class="text-center">{{ $item->name_brg ?? '-' }}</td>
                            <td class="text-center">{{ $id_regis }}</td>
                            <td>{{ $item->ruangSebelum->name ?? '-' }}</td>
                            <td>{{ $item->ruangSesudah->name ?? '-' }}</td>
                            <td class="text-center" data-status="{{ $item->st_histori ?? '-' }}">
                                @php
                                $status = $item->st_histori ?? '-';
                                echo $status === 'PDH' ? 'Pindah' :
                                ($status === 'RSK' ? 'Rusak' :
                                ($status === 'LLG' ? 'Lelang' : $status));
                                @endphp
                            </td>
                            <td>{{ $item->ket ?? '-' }}</td>
                            <td class="text-center align-middle td-aksi">
                                <a href="{{ route('histori.show', $item->id_histori) }}" class="btn btn-info btn-sm" title="Lihat">
                                    <i class="fas fa-search"></i>
                                </a>
                                @can('histori-delete')
                                <button onclick="confirmDelete('form-id-{{ $item->id_histori }}')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="form-id-{{ $item->id_histori }}" action="{{ route('histori.destroy', ['histori' => $item->id_histori]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

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

    /* Default (PC/laptop) */
    .filter-tanggal {
        display: flex !important;
        align-items: center;
        gap: .5rem;
    }

    .filter-tanggal label {
        margin-bottom: 0;
    }

    .filter-tanggal input {
        max-width: 160px;
    }

    /* Tombol PC kecil */
    .aksi-filter .btn,
    .aksi-filter .dropdown {
        width: auto;
        min-width: 120px;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Mode HP */
    @media (max-width: 576px) {

        /* Tanggal: label di atas, dua input dalam satu baris dengan 's/d' di tengah */
        .filter-tanggal {
            display: grid !important;
            /* override .d-flex Bootstrap */
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            gap: .5rem;
            align-items: center;
            width: 100%;
            padding: 0 2px;
            box-sizing: border-box;
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

        /* Tombol aksi full di HP */
        .aksi-filter {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }

        .aksi-filter .btn,
        .aksi-filter .dropdown {
            width: 100% !important;
        }

        /* Pastikan semua anak di dalam aksi-filter full width (termasuk form/anchor/button) */
        .aksi-filter>* {
            width: 100% !important;
        }

        /* Pastikan form tidak inline agar tombol di dalamnya bisa full width */
        .aksi-filter form {
            width: 100% !important;
            display: block !important;
        }

        /* Pastikan tombol dropdown (toggle) ikut full width */
        .aksi-filter .dropdown .btn {
            width: 100% !important;
            display: block !important;
        }

        .aksi-filter .dropdown-menu {
            width: 100% !important;
        }

        #judul-histori {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-histori .fa-history {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            autoWidth: false,
            responsive: true,
            scrollX: true
        });

        // Filter tanggal
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var min = $('#startDate').val();
            var max = $('#endDate').val();
            var dateStr = $(table.row(dataIndex).node()).find('td:eq(1)').data('date'); // ambil data-date (Y-m-d)

            if (!dateStr) return true;

            var date = new Date(dateStr);

            if (min && new Date(min) > date) return false;
            if (max && new Date(max) < date) return false;

            return true;
        });

        // Trigger ketika input tanggal berubah
        $('#startDate, #endDate').on('change', function() {
            table.draw();
        });

        // Filter status + tanggal
        window.filterByStatus = function(status) {
            $.fn.dataTable.ext.search.pop(); // buang filter lama

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var min = $('#startDate').val();
                var max = $('#endDate').val();
                var dateStr = $(table.row(dataIndex).node()).find('td:eq(1)').data('date');
                var rowStatus = $(table.row(dataIndex).node()).find('td:eq(6)').attr('data-status');
                if (!dateStr) return true;

                var date = new Date(dateStr);

                if (min && new Date(min) > date) return false;
                if (max && new Date(max) < date) return false;

                return rowStatus === status;
            });

            table.draw();

            var statusText = status === 'PDH' ? 'Pindah' : 'Lelang';
            $('#dropdownMenu2 strong').text('Status: ' + statusText);
            $('#btnCetak').prop('disabled', false);
        };

        // Reset filter
        window.resetFilter = function() {
            $('#startDate').val('');
            $('#endDate').val('');
            $.fn.dataTable.ext.search = []; // hapus semua filter
            table.draw();
            $('#dropdownMenu2 strong').text('Pilih Status');
            $('#btnCetak').prop('disabled', true);
        };

        // Cetak dengan status + range tanggal
        window.cetakHistori = function() {
            var currentStatus = $('#dropdownMenu2 strong').text();
            var status = '';
            if (currentStatus.includes('Pindah')) status = 'PDH';
            else if (currentStatus.includes('Lelang')) status = 'LLG';

            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (status) {
                var url = '{{ route("histori.print.status", ":status") }}'.replace(':status', status);

                var params = [];
                if (startDate) params.push('start=' + startDate);
                if (endDate) params.push('end=' + endDate);
                if (params.length > 0) url += '?' + params.join('&');

                window.open(url, '_blank');
            }
        };
    });
</script>
@endpush