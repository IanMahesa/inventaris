@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-eye me-3"></i> Detail Role
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-eye me-2"></i> Detail Role
        </span>
    </h5>

    <hr class="section-divider">

    <div class="section-body my-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <table class="table table-bordered text-first align-middle mb-0">
                            <thead class="table-gradient-header">
                                <th scope="row" class="text-start ps-3" style="width: 20%;"><strong>Name:</strong></th>
                                <td class="td-mode"><strong>{{ $role->name }}</strong></td>
                                </tr>
                                <tr class="table-gradient-header">
                                    <th class="text-start ps-3 align-middle"><strong>Permissions:</strong></th>
                                    <td class="td-mode align-middle">
                                        @if(!empty($rolePermissions))
                                        @foreach($rolePermissions as $v)
                                        <span class="badge bg-success me-1 mb-1">{{ $v->name }}</span>
                                        @endforeach
                                        @else
                                        <span class="text-muted">Tidak ada permission</span>
                                        @endif
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-first mt-3 aksi-back ms-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* CSS Kustom untuk menyesuaikan ukuran di layar berukuran mobile */
    @media (max-width: 767.98px) {
        #judul-roles {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-roles .fa-eye {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }
</style>

@endpush