<style>
    /* Custom Sidebar Gradient */
    .custom-sidebar-gradient {
        background: linear-gradient(180deg, #1E3C72 0%, #2A5298 50%, #2ECC71 100%) !important;
        background-size: cover !important;
    }

    /* Custom sidebar text colors for better contrast */
    .custom-sidebar-gradient .sidebar-brand {
        color: #fff !important;
    }

    .custom-sidebar-gradient .nav-item .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .custom-sidebar-gradient .nav-item .nav-link i {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .custom-sidebar-gradient .nav-item .nav-link:hover {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    .custom-sidebar-gradient .nav-item .nav-link:hover i {
        color: #fff !important;
    }

    .custom-sidebar-gradient .nav-item.active .nav-link {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.15) !important;
    }

    .custom-sidebar-gradient .nav-item.active .nav-link i {
        color: #fff !important;
    }

    .custom-sidebar-gradient hr.sidebar-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .custom-sidebar-gradient .sidebar-heading {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .custom-sidebar-gradient #sidebarToggle {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }

    .custom-sidebar-gradient #sidebarToggle:hover {
        background-color: rgba(255, 255, 255, 0.3) !important;
    }

    .custom-sidebar-gradient #sidebarToggle::after {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* Arrow caret for sidebar collapses */
    .nav-item>.nav-link .collapse-caret {
        transition: transform 0.2s ease;
        margin-left: auto;
    }

    .nav-item>.nav-link.collapsed .collapse-caret {
        transform: rotate(0deg);
    }

    .nav-item>.nav-link:not(.collapsed) .collapse-caret {
        transform: rotate(180deg);
    }

    /* Default horizontal spacing between first icon and label */
    .sidebar .nav-item .nav-link>i:first-child {
        margin-right: 0.5rem;
    }

    /* Collapsed sidebar layout - icon above, title below */
    /* *** PERUBAHAN UTAMA: Hapus .collapsed untuk menargetkan SEMUA tautan *** */
    .sidebar.toggled .nav-item .nav-link {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
    }

    /* *** PERUBAHAN: Hapus .collapsed dari selektor ini *** */
    .sidebar.toggled .nav-item .nav-link i:first-child {
        margin-bottom: 0.5rem !important;
        /* add more space below icon in vertical layout */
        margin-right: 0 !important;
    }

    /* *** PERUBAHAN: Hapus .collapsed dari selektor ini *** */
    .sidebar.toggled .nav-item .nav-link span {
        font-size: 0.75rem !important;
        margin-bottom: 0.25rem !important;
    }

    /* *** PERUBAHAN: Hapus .collapsed dari selektor ini *** */
    .sidebar.toggled .nav-item .nav-link .collapse-caret {
        margin-top: 0.25rem !important;
        margin-left: 0 !important;
    }

    /* Sidebar brand when collapsed */
    .sidebar.toggled .sidebar-brand {
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        text-align: center !important;
    }

    .sidebar.toggled .sidebar-brand .sidebar-brand-text {
        font-size: 0.7rem !important;
        margin-top: 0.25rem !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        line-height: 1.1 !important;
        display: block !important;
    }

    .sidebar.toggled .sidebar-brand .ms-3 {
        margin-left: 0 !important;
        margin-right: 0 !important;
        margin-bottom: 0 !important;
    }

    /* Keep default brand height; expand and center nicely when collapsed */
    .sidebar .sidebar-brand {
        height: 4.375rem;
    }

    .sidebar.toggled .sidebar-brand {
        height: auto !important;
        padding: 0.75rem 0 !important;
        gap: 0.25rem !important;
    }

    .sidebar.toggled .sidebar-brand img {
        display: block !important;
        margin: 0 auto 0.25rem !important;
    }

    .sidebar .nav-item .nav-link .collapse-caret {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding-top: 2px;
        /* opsional, untuk fine-tune */
    }


    /* Mobile: center brand/logo and remove side margins */
    @media (max-width: 576.98px) {
        .sidebar .sidebar-brand {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            width: 100% !important;
            padding: 0.75rem 0 !important;
            gap: 0.25rem !important;
        }

        .sidebar .sidebar-brand .ms-3,
        .sidebar .sidebar-brand .sidebar-brand-text {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .sidebar .sidebar-brand img {
            display: block !important;
            margin: 0 auto 0.25rem !important;
        }
    }
</style>

<ul class="navbar-nav custom-sidebar-gradient sidebar accordion d-none d-md-block" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="ms-3">
            <img src="{{ asset('assets/img/Logo.png') }}" width="40" />
        </div>
        <div class="sidebar-brand-text mx-3">INVENTARIS PERUMDA</div>
    </a>


    <hr class="sidebar-divider my-0">

    @can('dashboard-view')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-inbox" style=" margin-right: 8px;"></i>
            <span>Dashboard</span></a>
    </li>
    @endcan

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master Data
    </div>
    @canany(['user-list', 'role-list'])
    <li class="nav-item">
        <a class="nav-link collapsed d-flex align-items-center" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
            aria-expanded="false" aria-controls="collapsePages">
            <i class="fas fa-fw fa-user-circle"></i>
            <span>Setting User</span>
            <i class="fas fa-chevron-down collapse-caret ms-auto"></i>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Setting User</h6>
                @can('role-list')
                <a class="collapse-item" href="{{ url('roles') }}"><i class="fas fa-user-tag" style="margin-right: 8px;"></i>Role</a>
                @endcan
                @can('user-list')
                <a class="collapse-item" href="{{ url('users') }}"><i class="fas fa-users" style="margin-right: 8px;"></i>User</a>
                @endcan
            </div>
        </div>
    </li>
    @endcanany

    @canany(['ruang-list', 'kategori-list', 'aset-list', 'scanqr-view'])
    <li class="nav-item">
        <a class="nav-link collapsed d-flex align-items-center" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
            aria-expanded="false" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-server"></i>
            <span>Master Data</span>
            <i class="fas fa-chevron-down collapse-caret ms-auto"></i>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Master Data</h6>
                @can('ruang-list')
                <a class="collapse-item" href="{{ url('ruang') }}"><i class="fas fa-landmark" style="margin-right: 8px;"></i>List Ruangan</a>
                @endcan
                @can('kategori-list')
                <a class="collapse-item" href="{{ url('kategori') }}"><i class="fas fa-box" style="margin-right: 8px;"></i>Jenis Barang</a>
                @endcan
                @can('aset-list')
                <a class="collapse-item" href="{{ url('aset') }}"><i class="fas fa-th-list me-2" style="margin-right: 8px;"></i>Daftar Aset</a>
                @endcan
            </div>
        </div>
    </li>
    @endcanany

    @canany(['histori-list', 'rekap-view', 'opruang-view'])
    <li class="nav-item">
        <a class="nav-link collapsed d-flex align-items-center" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUtilities"
            aria-expanded="false" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-book-open fa-xs"></i>
            <span>Laporan</span>
            <i class="fas fa-chevron-down collapse-caret ms-auto"></i>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Laporan</h6>
                @can('histori-list')
                <a class="collapse-item" href="{{ url('histori') }}"><i class="fas fa-book" style="margin-right: 8px;"></i>Histori</a>
                @endcan
                @can('rekap-view')
                <a class="collapse-item" href="{{ url('rekap') }}"><i class="fas fa-window-restore" style="margin-right: 8px;"></i>Rekapitulasi</a>
                @endcan
                @can('opruang-view')
                <a class="collapse-item" href="{{ url('opruang') }}"><i class="fas fa-window-restore" style="margin-right: 8px;"></i>Aset per-Ruang</a>
                @endcan
            </div>
        </div>
    </li>
    @endcanany


    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Tools
    </div>

    @can('scanqr-view')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('scanqr') }}">
            <i class="fas fa-qrcode" style=" margin-right: 8px;"></i>
            <span>Scan QR Code</span></a>
    </li>
    @endcan
    @can('opnamhistori-view')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('opnamhistori') }}">
            <i class="fas fa-sync-alt" style="margin-right: 6px;"></i>
            <span>Pindah Ruang</span></a>
    </li>
    @endcan
    @can('brglelang-view')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('brglelang') }}">
            <i class="fas fa-balance-scale" style="margin-right: 6px;"></i>
            <span>Barang Lelang</span></a>
    </li>
    @endcan

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-block">
        <button class="rounded-circle border-0 d-block mx-auto"
            id="sidebarToggle"
            aria-label="Toggle sidebar">
        </button>

    </div>
</ul>