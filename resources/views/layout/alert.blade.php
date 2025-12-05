@push('scripts')
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = document.body.classList.contains('dark-mode');

        // Tema warna otomatis (dark/light)
        const swalTheme = {
            background: isDarkMode ? "#1e1e2f" : "#fff",
            color: isDarkMode ? "#f1f1f1" : "#000",
            confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6",
            iconColor: isDarkMode ? "#f8d44c" : "#f8bb86"
        };

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            background: swalTheme.background,
            color: swalTheme.color,
            iconColor: isDarkMode ? "#00d97e" : "#28a745" // hijau lembut untuk dark mode
        });
    });
</script>
@endif

@if ($errors->any())
@php
$messages = [
'The code has already been taken.' => 'Kode ruangan sudah digunakan!',
'The name has already been taken.' => 'Nama ruangan sudah digunakan!',
'The name field is required.' => 'Nama role harus diisi!',
'The password must be at least 8 characters.' => 'Password harus minimal 8 karakter!',
'The password and confirm-password must match.' => 'Konfirmasi password tidak cocok!',
'The confirm-password and password must match.' => 'Konfirmasi password tidak cocok!',
'The name has already been taken.' => 'Nama sudah digunakan!',
'The username has already been taken.' => 'Username sudah digunakan!',
 'The r sesudah field is required.' => 'Ruangan tujuan belum terisi!',
 'The selected r sesudah is invalid.' => 'Ruangan tujuan tidak valid!',
 'The r sesudah must be a string.' => 'Ruangan tujuan tidak valid!',
];

$text = 'Periksa kembali inputan Anda.';

foreach ($errors->all() as $msg) {
if (isset($messages[$msg])) {
$text = $messages[$msg];
break;
}
}
@endphp

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Deteksi dark mode
        const isDarkMode = document.body.classList.contains('dark-mode');

        // Tema warna SweetAlert
        const swalTheme = {
            background: isDarkMode ? "#1e1e2f" : "#fff",
            color: isDarkMode ? "#f1f1f1" : "#000",
            confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6",
            iconColor: isDarkMode ? "#f8d44c" : "#f8bb86"
        };

        // Animasi (pakai Animate.css)
        const swalAnimation = {
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        };

        // Tampilkan pesan error validasi
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ $text }}",
            background: swalTheme.background,
            color: swalTheme.color,
            confirmButtonColor: swalTheme.confirmButtonColor,
            iconColor: swalTheme.iconColor,
            ...swalAnimation
        });
    });
</script>
@endpush
@endif
@endpush