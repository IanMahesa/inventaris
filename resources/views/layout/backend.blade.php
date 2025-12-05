<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables 1.13 + Bootstrap 5 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Custom template CSS -->
    <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* === GLOBAL DARK MODE === */
        body.dark-mode {
            background-color: #1e1e2f;
            color: #f1f1f1;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Form control readonly di dark mode - lebih gelap dan redup dari normal */
        body.dark-mode .form-control[readonly],
        body.dark-mode .form-select[readonly],
        body.dark-mode input[readonly],
        body.dark-mode textarea[readonly],
        body.dark-mode select[readonly] {
            background-color: rgba(25, 25, 40, 0.7) !important;
            /* lebih gelap dari form-control normal (rgba(34, 42, 66, 0.5)) */
            color: #a0a0a0 !important;
            /* teks lebih redup dari normal (#f1f1f1) tapi masih bisa dibaca */
            border-color: #4a4a5a !important;
            /* border lebih gelap dan lembut */
            box-shadow: inset 1px 1px 3px rgba(0, 0, 0, 0.8) !important;
            /* shadow lebih dalam untuk efek "nonaktif" */
            cursor: not-allowed !important;
            /* tampil seperti nonaktif */
            opacity: 0.85 !important;
        }

        /* Form control disabled di dark mode - paling gelap dan paling redup */
        body.dark-mode .form-control:disabled,
        body.dark-mode .form-select:disabled,
        body.dark-mode input:disabled,
        body.dark-mode textarea:disabled,
        body.dark-mode select:disabled {
            background-color: rgba(20, 20, 35, 0.8) !important;
            /* paling gelap */
            color: #777 !important;
            /* teks paling redup */
            border-color: #3a3a4a !important;
            /* border paling gelap */
            box-shadow: inset 1px 1px 3px rgba(0, 0, 0, 0.9) !important;
            cursor: not-allowed !important;
            opacity: 0.7 !important;
        }

        /* === CONTAINER === */
        body.dark-mode .container {
            background-color: transparent !important;
            color: #f0f0f0 !important;
            padding: 20px;
        }

        body.dark-mode .container-fluid {
            background: #1e1e2f;
            /* warna gelap */
        }

        body.dark-mode #content-wrapper {
            background: #1e1e2f !important;
        }

        /* === NAVBAR === */
        body.dark-mode .navbar {
            background: rgba(34, 42, 66, .5) !important;
            border-bottom: 1px solid #333 !important;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
        }

        /* === DROPDOWN === */
        body.dark-mode .dropdown-menu {
            background: linear-gradient(145deg, rgb(34, 42, 66), rgb(58, 71, 99)) !important;
            color: #f1f1f1 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
        }

        body.dark-mode .dropdown-menu .dropdown-item {
            color: #cfcfcf !important;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode .dropdown-menu .dropdown-item:hover {
            background: linear-gradient(145deg, rgba(80, 90, 130, 0.8), rgba(50, 60, 90, 0.8)) !important;
            color: #f1f1f1 !important;
        }

        /* === CARD === */
        body.dark-mode .card {
            background: rgba(34, 42, 66, .5) !important;
            border-color: #333 !important;
            color: #f1f1f1 !important;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.6),
                -4px -4px 8px rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease-in-out;
        }

        /* Form control dark mode */
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: rgba(34, 42, 66, 0.5) !important;
            color: #f1f1f1 !important;
            border: 1px solid #444 !important;
            box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.6),
                inset -2px -2px 5px rgba(255, 255, 255, 0.05);
            /* efek cekung */
            transition: all 0.2s ease;
        }

        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            background-color: rgba(30, 30, 47, 0.96) !important;
            color: #fff !important;
            border-color: #999 !important;
            box-shadow: inset 2px 2px 6px rgba(0, 0, 0, 0.7),
                inset -2px -2px 6px rgba(255, 255, 255, 0.1);
            /* lebih dalam saat fokus */
        }


        /* === TABLE DARK MODE === */
        body.dark-mode .table {
            background-color: #1e1e2f !important;
            /* ubah agar senada dengan tbody */
            color: #f1f1f1 !important;
            border-color: #333 !important;
        }

        /* Table cells and rows */
        body.dark-mode .table td,
        body.dark-mode .table th {
            color: #f1f1f1 !important;
            border-color: #333 !important;
            background-color: transparent !important;
        }

        body.dark-mode .table tbody tr {
            background-color: #1e1e2f !important;
        }

        /* Bootstrap striped rows */
        body.dark-mode .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #24243a !important;
        }

        /* DataTables odd/even rows */
        body.dark-mode table.dataTable tbody tr {
            background-color: #1e1e2f !important;
        }

        body.dark-mode table.dataTable.stripe tbody tr.odd,
        body.dark-mode table.dataTable.display tbody tr.odd {
            background-color: #1c1c33 !important;
        }

        body.dark-mode table.dataTable.stripe tbody tr.even,
        body.dark-mode table.dataTable.display tbody tr.even {
            background-color: #1e1e2f !important;
        }

        /* Versi lebih kontras */
        body.dark-mode .table thead {
            background: linear-gradient(145deg, #3a4266, #1f253a) !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .table thead th {
            background: linear-gradient(145deg, #3a4266, #1f253a) !important;
            background-color: transparent !important;
            color: #fff !important;
            border-color: #444 !important;
        }

        body.dark-mode .table-gradient-header th {
            background: linear-gradient(145deg, #3a4266, #1f253a) !important;
            background-color: transparent !important;
            color: #fff !important;
            border-color: #444 !important;
        }

        body.dark-mode .table tbody tr:hover {
            background-color: #2a2a4a !important;
        }

        body.dark-mode table.dataTable.hover tbody tr:hover,
        body.dark-mode table.dataTable.display tbody tr:hover {
            background-color: #141428 !important;
            /* lebih gelap dari sebelumnya */
            transition: background-color 0.2s ease-in-out;
            /* agar halus saat hover */
        }

        body.dark-mode .table-bordered td,
        body.dark-mode .table-bordered th {
            border-color: #333 !important;
        }

        /* Responsive wrapper */
        body.dark-mode .table-responsive {
            background-color: #181818 !important;
            border: 1px solid #333 !important;
        }

        /* === DATA TABLES WRAPPER DARK MODE - NYATU TOTAL DENGAN CARD === */
        body.dark-mode .dataTables_wrapper {
            background: transparent !important;
            /* ikut warna card */
            color: #f1f1f1 !important;
            border: none !important;
            box-shadow: none !important;
            /* hilangkan bayangan */
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Buat area table-responsive juga transparan agar tidak menambah layer */
        body.dark-mode .table-responsive {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Input & dropdown serasi dengan tone card */
        body.dark-mode .dataTables_wrapper .dataTables_length select,
        body.dark-mode .dataTables_wrapper .dataTables_filter input {
            background: rgba(34, 42, 66, 0.55) !important;
            /* sama tone dengan card */
            color: #f1f1f1 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 0.4rem !important;
            box-shadow: inset 1px 1px 2px rgba(0, 0, 0, 0.4);
            transition: all 0.2s ease-in-out;
        }

        /* Styling untuk option di dalam dropdown select */
        body.dark-mode .dataTables_wrapper .dataTables_length select option {
            background: rgba(34, 42, 66, 0.95) !important;
            color: #f1f1f1 !important;
        }

        /* Custom arrow putih untuk dropdown select di dark mode */
        body.dark-mode .dataTables_wrapper .dataTables_length select {
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23f1f1f1' d='M6 9L1 4h10z'/%3E%3C/svg%3E"),
                linear-gradient(to bottom, rgba(34, 42, 66, 0.55), rgba(34, 42, 66, 0.55)) !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center, center !important;
            background-size: 12px 12px, 100% 100% !important;
            padding-right: 2.2rem !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }

        body.dark-mode .dataTables_wrapper .dataTables_length select:focus,
        body.dark-mode .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: rgba(100, 149, 237, 0.4) !important;
            /* aksen biru lembut */
            box-shadow: 0 0 6px rgba(100, 149, 237, 0.25);
        }

        /* Semua teks control tetap terang */
        body.dark-mode .dataTables_wrapper .dataTables_length,
        body.dark-mode .dataTables_wrapper .dataTables_filter,
        body.dark-mode .dataTables_wrapper .dataTables_info,
        body.dark-mode .dataTables_wrapper .dataTables_paginate {
            color: #f1f1f1 !important;
            background: transparent !important;
        }

        /* === DATATABLES PAGINATION DARK MODE === */
        body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: rgba(30, 30, 47, 0.75) !important;
            /* selaras dengan #1e1e2f */
            color: #f1f1f1 !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 0.4rem !important;
            box-shadow: inset 1px 1px 2px rgba(0, 0, 0, 0.4);
            transition: all 0.2s ease-in-out;
        }

        /* Tombol aktif (current page) */
        body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: rgba(60, 70, 120, 0.9) !important;
            /* lebih terang, biru lembut */
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
        }

        /* Hover effect */
        body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: rgba(20, 20, 40, 0.9) !important;
            /* lebih gelap saat hover */
            color: #fff !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            transform: scale(1.03);
            /* sedikit membesar agar interaktif */
        }

        /* Processing indicator (loading DataTables) */
        body.dark-mode .dataTables_wrapper .dataTables_processing {
            background: rgba(30, 30, 47, 0.85) !important;
            color: #f1f1f1 !important;
            border-radius: 0.5rem;
            box-shadow: none !important;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* === BOOTSTRAP / LARAVEL PAGINATION DARK MODE === */
        body.dark-mode .pagination {
            --bs-pagination-color: #f1f1f1;
            --bs-pagination-bg: #1e1e2f;
            --bs-pagination-border-color: #2a2a3a;
            --bs-pagination-hover-color: #fff;
            --bs-pagination-hover-bg: #141428;
            /* hover lebih gelap */
            --bs-pagination-hover-border-color: #333;
            --bs-pagination-focus-color: #fff;
            --bs-pagination-focus-bg: #202038;
            --bs-pagination-focus-box-shadow: 0 0 0 0.1rem rgba(80, 100, 180, 0.25);
            --bs-pagination-active-color: #fff;
            --bs-pagination-active-bg: #3c4680;
            --bs-pagination-active-border-color: #4c5a9e;
            --bs-pagination-disabled-color: #9aa0a6;
            --bs-pagination-disabled-bg: #1a1a28;
            --bs-pagination-disabled-border-color: #2a2a3a;
        }

        /* Tombol default */
        body.dark-mode .page-link {
            background-color: var(--bs-pagination-bg) !important;
            color: var(--bs-pagination-color) !important;
            border-color: var(--bs-pagination-border-color) !important;
            transition: all 0.2s ease-in-out;
        }

        /* Hover */
        body.dark-mode .page-link:hover {
            background-color: var(--bs-pagination-hover-bg) !important;
            color: var(--bs-pagination-hover-color) !important;
            border-color: var(--bs-pagination-hover-border-color) !important;
            transform: scale(1.03);
        }

        /* Halaman aktif */
        body.dark-mode .page-item.active .page-link {
            background-color: var(--bs-pagination-active-bg) !important;
            border-color: var(--bs-pagination-active-border-color) !important;
            color: var(--bs-pagination-active-color) !important;
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
        }

        /* Halaman nonaktif */
        body.dark-mode .page-item.disabled .page-link {
            background-color: var(--bs-pagination-disabled-bg) !important;
            border-color: var(--bs-pagination-disabled-border-color) !important;
            color: var(--bs-pagination-disabled-color) !important;
            opacity: 0.6;
        }

        /* === Breadcrumbs (auto-breadcrumbs include) === */
        body.dark-mode #auto-breadcrumbs .breadcrumb-item a {
            color: #90caf9 !important;
        }

        body.dark-mode #auto-breadcrumbs .breadcrumb-item a:hover {
            color: #bbdefb !important;
            text-decoration: underline !important;
        }

        body.dark-mode #auto-breadcrumbs .breadcrumb-item.active {
            color: #e0e0e0 !important;
        }

        /* === CAROUSEL (Bootstrap) === */
        body.dark-mode .carousel,
        body.dark-mode .carousel-inner {
            background: linear-gradient(145deg, #1a1a29, #23233a) !important;
            /* gradasi lembut */
            box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.6),
                -6px -6px 12px rgba(255, 255, 255, 0.05);
            /* efek timbul */
            transition: all 0.3s ease;
        }

        body.dark-mode .carousel-item {
            background-color: #1e1e2f !important;
            overflow: hidden;
            box-shadow: inset 1px 1px 3px rgba(255, 255, 255, 0.05),
                inset -1px -1px 3px rgba(0, 0, 0, 0.5);
        }

        /* If slides have images, add a subtle dark overlay for readability */
        body.dark-mode .carousel-item::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35));
            pointer-events: none;
        }

        body.dark-mode .carousel-caption {
            color: #f1f1f1 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
        }

        /* Controls (prev/next) */
        body.dark-mode .carousel-control-prev,
        body.dark-mode .carousel-control-next {
            filter: none !important;
        }

        body.dark-mode .carousel-control-prev-icon,
        body.dark-mode .carousel-control-next-icon {
            filter: invert(1) grayscale(100%);
        }

        /* Indicators */
        body.dark-mode .carousel-indicators [data-bs-target] {
            background-color: #bbb !important;
        }

        body.dark-mode .carousel-indicators .active {
            background-color: #fff !important;
        }

        /* === Section Divider (HR) === */
        .section-divider {
            height: 2px;
            background-color: #000;
            border: none;
        }

        body.dark-mode .section-divider {
            background-color: #444 !important;
        }

        /* === CheckBox === */
        body.dark-mode .form-check-input {
            background-color: #1e1e2f !important;
            border-color: #666 !important;
            box-shadow:
                inset 0 0 4px rgba(255, 255, 255, 0.1),
                0 0 3px rgba(120, 160, 255, 0.6),
                0 0 6px rgba(100, 140, 255, 0.4) !important;
            transition: box-shadow 0.2s ease-in-out;
        }

        body.dark-mode .form-check-input:checked {
            background-color: #0d6efd !important;
            /* biru bootstrap */
            border-color: #0d6efd !important;
        }

        /* === FOOTER === */
        body.dark-mode footer.sticky-footer {
            margin: 0 !important;
            background: rgba(34, 42, 66, .5) !important;
            color: #f1f1f1 !important;
            border-top: 1px solid #333 !important;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.6) !important;
            padding: 0.75rem 0;
            /* samakan dengan light mode */
        }

        /* === MODAL === */
        body.dark-mode .modal-content {
            background: linear-gradient(145deg, #1a1a29, #23233a) !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .modal-header {
            border-bottom-color: #444 !important;
        }

        body.dark-mode .modal-footer {
            border-top-color: #444 !important;
        }

        body.dark-mode .btn-close {
            filter: invert(1);
        }

        /* === SELECT2 DARK MODE === */
        body.dark-mode .select2-container .select2-selection--single,
        body.dark-mode .select2-container .select2-selection--multiple {
            background-color: rgba(34, 42, 66, 0.5) !important;
            color: #f1f1f1 !important;
            border: 1px solid #444 !important;
            box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.6),
                inset -2px -2px 5px rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }

        body.dark-mode .select2-container--default .select2-selection--single .select2-selection__rendered,
        body.dark-mode .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            color: #f1f1f1 !important;
        }

        body.dark-mode .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #2a2a44 !important;
            border-color: #555 !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #cfcfcf !important;
        }

        /* Dropdown panel */
        body.dark-mode .select2-dropdown {
            background-color: #1e1e2f !important;
            color: #f1f1f1 !important;
            border: 1px solid #333 !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
        }

        /* Search box in dropdown */
        body.dark-mode .select2-search--dropdown .select2-search__field {
            background: rgba(34, 42, 66, 0.55) !important;
            color: #f1f1f1 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        /* Options */
        body.dark-mode .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #141428 !important;
            color: #fff !important;
        }

        body.dark-mode .select2-results__option--selected {
            background-color: #3c4680 !important;
            color: #fff !important;
        }

        /* Arrow color */
        body.dark-mode .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #f1f1f1 transparent transparent transparent !important;
        }

        body.dark-mode .table .td-mode,
        body.dark-mode table .td-mode,
        body.dark-mode td.td-mode {
            background-color: #1e1e2f !important;
            /* warna gelap elegan */
            color: #f5f5f5 !important;
            /* teks putih lembut */
            border-color: #3a3a4a !important;
            /* garis lebih gelap */
        }

        body.dark-mode .td-mode:hover {
            background-color: rgb(46, 46, 65) !important;
            /* sedikit lebih terang dari dasar */
            color: #ffffff !important;
        }

        /* Garis tabel di mode gelap */
        body.dark-mode .table-bordered th,
        body.dark-mode .table-bordered td {
            border-color: #4b4b5a !important;
        }

        /* Transisi lembut saat ganti mode */
        .table-wrapper,
        .td-mode,
        .table-gradient-header th {
            transition: all 0.3s ease;
        }

        .hide-item {
            display: none;
        }

        /* Sidebar minimal full-viewport height, but can grow with page content */
        .sidebar {
            min-height: 100vh;
            padding-bottom: 1rem;
            /* keep bottom spacing without creating external gap */
            align-self: stretch;
            /* force equal height with content column in flex row */
            height: auto;
        }

        /* Remove external bottom gap coming from last nav-item margin */
        .sidebar .nav-item:last-child {
            margin-bottom: 0 !important;
        }

        /* Make sure flex children stretch to equal height */
        #wrapper {
            align-items: stretch;
            min-height: 100vh;
        }

        /* Ultra compact dropdown styles */
        .dropdown-menu {
            font-size: 0.8rem;
            min-width: 8rem;
            max-width: 12rem;
            padding: 0.1rem 0;
            border-radius: 0.2rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 0.15rem 0.5rem;
            font-size: 0.75rem;
            white-space: normal;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .dropdown-header {
            padding: 0.15rem 0.5rem;
            font-size: 0.65rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            margin: 0.2rem 0 0 0;
            line-height: 1.2;
        }

        .dropdown-divider {
            margin: 0.1rem 0;
        }

        .dropdown-item i {
            width: 0.9rem;
            font-size: 0.7rem;
            text-align: center;
            margin-right: 0.3rem;
        }

        /* Make sure dropdowns don't overflow screen */
        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        .navbar-nav .dropdown-menu-start {
            /* Geser ke kanan (misalnya 10px) */
            left: 20px !important;
            /* Hapus properti right, karena kita menggunakan left */
            right: auto !important;
        }

        .navbar-dark-custom {
            background-color: #ffffff !important;
            /* putih polos */
            color: #000000;
            /* teks dan ikon hitam */
        }

        .user-dropdown-bubble {
            position: relative;
            width: 240px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
            padding: 0;
            border: none;
        }

        /* Segitiga kecil di atas */
        .user-dropdown-bubble::before {
            content: "";
            position: absolute;
            top: -6px;
            right: 32px;
            /* sesuaikan agar pas di bawah avatar */
            width: 12px;
            height: 12px;
            background: white;
            transform: rotate(45deg);
            box-shadow: -3px -3px 6px rgba(0, 0, 0, 0.03);
        }

        /* Header gaya lembut */
        .user-dropdown-bubble .dropdown-header {
            background-color: #fff;
            padding: 10px 15px;
            border-radius: 12px 12px 0 0;
            font-size: 12px;
        }

        .user-dropdown-bubble {
            transform: translateX(-10px);
            /* geser ke kiri 10px */
        }

        /* Item menu */
        .user-dropdown-bubble .dropdown-item {
            transition: background-color 0.2s ease;
            border-radius: 0;
        }

        .user-dropdown-bubble .dropdown-item:hover {
            background-color: #f1f3f5;
        }

        .user-dropdown-bubble .dropdown-divider {
            margin: 0;
        }

        .animated--grow-in {
            transform-origin: top;
            animation: growIn 0.2s ease-in-out forwards;
        }

        /* Custom shadow untuk header */
        .navbar.navbar-dark-custom {
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15) !important;
        }

        /* Custom shadow untuk footer */
        .sticky-footer.bg-white {
            box-shadow: 0 -5px 12px rgba(0, 0, 0, 0.15) !important;
        }

        /* === COLLAPSE (DROPDOWN) === */
        body.dark-mode .sidebar .collapse,
        body.dark-mode .sidebar .collapse .collapse-inner {
            background-color: #1e1e2f !important;
            color: #e0e0e0 !important;
        }

        /* Header & item di dropdown */
        body.dark-mode .sidebar .collapse .collapse-header {
            color: #cfd8dc !important;
        }

        body.dark-mode .sidebar .collapse .collapse-item {
            color: #e0e0e0 !important;
        }

        /* Hover di dalam dropdown */
        body.dark-mode .sidebar .collapse .collapse-item:hover {
            background-color: #2a2a44 !important;
            color: #fff !important;
        }

        /* Animasi growIn (Bootstrap) diperbaiki agar tidak flash putih */
        body.dark-mode .animated--grow-in {
            background-color: #1e1e2f !important;
            animation-fill-mode: both !important;
        }

        @keyframes growIn-dark {
            0% {
                transform: scale(0.95);
                opacity: 0;
                background-color: #1e1e2f;
                /* warna gelap langsung */
            }

            100% {
                transform: scale(1);
                opacity: 1;
                background-color: #1e1e2f;
            }
        }

        body.dark-mode .animated--grow-in {
            animation-name: growIn-dark !important;
            background-color: #1e1e2f !important;
        }

        .role-title {
            font-weight: 700;
            color: #6e7b8b;
            /* biru solid utama */
            text-shadow:
                1px 1px 2px rgba(255, 255, 255, 0.2),
                /* pantulan cahaya lembut */
                2px 2px 4px rgba(0, 0, 0, 0.4);
            /* bayangan ringan */
            letter-spacing: 0.5px;
            transition: text-shadow 0.3s ease, transform 0.3s ease;
        }

        body.dark-mode .role-title {
            color: #cfd8dc !important;
            text-shadow:
                0 1px 2px rgba(0, 0, 0, 0.6);
        }

        .role-content {
            display: inline-flex;
            align-items: center;
            padding-left: 5px;
        }

        .role-content i {
            font-size: 1.3em;
            color: #6e7b8b;
            background: linear-gradient(145deg, #8c9aa3, #5a646d);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            display: inline-block;
            /* penting! */
            text-shadow:
                1px 1px 2px rgba(255, 255, 255, 0.3),
                2px 2px 4px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, text-shadow 0.3s ease;
        }

        body.dark-mode .role-content i {
            color: #b0bec5;
            /* warna logam muda */
            -webkit-text-fill-color: initial;
            /* kembalikan normal */
            background: none;
            /* hilangkan gradasi */
            text-shadow:
                0 1px 2px rgba(0, 0, 0, 0.6);
        }

        /* === Card berwarna tetap di dark mode === */
        body.dark-mode .card.border-left-info,
        body.dark-mode .card.border-left-warning,
        body.dark-mode .card.border-left-success,
        body.dark-mode .card.border-left-primary {
            color: #000000 !important;
            /* teks tetap hitam */
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15) !important;
        }

        /* Pastikan garis kiri tetap sesuai warnanya */
        body.dark-mode .card.border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
            /* biru muda */
        }

        body.dark-mode .card.border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
            /* kuning */
        }

        body.dark-mode .card.border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
            /* hijau */
        }

        body.dark-mode .card.border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
            /* biru */
        }

        .aksi-back .btn-primary {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #5c6bc0, #283593);
            /* gradasi biru tua elegan */
            border: 1px solid #1a237e;
            border-radius: 6px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-back .btn-primary:hover {
            background: linear-gradient(145deg, #7986cb, #303f9f);
            /* lebih terang saat hover */
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .aksi-filter .btn-info {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #64b5f6, #1976d2);
            /* biru muda ke arah langit */
            border: 1px solid #1565c0;
            border-radius: 6px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-filter .btn-info:hover {
            background: linear-gradient(145deg, #90caf9, #1e88e5);
            /* sedikit lebih terang saat hover */
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .aksi-cetak .btn-primary {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #3f51b5, #1a237e);
            /* kiri lebih muda → kanan lebih gelap */
            border: 1px solid #0d1b7a;
            border-radius: 6px;
            box-shadow:
                inset 0 1px 2px rgba(255, 255, 255, 0.25),
                /* kilau lembut di atas */
                2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-cetak .btn-primary:hover {
            background: linear-gradient(145deg, #5c6bc0, #283593);
            /* hover: keduanya sedikit lebih terang */
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow:
                inset 0 1px 2px rgba(255, 255, 255, 0.3),
                3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .aksi-cetak .btn-primary:active {
            transform: translateY(1px);
            box-shadow:
                inset 0 2px 4px rgba(0, 0, 0, 0.4),
                1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .aksi-tambah .btn-success {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #81c784, #4caf50);
            /* hijau agak tua tapi segar */
            border: 1px solid #388e3c;
            border-radius: 6px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-tambah .btn-success:hover {
            background: linear-gradient(145deg, #66bb6a, #43a047);
            /* sedikit lebih terang saat hover */
            color: #f1f1f1;
            /* teks jadi lebih terang saat hover */
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .td-aksi .btn {
            min-width: 35px;
            font-weight: 600;
            color: #fff;
            border-radius: 6px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        /* Tombol "Lihat" - biru lembut */
        .td-aksi .btn-info {
            background: linear-gradient(145deg, #64b5f6, #1976d2);
            border: 1px solid #1565c0;
        }

        .td-aksi .btn-info:hover {
            background: linear-gradient(145deg, #90caf9, #1e88e5);
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        /* Tombol "Edit" - oranye metalik */
        .td-aksi .btn-warning {
            background: linear-gradient(145deg, #ffcc80, #fb8c00);
            border: 1px solid #ef6c00;
            color: #fff;
        }

        .td-aksi .btn-warning:hover {
            background: linear-gradient(145deg, #ffd54f, #f57c00);
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        /* Tombol "Hapus" - merah 3D */
        .td-aksi .btn-danger {
            background: linear-gradient(145deg, #ef9a9a, #d32f2f);
            border: 1px solid #c62828;
        }

        .td-aksi .btn-danger:hover {
            background: linear-gradient(145deg, #e57373, #b71c1c);
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .aksi-hapus .btn-danger {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #ef9a9a, #d32f2f);
            /* hijau agak tua tapi segar */
            border: 1px solid #c62828;
            border-radius: 6px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-hapus .btn-danger:hover {
            background: linear-gradient(145deg, #e57373, #b71c1c);
            /* sedikit lebih terang saat hover */
            color: #f1f1f1;
            /* teks jadi lebih terang saat hover */
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .aksi-galeri .btn-secondary {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #bdbdbd, #757575);
            /* abu muda → abu sedang */
            border: 1px solid #666666;
            border-radius: 6px;
            box-shadow:
                inset 0 1px 2px rgba(255, 255, 255, 0.25),
                /* efek kilau atas */
                2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-galeri .btn-secondary:hover {
            background: linear-gradient(145deg, #e0e0e0, #9e9e9e);
            /* lebih terang saat hover */
            color: #f9f9f9;
            transform: translateY(-2px);
            box-shadow:
                inset 0 1px 2px rgba(255, 255, 255, 0.3),
                3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .aksi-save .btn-success {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #388e3c, #1b5e20);
            /* hijau tua elegan */
            border: 1px solid #1b5e20;
            border-radius: 6px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-save .btn-success:hover {
            background: linear-gradient(145deg, #43a047, #2e7d32);
            /* sedikit lebih terang saat hover */
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .aksi-reset .btn-secondary {
            min-width: 120px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(145deg, #757575, #424242);
            /* abu-abu tua elegan */
            border: 1px solid #333333;
            border-radius: 6px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .aksi-reset .btn-secondary:hover {
            background: linear-gradient(145deg, #9e9e9e, #616161);
            /* sedikit lebih terang saat hover */
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        }

        .btn-pilih-ruang {
            background: linear-gradient(145deg, #28a745, #218838);
            /* gradasi hijau */
            border: none;
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .btn-pilih-ruang:hover {
            background: linear-gradient(145deg, #34d058, #2ebd4d);
            /* warna lebih cerah saat hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.25);
        }

        .btn-pilih-ruang:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-gradient-blue {
            background: linear-gradient(145deg, #007bff, #0056b3);
            border: none;
            color: #fff !important;
            font-weight: 600;
            transition: all 0.25s ease;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.25);
            border-radius: 6px;
        }

        .btn-gradient-blue:hover {
            background: linear-gradient(145deg, #339dff, #0072ff);
            transform: translateX(-4px);
            /* bergeser ke kiri */
            box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.3);
            /* efek timbul */
        }

        .btn-satuan-blue {
            background: linear-gradient(145deg, #007bff, #0056b3);
            border: none;
            color: #fff !important;
            /* teks di tombol biru tetap putih */
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.25);
            border-radius: 6px;
            cursor: pointer;
            appearance: none;
            padding-right: 30px;
        }

        /* Hover efek */
        .btn-satuan-blue:hover {
            background: linear-gradient(145deg, #339dff, #0072ff);
            transform: scale(1.08);
            box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-satuan-blue option {
            color: #212529 !important;
            background: #fff !important;
        }

        /* Dark mode keeps the original gradient + white text */
        body.dark-mode .btn-satuan-blue {
            background: linear-gradient(145deg, #007bff, #0056b3) !important;
            color: #fff !important;
            border: none !important;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.25) !important;
        }

        body.dark-mode .btn-satuan-blue option {
            color: #fff !important;
            background: #1e1e2f !important;
        }

        .btn-gradient-red-tutup {
            background: linear-gradient(145deg, #dc3545, #a71d2a);
            /* gradasi merah */
            border: none;
            color: #fff !important;
            font-weight: 600;
            transition: all 0.25s ease;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.25);
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-gradient-red-tutup:hover {
            background: linear-gradient(145deg, #ff4d5a, #d12f3e);
            /* lebih terang saat hover */
            transform: translateX(-4px);
            /* efek bergeser ke kiri */
            box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.3);
            /* efek timbul */
        }

        .btn-gradient-red-tutup:active {
            transform: translateX(-2px) scale(0.98);
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .container-fluid {
            background-color: rgb(220, 220, 220);
        }

        #content-wrapper {
            background-color: rgb(220, 220, 220) !important;
        }

        /* Titles and text utilities in dark mode */
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode .text-dark {
            color: #f1f1f1 !important;
        }

        /* Mobile topbar - center logo and brand */
        @media (max-width: 767.98px) {

            /* Mobile breadcrumb and title spacing */
            .container-fluid {
                padding-top: 0 !important;
            }

            #auto-breadcrumbs {
                text-align: center;
                margin-top: -1.75rem !important;
                /* sedikit jarak, biar tidak nempel */
                padding-top: -0.25rem !important;
            }

            /* Title spacing below breadcrumb on mobile */
            .container-fluid h1,
            .container-fluid h2,
            .container-fluid h3,
            .container-fluid h4,
            .container-fluid h5 {
                margin-top: 0.5rem !important;
                margin-bottom: 1rem !important;
            }

            /* Adjust wrapper spacing for mobile */
            .d-flex.flex-column.flex-md-row {
                padding-top: 0.5rem !important;
                gap: 0.5rem !important;
            }

            /* Hide user dropdown text on mobile to make room for centered brand */
            .navbar-nav .nav-item .nav-link span {
                display: none !important;
            }

            /* Ensure proper spacing for mobile brand */
            .navbar {
                justify-content: space-between;
            }

            .navbar-dark-custom {
                background: linear-gradient(180deg, #5C6BC0 0%, #283593 100%) !important;
                color: rgb(16, 0, 79);
            }

            /* Tighten right edge spacing for user icon on mobile */
            .topbar.navbar {
                padding-right: 0 !important;
            }

            .navbar-nav.ms-auto {
                margin-right: 1rem !important;
                padding-right: 0 !important;
            }

            #userDropdown.nav-link {
                padding-right: 0 !important;
            }

            /* Move angle-down icon to the left of avatar and make it white on mobile */
            #userDropdown {
                display: flex;
                align-items: center;
                gap: 0;
            }

            #userDropdown .fa-angle-down {
                order: -1;
                color: #fff !important;
                margin-right: .25rem !important;
                margin-left: 0 !important;
            }

            #userDropdown .img-profile {
                order: 0;
            }

            .mobile-dropdown-bubble {
                position: relative;
                width: 220px;
                border-radius: 12px;
                background-color: #fff;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                margin-top: 12px;
                padding: 0;
                border: none;
            }

            /* Segitiga kecil di atas dropdown */
            .mobile-dropdown-bubble::before {
                content: "";
                position: absolute;
                top: -6px;
                left: 10px;
                /* posisi segitiga di bawah tombol menu */
                width: 12px;
                height: 12px;
                background: white;
                transform: rotate(45deg);
                box-shadow: -3px -3px 6px rgba(0, 0, 0, 0.03);
            }

            /* Tampilan item menu */
            .mobile-dropdown-bubble .dropdown-item {
                padding: 10px 16px;
                transition: background-color 0.2s ease;
            }

            .mobile-dropdown-bubble .dropdown-item:hover {
                background-color: #f8f9fa;
            }

            .mobile-dropdown-bubble .dropdown-header {
                background-color: #f8f9fa;
                font-weight: 600;
                padding: 10px 16px;
                border-radius: 12px 12px 0 0;
                font-size: 15px;
            }

            .mobile-dropdown-bubble .dropdown-divider {
                margin: 0;
            }

            .animated--grow-in {
                transform-origin: top;
                animation: growIn 0.2s ease-in-out forwards;
            }

            @keyframes growIn {
                0% {
                    opacity: 0;
                    transform: scale(0.95);
                }

                100% {
                    opacity: 1;
                    transform: scale(1);
                }
            }
        }

        @media (max-width: 576px) {
            .user-dropdown-bubble {
                width: 120px !important;
                min-width: unset !important;
                right: 0 !important;
                left: auto !important;
                margin-top: 30px;
            }

            .user-dropdown-bubble::before {
                right: 40px;
            }
        }

        /* Dark mode icon sizing and responsive nudges */
        #darkModeContainer #darkModeIcon {
            font-size: 1.5rem;
            line-height: 1;
            margin-right: .5rem;
        }

        .navbar.navbar-dark-custom.topbar {
            position: relative;
        }

        @media (max-width: 767.98px) {
            #darkModeContainer {
                position: absolute !important;
                right: 73px;
                top: 50%;
                transform: translateY(-50%);
                margin-left: 0 !important;
                margin-right: 0 !important;
                z-index: 2;
                background: transparent;
                padding: 0;
            }

            #darkModeContainer #darkModeIcon {
                font-size: 1.2rem;
                margin-left: 0;
                margin-right: 0;
            }
        }

        body.dark-mode .user-dropdown-bubble {
            background: #24243b !important;
            color: #f1f1f1 !important;
            border: 1px solid #353561 !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.55) !important;
        }

        body.dark-mode .user-dropdown-bubble::before {
            content: "";
            position: absolute;
            top: -6px;
            right: 32px;
            /* sesuaikan agar pas di bawah avatar */
            width: 12px;
            height: 12px;
            background: #24243b !important;
            transform: rotate(45deg);
            box-shadow: -3px -3px 6px rgba(0, 0, 0, 0.03);
        }

        body.dark-mode .user-dropdown-bubble .dropdown-header {
            background: transparent !important;
            color: #90caf9 !important;
            border-bottom: 1px solid #353561 !important;
        }

        body.dark-mode .user-dropdown-bubble .dropdown-item {
            background: transparent !important;
            color: #f1f1f1 !important;
            border-radius: 8px;
            transition: background .2s;
        }

        body.dark-mode .user-dropdown-bubble .dropdown-item:hover {
            background-color: #30304a !important;
            color: #90caf9 !important;
        }

        body.dark-mode .user-dropdown-bubble .dropdown-divider {
            border-color: #383858 !important;
            background: #383858 !important;
        }

        body.dark-mode .user-dropdown-bubble i {
            color: #6495ed !important;
        }

        body.dark-mode .mobile-dropdown-bubble {
            background: #24243b !important;
            color: #f1f1f1 !important;
            border: 1px solid #353561 !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.55) !important;
        }

        body.dark-mode .mobile-dropdown-bubble::before {
            content: "";
            position: absolute;
            top: -6px;
            left: 10px;
            width: 12px;
            height: 12px;
            background: #24243b !important;
            transform: rotate(45deg);
            box-shadow: -3px -3px 6px rgba(220, 32, 32, 0.03);
        }

        body.dark-mode .mobile-dropdown-bubble .dropdown-header {
            background: transparent !important;
            color: #90caf9 !important;
            border-bottom: 1px solid #353561 !important;
        }

        body.dark-mode .mobile-dropdown-bubble .dropdown-item {
            background: transparent !important;
            color: #f1f1f1 !important;
            border-radius: 8px;
            transition: background .2s;
        }

        body.dark-mode .mobile-dropdown-bubble .dropdown-item:hover {
            background-color: #30304a !important;
            color: #90caf9 !important;
        }

        body.dark-mode .mobile-dropdown-bubble .dropdown-divider {
            border-color: #383858 !important;
            background: #383858 !important;
        }

        body.dark-mode .mobile-dropdown-bubble i {
            color: #6495ed !important;
        }

        body.dark-mode .card .stats-highlight {
            color: #fff !important;
            font-weight: bold;
        }

        /* === FLATPICKR DARK MODE STYLING === */
        body.dark-mode .flatpickr-calendar {
            background-color: rgb(30, 30, 51) !important;
            color: #f1f1f1 !important;
            border: 1px solid #444 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            border-radius: 6px;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Header bulan dan tahun */
        body.dark-mode .flatpickr-months {
            background-color: rgb(30, 30, 51) !important;
            border-bottom: 1px solid #444 !important;
        }

        body.dark-mode .flatpickr-current-month {
            color: #ffffff !important;
        }

        /* Panah navigasi */
        body.dark-mode .flatpickr-prev-month,
        body.dark-mode .flatpickr-next-month {
            color: #ffffff !important;
            fill: #ffffff !important;
        }

        /* Hari-hari di kalender */
        body.dark-mode .flatpickr-day {
            background: transparent !important;
            color: #f1f1f1 !important;
            border-radius: 4px;
        }

        /* Hari yang dipilih */
        body.dark-mode .flatpickr-day.selected,
        body.dark-mode .flatpickr-day.startRange,
        body.dark-mode .flatpickr-day.endRange {
            background: #3b82f6 !important;
            /* biru muda */
            color: #fff !important;
            border: none !important;
        }

        /* Hover hari */
        body.dark-mode .flatpickr-day:hover {
            background: #3a3a4a !important;
        }

        /* Tombol Clear dan Today */
        body.dark-mode .flatpickr-calendar .flatpickr-clear,
        body.dark-mode .flatpickr-calendar .flatpickr-today {
            color: #3b82f6 !important;
        }

        /* Garis di antara tombol bawah */
        body.dark-mode .flatpickr-calendar .flatpickr-footer {
            border-top: 1px solid #444 !important;
        }

        body.dark-mode input[type="date"] {
            color-scheme: dark;
            background-color: #23233a;
            color: #f8f8f2;
            border: 1px solid #444;
            border-radius: 4px;
            padding: 4px 8px;
        }
    </style>

    @stack('styles')
</head>

<body id="page-top" class="{{ localStorageDarkMode() ? 'dark-mode' : '' }}">

    <div id="wrapper">

        <!-- Sidebar (hidden on small screens) -->
        @include('layout.sidebar')
        <!-- End Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column min-vh-100">

            <div id="content" class="flex-grow-1">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-dark navbar-dark-custom topbar mb-4 static-top shadow">
                    <!-- Mobile sidebar toggle removed; using dropdown menu instead -->

                    <!-- Mobile Nav Dropdown (visible only on small screens) -->
                    <ul class="navbar-nav d-md-none">
                        <li class="nav-item dropdown">
                            <button class="btn btn-link d-md-none rounded-circle me-3" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bars" style="color: #fff;"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-start mobile-dropdown-bubble shadow" aria-labelledby="mobileMenuDropdown" id="mobileDropdownMenu">

                                @can('dashboard-view')
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-fw fa-inbox me-2"></i> Dashboard
                                </a>
                                @endcan

                                @canany(['user-list','role-list'])
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Setting User</h6>
                                @can('role-list')
                                <a class="dropdown-item" href="{{ url('roles') }}">
                                    <i class="fas fa-user-tag me-2"></i> Role
                                </a>
                                @endcan
                                @can('user-list')
                                <a class="dropdown-item" href="{{ url('users') }}">
                                    <i class="fas fa-users me-2"></i> User
                                </a>
                                @endcan
                                @endcanany

                                @canany(['ruang-list','kategori-list','aset-list','scanqr-view'])
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Master Data</h6>
                                @can('ruang-list')
                                <a class="dropdown-item" href="{{ url('ruang') }}">
                                    <i class="fas fa-landmark me-2"></i> List Ruangan
                                </a>
                                @endcan
                                @can('kategori-list')
                                <a class="dropdown-item" href="{{ url('kategori') }}">
                                    <i class="fas fa-box me-2"></i> Jenis Barang
                                </a>
                                @endcan
                                @can('aset-list')
                                <a class="dropdown-item" href="{{ url('aset') }}">
                                    <i class="fas fa-th-list me-2"></i> Daftar Aset
                                </a>
                                @endcan
                                @endcanany

                                @canany(['histori-list','rekap-view','opruang-view'])
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Laporan</h6>
                                @can('histori-list')
                                <a class="dropdown-item" href="{{ url('histori') }}">
                                    <i class="fas fa-book me-2"></i> Histori
                                </a>
                                @endcan
                                @can('rekap-view')
                                <a class="dropdown-item" href="{{ url('rekap') }}">
                                    <i class="fas fa-window-restore me-2"></i> Rekapitulasi
                                </a>
                                @endcan
                                @can('opruang-view')
                                <a class="dropdown-item" href="{{ url('opruang') }}">
                                    <i class="fas fa-window-restore me-2"></i> Aset per-Ruang
                                </a>
                                @endcan
                                @endcanany

                                <div class="dropdown-divider"></div>
                                @can('scanqr-view')
                                <a class="dropdown-item" href="{{ url('scanqr') }}">
                                    <i class="fas fa-qrcode me-2"></i> Scan QR Code
                                </a>
                                @endcan
                                @can('opnamhistori-view')
                                <a class="dropdown-item" href="{{ url('opnamhistori') }}">
                                    <i class="fas fa-sync-alt me-2"></i> Pindah Ruang
                                </a>
                                @endcan
                                @can('brglelang-view')
                                <a class="dropdown-item" href="{{ url('brglelang') }}">
                                    <i class="fas fa-balance-scale me-2"></i> Barang Lelang
                                </a>
                                @endcan
                            </div>
                        </li>
                    </ul>

                    <!-- Mobile Brand/Logo (centered on mobile) -->
                    <div class="d-md-none d-flex align-items-center justify-content-center flex-grow-1">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <img src="{{ asset('assets/img/Logo.png') }}" width="30" class="me-2" />
                            <div class="text-center">
                                <div class="fw-bold text-white" style="font-size: 0.8rem; line-height: 1;">INVENTARIS</div>
                                <div class="fw-bold text-white" style="font-size: 0.8rem; line-height: 1;">PERUMDA</div>
                            </div>
                        </a>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item ms-auto d-flex align-items-center" id="darkModeContainer">
                            <i id="darkModeIcon" class="fas fa-moon" role="button" style="cursor: pointer;"></i>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle pe-0 pe-lg-3" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class=" me-0 me-lg-2 img-profile rounded-circle" src="{{ asset('assets/img/undraw_profile.svg') }}">
                                <span class=" me-1 d-none d-lg-inline text-gray-600 small">
                                    <strong>Hi, {{ Auth::user()->name }}</strong>
                                </span>
                                <i class="fas fa-angle-down fa-sm fa-fw me-0 me-lg-4 text-dark"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow user-dropdown-bubble"
                                aria-labelledby="userDropdown">
                                <!-- Header -->
                                <div class="dropdown-header text-center fw-semibold text-primary border-bottom">
                                    <strong>{{ Auth::user()->name }}</strong>
                                </div>
                                <!-- Body -->
                                <a class="dropdown-item py-2" href="#">
                                    <i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i> Activity Log
                                </a>
                                <div class="dropdown-divider my-1"></div>
                                <a class="dropdown-item py-2" href="#" onclick="confirmLogout(event)">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End Topbar -->

                <!-- Page Content -->
                <div class="container-fluid p-0 mb-5">
                    @include('layout.breadcrumbs')
                    @yield('content')
                </div>

            </div>
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto text-center">
                    <span>Copyright &copy; Inventaris Perumda 2025</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scroll to Top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal placeholder -->
    <div class="modal fade" id="logoutModal" tabindex="-1" style="display:none !important"></div>

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Core plugin -->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Bootstrap 5 jQuery collapse bridge (compat for SB Admin 2) -->
    <script>
        (function($) {
            if ($ && !$.fn.collapse && window.bootstrap && window.bootstrap.Collapse) {
                $.fn.collapse = function(action) {
                    return this.each(function() {
                        var instance = bootstrap.Collapse.getInstance(this);
                        if (!instance) instance = new bootstrap.Collapse(this, {
                            toggle: false
                        });
                        if (action === 'show') instance.show();
                        else if (action === 'hide') instance.hide();
                        else if (action === 'toggle') instance.toggle();
                    });
                };
            }
        })(window.jQuery);
    </script>
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')

    <!-- Custom JS -->
    <script>
        function confirmDelete(formId) {
            const isDarkMode = document.body.classList.contains('dark-mode');

            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan!",
                reverseButtons: true,

                // === Tema berdasarkan mode ===
                background: isDarkMode ? "#1e1e2f" : "#fff",
                color: isDarkMode ? "#f1f1f1" : "#000",
                iconColor: isDarkMode ? "#f8d44c" : "#f8bb86",
                confirmButtonColor: isDarkMode ? "#dc3545" : "#d33",
                cancelButtonColor: isDarkMode ? "#6c757d" : "#aaa",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Dibatalkan",
                        text: "Data tidak jadi dihapus.",
                        icon: "error",
                        background: isDarkMode ? "#1e1e2f" : "#fff",
                        color: isDarkMode ? "#f1f1f1" : "#000",
                        confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6"
                    });
                }
            });
        }

        function confirmLogout(e) {
            e.preventDefault();

            // Cek apakah body sedang dark mode
            const isDarkMode = document.body.classList.contains('dark-mode');

            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Ingin keluar dari halaman ini?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, keluar!",
                cancelButtonText: "Tidak, batalkan!",
                reverseButtons: true,

                // === Tema berdasarkan mode ===
                background: isDarkMode ? "#1e1e2f" : "#fff",
                color: isDarkMode ? "#f1f1f1" : "#000",
                iconColor: isDarkMode ? "#f8d44c" : "#3085d6",
                confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6",
                cancelButtonColor: isDarkMode ? "#6c757d" : "#aaa",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('logout') }}";
                    form.style.display = 'none';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);

                    document.body.appendChild(form);
                    form.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Dibatalkan",
                        text: "Anda tidak jadi keluar.",
                        icon: "error",
                        background: isDarkMode ? "#1e1e2f" : "#fff",
                        color: isDarkMode ? "#f1f1f1" : "#000",
                        confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6"
                    });
                }
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var bc = document.getElementById('auto-breadcrumbs');
            if (!bc) return;
            var content = document.getElementById('content');
            if (!content) return;
            var heading = content.querySelector('h1, h2, h3, h4, h5');
            if (!heading) return;

            var wrapper = document.createElement('div');
            wrapper.className = 'd-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-md-between gap-2 px-3 px-md-4 pt-2';

            // Sisipkan wrapper sebelum heading, lalu pindahkan heading dan breadcrumbs ke dalamnya
            heading.parentNode.insertBefore(wrapper, heading);
            wrapper.appendChild(heading);

            // Hilangkan padding default pada breadcrumb container agar rapih di baris judul
            bc.classList.remove('px-3', 'px-md-4', 'pt-2');
            bc.style.padding = '0';

            wrapper.appendChild(bc);
        });
    </script>
    <!-- Dark/Light Mode Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const icon = document.getElementById("darkModeIcon");
            const body = document.body;

            // Cek mode tersimpan dari localStorage atau cookie
            const cookieTheme = (document.cookie.match(/(?:^|; )theme=([^;]+)/) || [])[1];
            const savedMode = localStorage.getItem("theme") || cookieTheme;
            applyDarkMode(savedMode === "dark");

            // Toggle saat icon diklik
            icon.addEventListener("click", function() {
                const isDark = !body.classList.contains("dark-mode");
                applyDarkMode(isDark);
                localStorage.setItem("theme", isDark ? "dark" : "light");
                document.cookie = "theme=" + (isDark ? "dark" : "light") + "; path=/; max-age=31536000";
            });

            function applyDarkMode(isDark) {
                body.classList.toggle("dark-mode", isDark);
                updateIcon(isDark);
            }

            function updateIcon(isDark) {
                if (isDark) {
                    if (icon.classList.contains("fa-moon")) icon.classList.replace("fa-moon", "fa-sun");
                    icon.style.color = "#FFD700"; // warna kuning
                } else {
                    if (icon.classList.contains("fa-sun")) icon.classList.replace("fa-sun", "fa-moon");
                    icon.style.color = "#A9a9a9"; // warna normal
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggledClass = 'sidebar-toggled';
            const sidebarCollapsedKey = 'sidebar-collapsed';

            // Fungsi untuk menyimpan status sidebar
            function saveSidebarState() {
                const isCollapsed = body.classList.contains(sidebarToggledClass);
                localStorage.setItem(sidebarCollapsedKey, isCollapsed ? 'true' : 'false');
            }

            // Fungsi untuk memuat status terakhir
            function loadSidebarState() {
                const saved = localStorage.getItem(sidebarCollapsedKey);
                if (saved === 'true') {
                    body.classList.add(sidebarToggledClass);
                    if (sidebar) sidebar.classList.add('toggled');
                } else {
                    body.classList.remove(sidebarToggledClass);
                    if (sidebar) sidebar.classList.remove('toggled');
                }
            }

            // Jalankan saat load
            loadSidebarState();

            // Event listener untuk tombol toggle
            $(document).on('click', '#sidebarToggle, #sidebarToggleTop', function(e) {
                setTimeout(saveSidebarState, 300); // beri waktu animasi berjalan dulu
            });

            // Auto collapse jika layar kecil
            function handleResize() {
                if (window.innerWidth < 768) {
                    body.classList.add(sidebarToggledClass);
                    if (sidebar) sidebar.classList.add('toggled');
                    localStorage.setItem(sidebarCollapsedKey, 'true');
                }
            }

            handleResize();
            window.addEventListener('resize', handleResize);
        });
    </script>
    <script>
        // === Toggle Show/Hide Password dengan animasi berkedip ===
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        function toggleVisibility(inputId, iconEl) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            iconEl.classList.toggle('fa-eye');
            iconEl.classList.toggle('fa-eye-slash');

            // tambahkan animasi berkedip singkat
            iconEl.classList.add('blink');
            setTimeout(() => iconEl.classList.remove('blink'), 400);
        }

        togglePassword.addEventListener('click', function() {
            toggleVisibility('password', this);
        });

        toggleConfirmPassword.addEventListener('click', function() {
            toggleVisibility('confirmPassword', this);
        });
    </script>
</body>

</html>