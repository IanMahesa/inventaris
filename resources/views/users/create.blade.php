@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-3"></i> Buat User Baru
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-2"></i> Buat User Baru
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {!! Form::open(['route' => 'users.store','method'=>'POST']) !!}
    <div class="section-body" style="margin-top: 10px; margin-bottom: 3rem;">
        <div class="card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label mb-0">
                            <strong>Nama <span class="text-danger">*</span></strong>
                        </label>
                        <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                            *harap isi sesuai nama pengguna.
                        </small>
                        {!! Form::text('name', null, [ 'placeholder' => 'Name', 'class' => 'form-control' ]) !!}
                        @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="username" class="form-label mb-0"><strong>Username<span class="text-danger">*</span></strong></label>
                        <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                            *harap isi username untuk login.
                        </small>
                        {!! Form::text('username', null, ['placeholder' => 'Username','class' => 'form-control']) !!}
                        @error('confirm-username')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="password" class="form-label mb-0"><strong>Password<span class="text-danger">*</span></strong></label>
                        <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                            *harap isi minimal 8 karakter.
                        </small>
                        <div class="input-group">
                            {!! Form::password('password', ['placeholder'=> 'Password','class' => 'form-control', 'id' => 'password']) !!}
                            <span class="input-group-text toggle-password" style="cursor:pointer;">
                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                            </span>
                        </div>
                        @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="confirm-password" class="form-label mb-0"><strong>Confirm Password<span class="text-danger">*</span></strong></label>
                        <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                            *harap isi sesuai dengan password.
                        </small>
                        <div class="input-group">
                            {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password','class' => 'form-control', 'id' => 'confirmPassword']) !!}
                            <span class="input-group-text toggle-password" style="cursor:pointer;">
                                <i class="fa fa-eye-slash" id="toggleConfirmPassword"></i>
                            </span>
                        </div>
                        @error('confirm-password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4 position-relative">
                        <label for="roles" class="form-label mb-0">
                            <strong>Role <span class="text-danger">*</span></strong>
                        </label>
                        <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                            *harap pilih salah satu.
                        </small>
                        <div class="position-relative">
                            {!! Form::select('role', $roles, null, [
                            'class' => 'form-control',
                            'placeholder' => '-- Pilih Role --'
                            ]) !!}
                            <i class="fa fa-chevron-down position-absolute end-0 top-50 translate-middle-y me-3 text-secondary"></i>
                        </div>
                    </div>

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
        #judul-users {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-users .fa-plus-square {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }

    /* ====== Efek 3D umum ====== */
    .input-group {
        position: relative;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .input-group:focus-within {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .input-group-text {
        background-color: #fff;
        border-left: none;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Efek hover di mode terang */
    .toggle-password:hover i {
        color: #007bff;
        transform: scale(1.15);
        transition: transform 0.15s ease, color 0.15s ease;
    }

    /* ====== DARK MODE (hanya untuk eye & input group) ====== */
    body.dark-mode .input-group-text {
        background-color: #2e2e40;
        border-color: #555;
        box-shadow: inset 0 1px 3px rgba(255, 255, 255, 0.05),
            0 2px 6px rgba(0, 0, 0, 0.5);
    }

    body.dark-mode .input-group {
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.6);
    }

    body.dark-mode .input-group:focus-within {
        box-shadow: 0 5px 12px rgba(0, 0, 0, 0.8);
        transform: translateY(-1px);
    }

    body.dark-mode .toggle-password i {
        color: #cfcfcf;
        text-shadow: 0 1px 3px rgba(255, 255, 255, 0.1);
        transition: color 0.2s ease, transform 0.15s ease;
    }

    body.dark-mode .toggle-password:hover i {
        color: #4da3ff;
        transform: scale(1.15);
    }

    /* ====== ANIMASI BERKEDIP (mata buka-tutup) ====== */
    @keyframes eyeBlink {
        0% {
            transform: scaleY(1);
            opacity: 1;
        }

        25% {
            transform: scaleY(0.1);
            opacity: 0.5;
        }

        50% {
            transform: scaleY(0);
            opacity: 0.2;
        }

        75% {
            transform: scaleY(0.1);
            opacity: 0.6;
        }

        100% {
            transform: scaleY(1);
            opacity: 1;
        }
    }

    .blink {
        animation: eyeBlink 0.4s ease-in-out;
        transform-origin: center center;
    }
</style>
@endpush