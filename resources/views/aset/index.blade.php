@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">
    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-3"></i> Manajemen Aset Inventaris
        </span>
    </h2>

    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-cog me-2"></i> Manajemen Aset Inventaris
        </span>
    </h5>

    <hr class="section-divider">

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('aset.index') }}" id="filterForm">
                <div class="d-flex justify-content-between align-items-end flex-wrap filter-header aksi-filter aksi-reset">
                    {{-- Filter Tanggal --}}
                    <div class="d-flex align-items-center gap-2 filter-tanggal">
                        <label class="form-label mb-0">Tanggal</label>
                        <input type="date" name="date_from" id="date_from"
                            class="form-control form-control-sm" style="max-width: 160px;"
                            value="{{ request('date_from') }}">
                        <span>s/d</span>
                        <input type="date" name="date_to" id="date_to"
                            class="form-control form-control-sm" style="max-width: 160px;"
                            value="{{ request('date_to') }}">
                        <div class="btn-filter-wrap" style="min-width: 90px;">
                            <button type="submit" class="btn btn-info btn-sm w-100">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>

                        <!-- Reset -->
                        <div class="btn-reset-wrap" style="min-width: 90px; margin-left: 18px;">
                            <a href="{{ route('aset.index') }}" class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </div>

                    {{-- Tombol Cetak & Tambah Aset --}}
                    <div class="d-flex justify-content-md-end align-items-center gap-2 aksi-filter aksi-tambah aksi-cetak">
                        @can('aset-print')
                        <div class="btn-group" style="min-width: 120px;">
                            <button class="btn btn-primary dropdown-toggle btn-sm w-100" type="button"
                                id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true"
                                aria-expanded="false">
                                <i class="fas fa-print me-1"></i> Pilihan Cetak
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                                <li>
                                    <button type="button" class="dropdown-item"
                                        data-url="{{ route('aset.print') }}"
                                        onclick="submitPrintForm(this.dataset.url)">
                                        <strong>Cetak Aset</strong>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item"
                                        data-url="{{ route('aset.printjenis') }}"
                                        onclick="submitPrintForm(this.dataset.url)">
                                        <strong>Cetak Per Jenis Brg</strong>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item"
                                        data-url="{{ route('aset.printqrcodeall') }}"
                                        onclick="submitPrintForm(this.dataset.url)">
                                        <strong>Cetak QrCode</strong>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        @endcan

                        @can('aset-create')
                        <a href="{{ route('aset.create') }}" class="btn btn-success btn-sm" style="min-width: 120px;">
                            <i class="fas fa-plus-circle me-1"></i>Tambah Aset
                        </a>
                        <button type="button" class="btn btn-info btn-sm" style="min-width: 120px;" data-bs-toggle="modal" data-bs-target="#modalImport">
                            <i class="fas fa-file-excel me-1"></i>Impor Excel
                        </button>
                        @endcan
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="myTable" class="table table-hover table-bordered display nowrap w-100">
                    <thead class="table-gradient-header">
                        <tr class="text-center">
                            <th class="text-center" style="width: 2%;">No</th>
                            <th class="text-center" style="width: 10%;">Tgl Input</th>
                            <th class="text-center" style="width: 15%;">Kode Aset</th>
                            <th class="text-center" style="width: 20%;">Ruangan</th>
                            <th class="text-center" style="width: 10%;">Nama Barang</th>
                            <th class="text-center" style="width: 25%;">Deskripsi</th>
                            <th class="text-center" style="width: 3%;">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <label for="selectAll" class="mb-1">All</label>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </div>
                            </th>

                            <th class="text-center" style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($asets as $key => $item)
                        @php
                        $tahun = \Carbon\Carbon::parse($item->periode)->format('Y');
                        $bulan = \Carbon\Carbon::parse($item->periode)->format('m');
                        $kodeRuang = $item->ruang->code ?? 'XX';
                        $kodeKategori = $item->kategori->kode ?? 'XX';
                        $idFormatted = str_pad($item->id_aset, 4, '0', STR_PAD_LEFT);
                        $barang_id = "$kodeRuang-$tahun-$bulan-$kodeKategori-$idFormatted";
                        @endphp
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $barang_id }}</td>
                            <td>{{ $item->ruang->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->nama_brg }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td class="text-center align-middle py-3">
                                <input type="checkbox" name="selected_asets[]" value="{{ $item->id_aset }}" class="form-check-input aset-checkbox">
                            </td>
                            <td class="text-center align-middle td-aksi">
                                @can('aset-view')
                                <a href="{{ route('aset.show', $item) }}" class="btn btn-sm btn-info" title="Lihat Aset">
                                    <i class="fas fa-search"></i>
                                </a>
                                @endcan
                                @can('aset-edit')
                                <a href="{{ route('aset.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit Aset">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('aset-delete')
                                <button onclick="confirmDelete('form-id-{{ $item->id_aset }}')" class="btn btn-sm btn-danger" title=" Hapus Aset">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Form hapus -->
                                <form id="form-id-{{ $item->id_aset }}" action="{{ route('aset.destroy', ['aset' => $item->id_aset]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel">
                    <i class="fas fa-file-excel me-2"></i>Impor Data Aset dari Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('aset.import') }}" method="POST" enctype="multipart/form-data" id="formImport">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">
                            <strong>Pilih File Excel</strong>
                        </label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xls,.xlsx" required>
                        <small class="text-muted d-block mt-2">
                            Format file: .xls atau .xlsx (Maksimal 10MB)
                        </small>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="mb-2"><strong>Format Excel yang Diperlukan:</strong></h6>
                        <small>
                            <strong>Kolom wajib:</strong><br>
                            - nama_brg (Nama Barang)<br>
                            - merk (Merk)<br>
                            - bahan (Bahan)<br>
                            - code_ruang (Kode Ruang)<br>
                            - code_kategori (Kode Kategori)<br><br>

                            <strong>Kolom opsional:</strong><br>
                            - seri (Seri/No Seri)<br>
                            - ukuran (Ukuran)<br>
                            - periode (Periode - format: YYYY-MM-DD atau tanggal Excel)<br>
                            - date (Tanggal Input - format: YYYY-MM-DD atau tanggal Excel)<br>
                            - keterangan (Keterangan)<br>
                            - jumlah_brg (Jumlah Barang, default: 1)<br>
                            - harga (Harga)<br>
                            - kondisi (Kondisi: Baik/Kurang Baik/Rusak Berat, default: Baik)<br>
                            - st_aset (Status Aset: BL/HBH, default: BL)<br>
                            - satuan (Satuan, default: unit)<br>
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Catatan:</strong> Pastikan code_ruang dan code_kategori sudah ada di database sebelum melakukan import.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnImport">
                        <i class="fas fa-upload me-1"></i> Impor Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kategori untuk Cetak Per Jenis Barang -->
