@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-3"></i>
            Management Role
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-2"></i>
            Management Role
        </span>
    </h5>

    <hr class="section-divider">

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-md-end align-items-center gap-2 aksi-roles aksi-tambah" style="position: relative;">
                @can('role-create')
                <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm" style="min-width: 120px;">
                    <i class="fas fa-fw fa-plus-circle me-1"></i> Tambah Role</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="myTable" class="table table-hover table-bordered display nowrap w-100">
                    <thead class="table-gradient-header">
                        <tr>
                            <th class="text-center" style="width: 5%;">No</th>
                            <th class="text-center" style="width: 10%;">Name</th>
                            <th class="text-center" style="width: 45%;">Permissions</th>
                            <th class="text-center" style="width: 25%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td class="text-center align-middle">{{ ++$i }}</td>
                            <td class="text-center align-middle">
                                <span class="badge bg-success fs-6">{{ $role->name }}</span>
                            </td>
                            <td class="text-center align-middle">{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                            <td class="text-center align-middle td-aksi">
                                <a class="btn btn-info btn-sm me-1" href="{{ route('roles.show', $role->id) }}" title="Lihat">
                                    <i class="fas fa-search"></i>
                                </a>
                                @can('role-edit')
                                <a class="btn btn-warning btn-sm me-1" href="{{ route('roles.edit', $role->id) }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('role-delete')
                                <button onclick="confirmDelete('form-id-{{ $role->id }}')" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="form-id-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: none;">
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
            <div class="mt-3">
                {!! $roles->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@verbatim
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

    /* Keep table wide enough to match desktop layout; .table-responsive will scroll */
    #myTable {
        min-width: 900px;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Mode HP */
    @media (max-width: 576px) {
        .aksi-roles {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }

        .aksi-roles .btn {
            width: 100% !important;
        }
    }

    /* Wrap kolom Permissions agar menyesuaikan layar */
    #myTable th:nth-child(3),
    #myTable td:nth-child(3) {
        white-space: normal !important;
        /* override .nowrap */
        overflow-wrap: anywhere;
        word-break: break-word;
        text-align: left;
        /* lebih nyaman dibaca */
    }

    /* Mobile: wrap seperti PC tetapi beri lebar lebih besar agar tidak terlalu pendek */
    @media (max-width: 576px) {

        #myTable th:nth-child(3),
        #myTable td:nth-child(3) {
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        #judul-roles {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-roles .fa-cog {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }
</style>
@endverbatim
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        const isMobile = window.matchMedia('(max-width: 576px)').matches;
        $('#myTable').DataTable({
            autoWidth: true,
            responsive: true,
            scrollX: true,
            columnDefs: [{
                    targets: 0,
                    className: 'text-center align-middle'
                },
                {
                    targets: 1,
                    className: 'text-center align-middle'
                },
                {
                    targets: 2,
                    className: 'text-start align-middle'
                },
                {
                    targets: 3,
                    className: 'text-center align-middle'
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