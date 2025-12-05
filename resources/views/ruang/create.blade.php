@extends('layout.backend')

@section('content')

<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-3"></i> Tambah Ruangan
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-2"></i> Tambah Ruangan
        </span>
    </h5>

    <hr class="section-divider">

    <div class="section-body my-4">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('ruang.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-3 mb-3">
                            <label for="code" class="form-label"><strong>Kode Ruangan<span class="text-danger">*</span></strong></label>
                            <input class="form-control" type="text" name="code" id="code" placeholder="Kode Ruangan">
                            <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                *tolong data ini harap diisi.
                            </small>
                        </div>
                        <div class="col-12 col-md-9 mb-3">
                            <label for="name" class="form-label"><strong>Nama Ruangan<span class="text-danger">*</span></strong></label>
                            <input class="form-control" type="text" name="name" id="name" placeholder="Nama Ruangan">
                            <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                *tolong data ini harap diisi.
                            </small>
                        </div>
                    </div>
                    <div class="row gy-2 gx-0 gx-md-2 mt-4 align-items-stretch aksi-back">
                        <div class="col-12 col-md-auto px-1">
                            <a href="{{ route('ruang.index') }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto px-1 aksi-save">
                            <button type="submit" class="btn btn-success btn-sm w-100 me-md-2">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                        <div class="col-6 col-md-auto px-1 aksi-reset">
                            <button type="reset" class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @media (max-width: 767.98px) {
        #judul-ruang {
            font-size: 1.5rem !important;
        }

        #judul-ruang .fa-plus-square {
            font-size: 1.2rem !important;
        }
    }
</style>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        const swalTheme = {
            background: isDarkMode ? "#1e1e2f" : "#fff",
            color: isDarkMode ? "#f1f1f1" : "#000",
            confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6",
            iconColor: isDarkMode ? "#f8d44c" : "#f8bb86"
        };
        const swalAnimation = {
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        };
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ $errors->first() }}", // <-- pakai ini
            background: swalTheme.background,
            color: swalTheme.color,
            confirmButtonColor: swalTheme.confirmButtonColor,
            iconColor: swalTheme.iconColor,
            ...swalAnimation
        });
    });
</script>
@endif

@endpush