<div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKategoriLabel">Pilih Kategori untuk Cetak Per Jenis Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Kiri: Checkbox Kategori -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllKategori">
                                <label class="form-check-label" for="selectAllKategori">
                                    <strong>Pilih Semua Kategori</strong>
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @foreach($kategori as $kat)
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input kategori-checkbox" type="checkbox" value="{{ $kat->kode }}" id="kategori_{{ $kat->kode }}">
                                    <label class="form-check-label" for="kategori_{{ $kat->kode }}">
                                        {{ $kat->kode }} - {{ $kat->nama }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kanan: Dropdown Ruangan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ruangDropdown" class="form-label"><strong>Ruangan</strong></label>
                            <select id="ruangDropdown" class="form-select" multiple data-placeholder="Pilih ruangan...">
                                @foreach($ruang as $r)
                                <option value="{{ $r->code }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Anda bisa memilih lebih dari satu ruangan atau biarkan kosong untuk semua ruangan.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitPrintJenis()">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 for searchable multi-select -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#myTable').DataTable({
            autoWidth: false,
            responsive: true,
            scrollX: true,
            columnDefs: [{
                orderable: false,
                targets: [6, 7]
            }]
        });

        // Persist selected aset IDs across pagination (make it global so print() can access)
        window.selectedAsetIds = window.selectedAsetIds || new Set();

        // Init Select2 on Ruangan dropdown inside modal
        $('#ruangDropdown').select2({
            dropdownParent: $('#modalKategori'),
            width: '100%',
            placeholder: $('#ruangDropdown').data('placeholder') || 'Pilih ruangan...',
            allowClear: true
        });

        // Helper: get checkboxes only on current page
        function getVisibleCheckboxes() {
            return $(table.rows({
                page: 'current'
            }).nodes()).find('.aset-checkbox');
        }

        // On table draw: re-apply checks from memory and sync header selectAll
        table.on('draw.dt', function() {
            // Re-check rows based on memory
            $(table.rows({
                page: 'current'
            }).nodes()).each(function() {
                const cb = $(this).find('.aset-checkbox');
                const id = cb.val();
                cb.prop('checked', window.selectedAsetIds.has(id));
            });

            // Update header selectAll based on visible rows only
            const $visible = getVisibleCheckboxes();
            const allChecked = $visible.length > 0 && $visible.filter(':checked').length === $visible.length;
            $('#selectAll').prop('checked', allChecked);
        });

        // Initial sync on first load
        table.on('init.dt', function() {
            const $visible = getVisibleCheckboxes();
            const allChecked = $visible.length > 0 && $visible.filter(':checked').length === $visible.length;
            $('#selectAll').prop('checked', allChecked);
        });

        // Select All functionality (only affects current page)
        $('#selectAll').change(function() {
            const check = $(this).is(':checked');
            getVisibleCheckboxes().each(function() {
                $(this).prop('checked', check);
                const id = $(this).val();
                if (check) {
                    window.selectedAsetIds.add(id);
                } else {
                    window.selectedAsetIds.delete(id);
                }
            });
        });

        // Individual checkbox change: update memory and header selectAll for current page
        $(document).on('change', '.aset-checkbox', function() {
            const id = $(this).val();
            if ($(this).is(':checked')) {
                window.selectedAsetIds.add(id);
            } else {
                window.selectedAsetIds.delete(id);
            }

            const $visible = getVisibleCheckboxes();
            const allChecked = $visible.length > 0 && $visible.filter(':checked').length === $visible.length;
            $('#selectAll').prop('checked', allChecked);
        });

        // Select All Kategori functionality
        $('#selectAllKategori').change(function() {
            $('.kategori-checkbox').prop('checked', $(this).is(':checked'));
        });

        // Individual kategori checkbox change
        $('.kategori-checkbox').change(function() {
            if (!$(this).is(':checked')) {
                $('#selectAllKategori').prop('checked', false);
            } else {
                // Check if all kategori checkboxes are checked
                if ($('.kategori-checkbox:checked').length === $('.kategori-checkbox').length) {
                    $('#selectAllKategori').prop('checked', true);
                }
            }
        });
    });

    function submitPrintForm(actionUrl) {
        // Jika URL adalah printjenis, tampilkan modal kategori
        if (actionUrl.includes('printjenis')) {
            $('#modalKategori').modal('show');
            return;
        }

        let form = document.createElement('form');
        form.method = 'GET';
        form.action = actionUrl;
        form.target = '_blank'; // buka tab baru

        // Ambil semua aset yang disimpan di memori (lintas halaman)
        if (typeof window.selectedAsetIds !== 'undefined') {
            window.selectedAsetIds.forEach(function(id) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_asets[]';
                input.value = id;
                form.appendChild(input);
            });
        } else {
            // Fallback: ambil dari DOM yang sedang tampil
            document.querySelectorAll('.aset-checkbox:checked').forEach(cb => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_asets[]';
                input.value = cb.value;
                form.appendChild(input);
            });
        }

        if (form.querySelectorAll('input').length === 0) {
            const isDarkMode = document.body.classList.contains('dark-mode');

            // Tema warna berdasarkan mode
            const swalTheme = {
                background: isDarkMode ? "#1e1e2f" : "#fff",
                color: isDarkMode ? "#f1f1f1" : "#000",
                confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6",
                iconColor: isDarkMode ? "#f8d44c" : "#f8bb86"
            };

            // Animasi (gunakan Animate.css)
            const swalAnimation = {
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            };

            Swal.fire({
                icon: "warning",
                title: "Tidak ada aset dipilih",
                text: "Silakan checklist aset yang ingin dicetak.",
                background: swalTheme.background,
                color: swalTheme.color,
                confirmButtonColor: swalTheme.confirmButtonColor,
                iconColor: swalTheme.iconColor,
                ...swalAnimation
            });

            return;
        }


        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    function submitPrintJenis() {
        let selectedKategori = [];
        document.querySelectorAll('.kategori-checkbox:checked').forEach(cb => {
            selectedKategori.push(cb.value);
        });

        if (selectedKategori.length === 0) {
            const isDarkMode = document.body.classList.contains('dark-mode');

            // Tema warna sesuai mode
            const swalTheme = {
                background: isDarkMode ? "#1e1e2f" : "#fff",
                color: isDarkMode ? "#f1f1f1" : "#000",
                confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6",
                iconColor: isDarkMode ? "#f8d44c" : "#f8bb86"
            };

            // Animasi (gunakan Animate.css)
            const swalAnimation = {
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            };

            Swal.fire({
                icon: "warning",
                title: "Tidak ada kategori dipilih",
                text: "Silakan pilih minimal satu kategori untuk dicetak.",
                background: swalTheme.background,
                color: swalTheme.color,
                confirmButtonColor: swalTheme.confirmButtonColor,
                iconColor: swalTheme.iconColor,
                ...swalAnimation
            });

            return;
        }


        let form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("aset.printjenis") }}';
        form.target = '_blank';

        // Tambahkan parameter kategori yang dipilih
        selectedKategori.forEach(kode => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_kategori[]';
            input.value = kode;
            form.appendChild(input);
        });

        // Tambahkan parameter ruangan jika dipilih (mendukung multi-select)
        const selectedRuangs = ($('#ruangDropdown').val() || []);
        selectedRuangs.forEach(code => {
            let inputR = document.createElement('input');
            inputR.type = 'hidden';
            inputR.name = 'selected_ruang[]';
            inputR.value = code;
            form.appendChild(inputR);
        });

        // Tambahkan parameter tanggal jika ada
        let dateFromInput = document.getElementById('date_from');
        let dateToInput = document.getElementById('date_to');

        if (dateFromInput && dateFromInput.value) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_from';
            input.value = dateFromInput.value;
            form.appendChild(input);
        }

        if (dateToInput && dateToInput.value) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_to';
            input.value = dateToInput.value;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);

        // Tutup modal
        $('#modalKategori').modal('hide');
    }

    function confirmDelete(formId) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Apakah anda yakin?",
            text: "Ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Tidak, Batalkan!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Batal",
                    text: "Data tidak dihapus.",
                    icon: "error"
                });
            }
        });

        // Handle form import submit
        $('#formImport').on('submit', function(e) {
            const fileInput = $('#file')[0];
            if (!fileInput.files || !fileInput.files[0]) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "File belum dipilih",
                    text: "Silakan pilih file Excel terlebih dahulu.",
                });
                return false;
            }

            // Show loading
            $('#btnImport').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Mengimpor...');
        });
    }
