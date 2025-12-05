@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-3"></i> Management User
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-2"></i> Management User
        </span>
    </h5>

    <hr class="section-divider">

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-md-end align-items-center gap-2 aksi-users aksi-tambah" style="position: relative;">
                @can('user-create')
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm" style="min-width: 120px;">
                    <i class="fas fa-fw fa-plus-circle me-1"></i> Tambah User
                </a>
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
                            <th class="text-center" style="width: 10%;">No</th>
                            <th class="text-center" style="width: 20%;">Name</th>
                            <th class="text-center" style="width: 20%;">Username</th>
                            <th class="text-center" style="width: 20%;">Roles</th>
                            <th class="text-center" style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($data as $key => $user)
                        <tr>
                            <td class="text-center align-middle">{{ ++$i }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td class="text-center align-middle">
                                @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                <span class="badge bg-success fs-6">{{ $v }}</span>
                                @endforeach
                                @endif
                            </td>
                            <td class="text-center align-middle td-aksi">
                                <a class="btn btn-info btn-sm me-1" href="{{ route('users.show', $user->id) }}" title="Lihat">
                                    <i class="fas fa-search"></i>
                                </a>
                                @can('user-edit')
                                <a class="btn btn-warning btn-sm me-1" href="{{ route('users.edit', $user->id) }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('user-delete') {{-- atau permission sesuai yang kamu tetapkan --}}
                                <!-- Tombol Delete -->
                                <button onclick="confirmDelete('form-id-{{ $user->id }}')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Form Delete tersembunyi -->
                                <form id="form-id-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
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
                {!! $data->render() !!}
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
        .aksi-users {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }

        .aksi-users .btn {
            width: 100% !important;
        }

        #judul-users {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-users .fa-cog {
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
                    targets: 1
                },
                {
                    targets: 2
                },
                {
                    targets: 3,
                    className: 'text-center'
                },
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