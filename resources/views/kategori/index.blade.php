@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-3"></i> List Jenis Barang
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-2"></i> List Jenis Barang
        </span>
    </h5>

    <hr class="section-divider">

    @can('kategori-create')
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-md-end align-items-center gap-2 aksi-kategori aksi-tambah" style="position: relative;">
                <a href="{{ route('kategori.create') }}" class="btn btn-success btn-sm" style="min-width: 120px;">
                    <i class="fas fa-fw fa-plus-circle me-1"></i> Tambah Jenis Brg
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
                            <th class="text-center" style="width: 20%;">Kode Jenis Barang</th>
                            <th class="text-center" style="width: 50%;">Nama Jenis Barang</th>
                            <th class="text-center" style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($kategoris as $key => $item)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="text-center align-middle">{{ $item->kode }}</td>
                            <td class="align-middle">{{ $item->nama }}</td>
                            <td class="text-center align-middle td-aksi">
                                <a href="{{ route('kategori.show', $item) }}" class="btn btn-sm btn-info" title="Lihat">
                                    <i class="fas fa-search"></i>
                                </a>
                                @can('kategori-edit')
                                <a href="{{ route('kategori.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('kategori-delete')
                                <button onclick="confirmDelete('form-id-{{ $item->kode }}')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Form hapus -->
                                <form id="form-id-{{ $item->kode }}" action="{{ route('kategori.destroy', ['kategori' => $item->kode]) }}" method="POST" style="display: none;">
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
        .aksi-kategori {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }

        .aksi-kategori .btn {
            width: 100% !important;
        }

        #judul-kategori {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-kategori .fa-cog {
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