</script>
@endpush

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    table-gradient-header {
        /* Hapus background dari thead itu sendiri agar tidak menghalangi */
        background-color: transparent !important;
        background-image: none !important;
    }

    /* Target sel header (th) di dalam thead untuk menerapkan gradien */
    .table-gradient-header th {
        background-image: linear-gradient(to bottom, #b3e5fc, #81d4fa) !important;
        background-color: transparent !important;
        color: #000;
        border-color: #9fcdff !important;
    }

    /* Default (PC) */
    .filter-header {
        gap: .5rem;
    }

    .filter-tanggal {
        display: flex !important;
        align-items: center;
        gap: .5rem;
    }

    .filter-tanggal label {
        margin-bottom: 0;
    }

    .filter-tanggal input[type="date"] {
        max-width: 160px;
    }

    .btn-filter-wrap,
    .btn-reset-wrap {
        min-width: 90px;
    }

    /* Tombol aksi (PC) */
    .aksi-filter .btn,
    .aksi-filter .dropdown {
        width: auto;
        min-width: 120px;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Mode HP */
    @media (max-width: 576px) {

        /* Susun kontainer filter bertumpuk */
        .filter-header {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }

        /* Tanggal: label di atas, dua input satu baris dengan 's/d' di tengah */
        .filter-tanggal {
            display: grid !important;
            /* override .d-flex */
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            gap: .5rem;
            align-items: center;
            width: 100%;
            padding: 0 2px;
            box-sizing: border-box;
            margin-left: 0 !important;
            /* override ms-4 */
        }

        .filter-tanggal label {
            grid-column: 1 / -1;
            align-self: start;
            font-weight: 600;
            margin-bottom: 0;
        }

        .filter-tanggal input[type="date"] {
            width: 100%;
            max-width: 100% !important;
            /* override inline max-width */
            margin: 0;
        }

        .filter-tanggal span {
            text-align: center;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
        }

        .btn-filter-wrap,
        .btn-reset-wrap {
            grid-column: 1 / -1;
            width: 100%;
        }

        .btn-filter-wrap .btn,
        .btn-reset-wrap .btn {
            width: 100%;
        }

        /* Hapus margin kiri inline pada Reset agar sejajar */
        .btn-reset-wrap {
            margin-left: 0 !important;
        }

        /* Tombol aksi: full width bertumpuk */
        .aksi-filter {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
            margin-right: 0 !important;
            /* override me-4 */
            margin-left: 0 !important;
        }

        .aksi-filter .btn,
        .aksi-filter .dropdown,
        .aksi-filter a,
        .aksi-filter form,
        .aksi-filter .btn-group {
            width: 100% !important;
            display: block !important;
        }

        /* Dropdown menu selebar tombol */
        .aksi-filter .dropdown-menu {
            width: 100% !important;
        }

        #judul-aset {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-aset .fa-cog {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }
    }

    @media print {

        /* Sembunyikan elemen yang tidak perlu saat print */
        body * {
            visibility: hidden;
        }

        .container-fluid,
        .container-fluid * {
            visibility: visible;
        }

        .container-fluid {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .btn,
        .dataTables_filter,
        .dataTables_length,
        .dataTables_info,
        .dataTables_paginate {
            display: none !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000 !important;
            padding: 5px !important;
        }
    }

    /* Pusatkan checkbox tanpa offset manual */
    .table td .form-check-input,
    .table th .form-check-input {
        margin: 0;
        /* no extra margins */
        position: static;
        /* reset positioning */
        transform: none;
        /* no transform */
    }
</style>
@endpush