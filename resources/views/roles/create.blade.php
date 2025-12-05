@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-3"></i> Buat Role Baru
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-2"></i> Buat Role Baru
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {!! Form::open(['route' => 'roles.store','method'=>'POST']) !!}
    <div class="section-body" style="margin-top: 10px; margin-bottom: 3rem;">
        <div class="card">
            <div class="card-body p-4">
                <div class="row">
                    {{-- Input Nama Role --}}
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label mb-0">
                            <strong>Nama Role <span class="text-danger">*</span></strong>
                        </label>
                        <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                            *harap isi role.
                        </small>
                        {!! Form::text('name', null, ['placeholder' => 'Nama Role','class' => 'form-control']) !!}
                        @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permission per Kategori --}}
                    <div class="col-md-12 mb-3">
                        <label for="permission" class="form-label">
                            <strong>Permission</strong>
                        </label>
                        <hr class="sidebar-divider" style="border: none; height: 3px; background-color: #000; margin: 0.25rem 0;">

                        <div class="row">
                            @foreach($groupedPermissions as $kategori => $permissionGroup)
                            <div class="col-md-3 mb-2">
                                <h6 class="mt-1"><strong>{{ ucfirst($kategori) }}</strong></h6> {{-- judul lebih kecil --}}
                                <div class="table-responsive" style="margin-bottom: 0;">
                                    <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 0.8rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;" class="text-center">Pilih</th>
                                                <th>Nama Permission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permissionGroup as $value)
                                            <tr>
                                                <td class="text-center">
                                                    {{ Form::checkbox('permission[]', $value->id, false, ['class' => 'form-check-input']) }}
                                                </td>
                                                <td>{{ $value->name }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center aksi-save">
                        <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<style>
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

        #judul-roles .fa-plus-square {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }
</style>

@endpush