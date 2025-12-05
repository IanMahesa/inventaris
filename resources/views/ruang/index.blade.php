@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-3"></i> List Ruangan
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-2"></i> List Ruangan
        </span>
    </h5>

    <hr class="section-divider">

    @can('ruang-create')
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-md-end align-items-center gap-2 aksi-ruang aksi-tambah" style="position: relative;">
                <a href="{{ route('ruang.create') }}" class="btn btn-success btn-sm" style="min-width: 120px;">
                    <i class="fas fa-fw fa-plus-circle me-1"></i>Tambah Ruangan
                </a>
            </div>
        </div>
    </div>
    @endcan

    <div class="card">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="myTable" class="table table-hover table-bordered display nowrap w-100">
                    <thead class="table-gradient-header">
                        <tr class="text-center">
                            <th class="text-center" style="width: 10%;">No</th>
                            <th class="text-center" style="width: 20%;">Kode Ruangan</th>
                            <th class="text-center" style="width: 50%;">Nama Ruangan</th>
                            <th class="text-center" style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($ruangs as $key => $item)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="text-center align-middle">{{ str_pad($item->code, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="align-middle">{{ $item->name }}</td>
                            <td class="text-center align-middle td-aksi">
                                <a href="{{ route('ruang.show', $item) }}" class="btn btn-sm btn-info" title="Lihat">
                                    <i class="fas fa-search"></i>
                                </a>
                                @can('ruang-edit')
                                <a href="{{ route('ruang.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                <!-- Tombol hapus -->
                                @can('ruang-delete')
                                <button onclick="confirmDelete('form-id-{{ $item->code }}')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Form hapus -->
                                <form id="form-id-{{ $item->code }}" action="{{ route('ruang.destroy', ['ruang' => $item->code]) }}" method="POST" style="display: none;">
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

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Mode HP */
    @media (max-width: 576px) {
        .aksi-ruang {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }

        .aksi-ruang .btn {
            width: 100% !important;
        }

        #judul-ruang {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-ruang .fa-cog {
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
        $('#myTable').DataTable({
            autoWidth: false,
            responsive: true,
            scrollX: true,
            columnDefs: [{
                    targets: 0,
                    className: 'text-center'
                },
                {
                    targets: 1,
                    className: 'text-center'
                },
                {
                    targets: 3,
                    className: 'text-center'
                }
            ]
        });
    });

    function confirmDelete(formId) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Apakah anda yakin?",
            text: "Ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Tidak, Batalkan!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Batal",
                    text: "Data tidak dihapus.",
                    icon: "error"
                });
            }
        });
    }
</script>
@endpush