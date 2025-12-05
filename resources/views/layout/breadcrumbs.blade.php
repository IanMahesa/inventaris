<div id="auto-breadcrumbs" class="px-3 px-md-4 pt-2">
    <nav aria-label="breadcrumb">
        @php
        $maps = [
        'dashboard' => 'Dashboard',
        'aset' => 'Aset',
        'kategori' => 'Kategori',
        'ruang' => 'Ruangan',
        'users' => 'User',
        'roles' => 'Role',
        'scanqr' => 'Scan QR Code',
        'histori' => 'Histori',
        'rekap' => 'Rekapitulasi',
        'opruang' => 'Aset per-Ruang',
        'opnamhistori' => 'Pindah Ruang',
        'brglelang' => 'Barang Lelang',
        'create' => 'Tambah',
        'edit' => 'Edit',
        'show' => 'Detail',
        'print' => 'Cetak',
        'printjenis' => 'Cetak Per Jenis'
        ];
        $segments = request()->segments();
        $url = url('/');
        $crumbs = [];
        foreach ($segments as $seg) {
        if ($seg === 'dashboard') continue;
        if (is_numeric($seg)) continue;
        $label = $maps[$seg] ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $seg));
        $url .= '/' . $seg;
        $crumbs[] = ['label' => $label, 'url' => $url];
        }
        // Tambahkan 'Detail' untuk route resource show (biasanya /resource/{id})
        $route = request()->route();
        $routeName = $route ? $route->getName() : null;
        $actionMethod = $route && method_exists($route, 'getActionMethod') ? $route->getActionMethod() : null;
        if (($routeName && \Illuminate\Support\Str::endsWith($routeName, '.show')) || ($actionMethod === 'show')) {
        $crumbs[] = ['label' => ($maps['show'] ?? 'Detail'), 'url' => null];
        }
        @endphp
        <ol class="breadcrumb small mb-0" style="background: transparent; box-shadow: none; border: none;">
            @if (empty($crumbs))
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-home me-1"></i> Dashboard
            </li>
            @else
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home me-1"></i> Dashboard</a>
            </li>
            @foreach ($crumbs as $i => $c)
            @if ($i === count($crumbs) - 1)
            <li class="breadcrumb-item active" aria-current="page">{{ $c['label'] }}</li>
            @else
            <li class="breadcrumb-item"><a href="{{ $c['url'] }}">{{ $c['label'] }}</a></li>
            @endif
            @endforeach
            @endif
        </ol>
    </nav>
</div>

@push('styles')
<style>
    #auto-breadcrumbs .breadcrumb {
        background: transparent !important;
        /* hilangkan latar abu-abu */
        box-shadow: none !important;
        /* hilangkan efek bayangan */
        border: none !important;
        /* hilangkan border */
        margin-bottom: 0 !important;
        padding: 0.3rem 0 !important;
        font-size: 0.95rem;
        /* sedikit lebih besar dari small */
        font-weight: 500;
        /* agak tebal biar jelas */
    }

    #auto-breadcrumbs .breadcrumb-item a {
        color: #0d6efd;
        /* warna link biru Bootstrap */
        text-decoration: none;
    }

    #auto-breadcrumbs .breadcrumb-item a:hover {
        text-decoration: underline;
        /* efek hover */
    }

    #auto-breadcrumbs .breadcrumb-item.active {
        color: #343a40;
        /* warna teks item aktif (abu gelap) */
        font-weight: 600;
    }
</style>