@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-edit me-3"></i> Edit User
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-edit me-2"></i> Edit User
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
    <div class="section-body" style="margin-top: 10px; margin-bottom: 3rem;">
        <div class="card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label"><strong>User Name</strong></label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="username" class="form-label"><strong>Username</strong></label>
                        {!! Form::text('username', null, ['placeholder' => 'Username','class' => 'form-control']) !!}
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="password" class="form-label"><strong>Password</strong></label>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="confirm-password" class="form-label"><strong>Confirm Password</strong></label>
                        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                    </div>

                    <div class="col-md-12 mb-4 position-relative">
                        <label for="roles" class="form-label"><strong>Role</strong></label>

                        <div class="position-relative">
                            {!! Form::select('roles', $roles, $userRole, ['class' => 'form-control pe-5', 'id' => 'roles']) !!}
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

        #judul-users .fa-edit {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }
</style>
@endpush