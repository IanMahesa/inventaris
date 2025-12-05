@extends('layout.backend')

@section('content')
<section class="p-2 rounded mb-3 ms-md-5"
    style="background-color: transparent; margin-top: -10px;">
    <h1 class="fw-semibold text-center text-md-start welcome-title text-3d">
        Selamat Datang, {{ Auth::user()->name }}
    </h1>
</section>

<div class="container-fluid">
    <div class="row">

        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card border-left-info h-100 p-3" style="box-shadow: 0 8px 12px rgba(0,0,0,0.15);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jumlah Ruangan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 stats-highlight">
                                {{ $jumlahRuangan }}
                            </div>
                        </div>
                        <i class="fas fa-door-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card border-left-warning h-100 p-3" style="box-shadow: 0 8px 12px rgba(0,0,0,0.15);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Jumlah Barang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 stats-highlight">
                                {{ $totalBarang }}
                            </div>
                        </div>
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card border-left-success h-100 p-3" style="box-shadow: 0 8px 12px rgba(0,0,0,0.15);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Harga Barang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 stats-highlight">
                                Rp. {{ number_format($totalHarga, 0, ',', '.') }},00
                            </div>
                        </div>
                        <i class="fas fa-wallet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card border-left-primary h-100 p-3" style="box-shadow: 0 8px 12px rgba(0,0,0,0.15);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jumlah User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 stats-highlight">
                                {{ $jumlahUser }}
                            </div>
                        </div>
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tambahan Chart Pie --}}
    <div class="row mt-2 align-items-stretch">
        <div class="col-12 col-lg-8 d-flex">
            <div class="card p-3 w-100" style="box-shadow: 0 8px 12px rgba(0,0,0,0.15);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">Distribusi Jumlah Barang per Ruang</h6>
                    <button class="btn btn-outline-primary btn-sm" onclick="openChartModal()">
                        <i class="fas fa-expand"></i> Perbesar
                    </button>
                </div>
                <div class="chart-container" onclick="openChartModal()">
                    <canvas id="asetPieChart"
                        data-labels="{{ json_encode($labels ?? []) }}"
                        data-values="{{ json_encode($data ?? []) }}"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 d-flex">
            <div class="card p-3 w-100" style="box-shadow: 0 8px 12px rgba(0,0,0,0.15);">
                <h6 class="mb-2 fw-semibold text-center">Legenda Chart Jumlah Barang</h6>
                <hr class="my-2" style="border-top: 2px solid #000;">
                <div id="customLegend" class="h-100 mt-2" style="max-height: 45vh; overflow-y: auto; padding-right: 6px;">
                    <!-- Legenda akan diisi oleh JavaScript -->
                </div>
                <hr class="my-2" style="border-top: 2px solid #000;">
                <h6 class="mb-2">Informasi Chart</h6>
                <p class="text-muted small mb-1">Menampilkan distribusi jumlah barang berdasarkan ruangan:</p>
                <ul class="small mb-0 ps-3">
                    <li><strong>Kode Ruang</strong> - Nama Ruang</li>
                    <li><strong>Nilai Aset</strong> dalam kurung</li>
                    <li><strong>Data</strong> berdasarkan jumlah barang</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Modal untuk Chart Popup --}}
    <div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartModalLabel">
                        <i class="fas fa-chart-pie"></i> Distribusi Jumlah Barang per Ruang
                    </h5>
                    <button type="button" class="close" onclick="closeChartModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div style="width: 100%; height: 70vh;">
                                <canvas id="asetPieChartModal"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <h6 class="mb-2 text-center">LEGENDA CHART JUMLAH BARANG PER RUANGAN</h6>
                            <hr class="my-2" style="border-top: 3px solid #000;">
                            <div id="customLegendModal" class="h-100 mt-3" style="max-height: calc(70vh - 5rem); overflow-y: auto; padding-right: 6px;">
                                <!-- Legenda modal akan diisi oleh JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeChartModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('asetPieChart');

        // Matikan legend bawaan Chart.js secara global (kompatibel v2/v3/v4)
        if (typeof Chart !== 'undefined' && Chart.defaults) {
            if (Chart.defaults.plugins && Chart.defaults.plugins.legend) {
                Chart.defaults.plugins.legend.display = false;
            }
            if (Chart.defaults.global && Chart.defaults.global.legend) {
                Chart.defaults.global.legend.display = false;
            }
        }

        // Beri peringatan jika Chart.js gagal dimuat
        if (typeof Chart === 'undefined') {
            console.error('Chart.js tidak dimuat. Periksa koneksi CDN.');
        }

        // Ambil data dari canvas attributes dengan fallback
        let labels, dataValues;
        try {
            labels = JSON.parse(canvas.dataset.labels || '[]');
            dataValues = JSON.parse(canvas.dataset.values || '[]');
        } catch (e) {
            console.error('Error parsing chart data:', e);
            labels = ['Sample Room 1', 'Sample Room 2', 'Sample Room 3'];
            dataValues = [1000000, 2000000, 1500000];
        }

        // Jika tidak ada data, gunakan sample data
        if (labels.length === 0 || dataValues.length === 0) {
            labels = ['Ruang Admin', 'Ruang Guru', 'Ruang Kelas'];
            dataValues = [5000000, 3000000, 2000000];
        }

        function generateColors(n) {
            const colors = [];
            for (let i = 0; i < n; i++) {
                const hue = Math.round((360 / n) * i);
                colors.push(`hsl(${hue} 70% 55%)`);
            }
            return colors;
        }

        const ctx = document.getElementById('asetPieChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: generateColors(dataValues.length),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 20,
                        bottom: 20,
                        left: 20,
                        right: 20
                    }
                },
                // Pastikan legend default benar-benar disembunyikan (Chart.js v2/v3/v4)
                legend: {
                    display: false
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda default
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed;
                                let percentage = ((value / dataValues.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
                                return [
                                    `${context.label}`,
                                    `Jumlah Barang: ${value.toLocaleString()} unit`,
                                    `Persentase: ${percentage}%`
                                ];
                            }
                        }
                    }
                }
            }
        });

        // Buat legenda custom
        function createCustomLegend() {
            const legendContainer = document.getElementById('customLegend');
            const colors = generateColors(labels.length);

            let legendHTML = '';
            labels.forEach((label, index) => {
                const value = dataValues[index];
                const percentage = ((value / dataValues.reduce((a, b) => a + b, 0)) * 100).toFixed(1);

                legendHTML += `
                    <div class="d-flex align-items-center mb-2 legend-item" style="cursor: pointer;" onclick="toggleDataset(${index})">
                        <div style="width: 16px; height: 16px; background-color: ${colors[index]}; margin-right: 8px; border-radius: 3px;"></div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold" style="font-size: 13px;">${label}</div>
                            <div class="text-muted" style="font-size: 11px;">
                                ${value.toLocaleString()} unit (${percentage}%)
                            </div>
                        </div>
                    </div>
                `;
            });

            legendContainer.innerHTML = legendHTML;
        }

        // Fungsi untuk toggle dataset saat legenda diklik
        function toggleDataset(index) {
            const meta = chart.getDatasetMeta(0);
            meta.data[index].hidden = !meta.data[index].hidden;
            chart.update();
        }

        // Panggil fungsi untuk membuat legenda
        createCustomLegend();

        // Variabel untuk chart modal
        let modalChart = null;

        // Fungsi untuk membuka modal chart
        function openChartModal() {
            $('#chartModal').modal('show');

            // Tunggu modal selesai dibuka (hindari multiple binding)
            $('#chartModal').off('shown.bs.modal').on('shown.bs.modal', function() {
                if (modalChart) {
                    modalChart.destroy();
                }
                createModalChart();
            });
        }

        // Fungsi untuk menutup modal chart
        function closeChartModal() {
            $('#chartModal').modal('hide');
        }

        // Fungsi untuk membuat chart di modal
        function createModalChart() {
            const modalCanvas = document.getElementById('asetPieChartModal');
            const modalCtx = modalCanvas.getContext('2d');

            modalChart = new Chart(modalCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: generateColors(dataValues.length),
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 20,
                            left: 20,
                            right: 20
                        }
                    },
                    // Pastikan legend default benar-benar disembunyikan (Chart.js v2/v3/v4)
                    legend: {
                        display: false
                    },
                    plugins: {
                        legend: {
                            display: false // Sembunyikan legenda default
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.parsed;
                                    let percentage = ((value / dataValues.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
                                    return [
                                        `${context.label}`,
                                        `Jumlah Barang: ${value.toLocaleString()} unit`,
                                        `Persentase: ${percentage}%`
                                    ];
                                }
                            }
                        }
                    }
                }
            });

            // Buat legenda untuk modal
            createModalLegend();
        }

        // Fungsi untuk membuat legenda di modal
        function createModalLegend() {
            const legendContainer = document.getElementById('customLegendModal');
            const colors = generateColors(labels.length);

            let legendHTML = '';
            labels.forEach((label, index) => {
                const value = dataValues[index];
                const percentage = ((value / dataValues.reduce((a, b) => a + b, 0)) * 100).toFixed(1);

                legendHTML += `
                    <div class="d-flex align-items-center mb-2 legend-item" style="cursor: pointer;" onclick="toggleModalDataset(${index})">
                        <div style="width: 14px; height: 14px; background-color: ${colors[index]}; margin-right: 8px; border-radius: 3px;"></div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold" style="font-size: 11px; line-height: 1.3;">${label}</div>
                            <div class="text-muted" style="font-size: 10px;">
                                ${value.toLocaleString()} unit (${percentage}%)
                            </div>
                        </div>
                    </div>
                `;
            });

            legendContainer.innerHTML = legendHTML;
        }

        // Fungsi untuk toggle dataset di modal
        function toggleModalDataset(index) {
            if (modalChart) {
                const meta = modalChart.getDatasetMeta(0);
                meta.data[index].hidden = !meta.data[index].hidden;
                modalChart.update();
            }
        }

        // Bind event hanya jika jQuery tersedia (Bootstrap 4)
        if (window.jQuery) {
            // Cleanup saat modal ditutup
            $('#chartModal').on('hidden.bs.modal', function() {
                if (modalChart) {
                    modalChart.destroy();
                    modalChart = null;
                }
            });

            // Event listener untuk menutup modal dengan ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#chartModal').hasClass('show')) {
                    closeChartModal();
                }
            });

            // Event listener untuk menutup modal saat klik di luar area
            $('#chartModal').on('click', function(e) {
                if (e.target === this) {
                    closeChartModal();
                }
            });
        }
    </script>
    @endsection

    @push('styles')
    <style>
        /* Hide breadcrumb on dashboard page */
        #auto-breadcrumbs {
            display: none !important;
        }

        /* Responsive welcome title: scale between ~20px and ~32px */
        .welcome-title {
            font-size: clamp(1.25rem, 2.5vw + 0.5rem, 2rem);
            line-height: 1.2;
        }

        .text-3d {
            color: rgb(89, 89, 89);
            /* warna putih utama */
            -webkit-text-stroke: 0.5px rgba(0, 0, 0, 0.3);
            text-shadow:
                1px 1px 2px rgba(0, 0, 0, 0.4),
                2px 2px 4px rgba(0, 0, 0, 0.3),
                3px 3px 6px rgba(0, 0, 0, 0.2);
            /* bayangan lembut agar tampak timbul */
            letter-spacing: 0.5px;
            /* sedikit jarak antar huruf agar tegas */
            font-weight: 700;
            /* lebih tebal agar efek 3D terlihat jelas */
        }

        /* Responsive chart container height */
        .chart-container {
            width: 100%;
            height: 320px;
            cursor: pointer;
        }

        @media (min-width: 576px) {
            .chart-container {
                height: 360px;
            }
        }

        @media (min-width: 768px) {
            .chart-container {
                height: 420px;
            }
        }

        @media (min-width: 1200px) {
            .chart-container {
                height: 550px;
            }
        }

        @media (min-width: 768px) {
            .welcome-title {
                text-align: left !important;
            }
        }

        /* Ensure welcome message is centered on mobile */
        @media (max-width: 767.98px) {
            .welcome-title {
                text-align: center !important;
            }
        }

        @media (max-width: 767.98px) {
            .row.mt-2.align-items-stretch>.col-12:first-child {
                margin-bottom: 1.5rem !important;
            }
        }

        .card-body .d-flex {
            flex-wrap: nowrap;
            min-width: 0;
        }

        .card-body .text-xs {
            font-size: clamp(0.7rem, 1.8vw, 0.85rem);
        }

        .card-body .h5 {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
        }

        .card-body i {
            font-size: clamp(1.25rem, 3vw, 2rem);
            flex-shrink: 0;
        }

        @media (max-width: 575.98px) {
            .card.p-3 {
                padding: 0.75rem !important;
            }
        }

        @media (max-width: 767.98px) {
            .col-12.col-sm-6.col-lg-3.mb-4 {
                margin-bottom: 1rem !important;
            }
        }

        .card-body .font-weight-bold,
        .card-body .text-muted {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (min-width: 576px) and (max-width: 992px) {
            .card-body i {
                font-size: clamp(1.25rem, 2vw, 1.75rem) !important;
            }
        }

        #customLegend {
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }

        #customLegend::-webkit-scrollbar {
            width: 8px;
        }

        #customLegend::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        #customLegend::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        #customLegend .legend-item {
            font-size: 12px;
        }

        #customLegend .legend-item .font-weight-bold {
            font-size: 12px;
        }

        #customLegend .legend-item .text-muted {
            font-size: 10px;
        }

        /* Responsive adjustments for cards at 993px and maintain 4 columns until 1430px */
        /* 2 kolom per baris (untuk layar 993px–1430px) */
        @media (min-width: 993px) and (max-width: 1430px) {
            .col-12.col-sm-6.col-lg-3.mb-4 {
                flex: 0 0 50%;
                /* Lebar tiap kolom 50% */
                max-width: 50%;
            }

            .card.p-3 {
                padding: 1rem !important;
                /* biar tidak terlalu mepet */
            }

            .card-body i {
                font-size: 1.25rem;
            }

            .card-body .text-xs {
                font-size: 0.8rem;
            }

            .card-body .h5 {
                font-size: 1rem;
            }
        }

        /* Tambahan untuk layar 993px–1200px (lebih kecil lagi) */
        @media (min-width: 993px) and (max-width: 1200px) {
            .card-body i {
                font-size: 1.1rem;
            }

            .card-body .text-xs {
                font-size: 0.7rem;
            }

            .card-body .h5 {
                font-size: 0.9rem;
            }
        }
    </style>
    @endpush