@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-3"></i> Tambah Aset Inventaris
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-plus-square me-2"></i> Tambah Aset Inventaris
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('aset.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>

    <div class="section-body mb-5">
        <div class="card">
            <div class="card-body p-4">
                <form id="form-aset" action="{{ route('aset.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-12 col-md-8">
                            <div class="row">
                                <div class="col-12 col-md-2 mb-3">
                                    <label for="st_aset" class="form-label">Status</label>
                                    <div class="position-relative">
                                        <select name="st_aset" id="st_aset" class="form-control" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="BL" {{ old('st_aset') == 'BL' ? 'selected' : '' }}>Beli</option>
                                            <option value="HBH" {{ old('st_aset') == 'HBH' ? 'selected' : '' }}>Hibah</option>
                                        </select>
                                        <i class="fas fa-chevron-down position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;">
                                        </i>
                                    </div>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *harap pilih salah satu.
                                    </small>
                                </div>
                                <div class="col-12 col-md-2 mb-3">
                                    <label for="date" class="form-label">Tanggal Input</label>
                                    <input type="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly style="background-color: #f8f9fa;">
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *tanggal sesuai hari input.
                                    </small>
                                </div>
                                <div class="col-12 col-md-2 mb-3">
                                    <label for="periode" class="form-label">Tahun Perolehan</label>
                                    <input type="date" name="periode" id="periode" class="form-control" value="{{ old('periode') }}" required>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *tolong data ini harap diisi.
                                    </small>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="nama_brg" class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_brg" id="nama_brg" class="form-control" value="{{ old('nama_brg') }}" required>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *tolong data ini harap diisi.
                                    </small>
                                </div>
                                <div class="col-12 col-md-2 mb-3">
                                    <label for="jumlah_brg" class="form-label">Jumlah</label>
                                    <div class="input-group position-relative">
                                        <input type="number" name="jumlah_brg" id="jumlah_brg" class="form-control " value="{{ old('jumlah_brg', 1) }}" readonly style="background-color: #f8f9fa;">
                                        <div class="position-relative" style="flex: 0 0 auto;">
                                            <select name="satuan" id="satuan" class="form-control btn-satuan-blue" style="max-width: 100px; border-radius: 0 6px 6px 0; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                <option value="">Satuan</option>
                                                <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>Unit</option>
                                                <option value="buah" {{ old('satuan') == 'buah' ? 'selected' : '' }}>Buah</option>
                                                <option value="set" {{ old('satuan') == 'set' ? 'selected' : '' }}>Set</option>
                                                <option value="lbr" {{ old('satuan') == 'lbr' ? 'selected' : '' }}>Lembar</option>
                                                <option value="lsn" {{ old('satuan') == 'lsn' ? 'selected' : '' }}>Lusin</option>
                                                <option value="roll" {{ old('satuan') == 'roll' ? 'selected' : '' }}>Roll</option>
                                                <option value="psng" {{ old('satuan') == 'psng' ? 'selected' : '' }}>Pasang</option>
                                            </select>
                                            <i class="fas fa-chevron-down position-absolute" style="right: 8px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #fff;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="merk" class="form-label">Merk</label>
                                    <input type="text" name="merk" id="merk" class="form-control" value="{{ old('merk') }}" required>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *tolong data ini harap diisi.
                                    </small>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="seri" class="form-label">No. Seri</label>
                                    <input type="text" name="seri" class="form-control" value="{{ old('seri') }}">
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="ukuran" class="form-label">Ukuran</label>
                                    <input type="text" name="ukuran" class="form-control" value="{{ old('ukuran') }}">
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="bahan" class="form-label">Bahan</label>
                                    <input type="text" name="bahan" id="bahan" class="form-control" value="{{ old('bahan') }}" required>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *tolong data ini harap diisi.
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="harga" class="form-label">Harga Beli</label>
                                    <input type="text" step="0.01" min="0" name="harga" id="harga" class="form-control" value="{{ old('harga') }}" required>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *tolong data ini harap diisi.
                                    </small>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="kondisi" class="form-label">Kondisi</label>
                                    <div class="position-relative">
                                        <select name="kondisi" id="kondisi" class="form-control" required>
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="Kurang Baik" {{ old('kondisi') == 'Kurang Baik' ? 'selected' : '' }}>Kurang Baik</option>
                                            <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        </select>
                                        <i class="fas fa-chevron-down position-absolute"
                                            style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;"></i>
                                    </div>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *harap pilih salah satu.
                                    </small>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="code_kategori" class="form-label">Jenis Barang</label>
                                    <div class="position-relative">
                                        <select name="code_kategori" id="code_kategori" class="form-control" required>
                                            <option value="">-- Pilih Jenis Barang --</option>
                                            @foreach($kategori as $kat)
                                            <option value="{{ $kat->kode }}" {{ old('code_kategori') == $kat->kode ? 'selected' : '' }}>
                                                {{ $kat->kode }} - {{ $kat->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down position-absolute"
                                            style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;"></i>
                                    </div>
                                    <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *harap pilih salah satu.
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="code_ruang" class="form-label">Ruangan</label>
                                <div class="input-group">
                                    <input type="text" id="ruang_nama" class="form-control" placeholder="Pilih ruangan" onkeydown="return false;">
                                    <input type="hidden" name="code_ruang" id="code_ruang">
                                    <button type="button" class="btn btn-gradient-blue" data-bs-toggle="modal" data-bs-target="#modalRuang">Pilih</button>
                                </div>
                                <small class="text-danger" style="text-transform: lowercase; display:block; margin-top:2px;">
                                    *harap pilih salah satu.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                            </div>
                            <!-- Ambil Foto Langsung dipindah ke bawah keterangan -->
                            <div class="mb-3 d-flex flex-column align-items-center aksi-filter aksi-galeri">
                                <div class="border border-secondary rounded p-3 d-flex flex-column align-items-center w-100 mb-3" style="max-width: 340px;">
                                    <h6 class="mb-2">Ambil Foto Langsung</h6>
                                    <div id="cameraSelectWrapper" class="w-100 mb-2" style="display:none;">
                                        <label for="cameraSelect" class="form-label">Pilih Kamera</label>
                                        <select id="cameraSelect" class="form-select"></select>
                                    </div>
                                    <video id="video" class="border rounded mb-2 w-100" style="max-width:320px; height:auto;" autoplay playsinline></video>
                                    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                                    <button type="button" class="btn btn-info btn-sm mb-2" onclick="take_snapshot()">
                                        <i class="fas fa-camera-retro me-1"></i> Ambil Foto
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm mb-2" onclick="document.getElementById('galleryInput').click()">
                                        <i class="fas fa-image me-1"></i> Ambil Galeri
                                    </button>
                                    <input type="file" id="galleryInput" accept="image/*" multiple style="display:none;" />
                                    <small class="text-danger d-block mt-1" style="text-transform: lowercase; display:block; margin-top:2px;">
                                        *harap ambil foto barang invetaris.
                                    </small>
                                    <input type="hidden" name="image_url_1" id="image_url_input_1">
                                    <input type="hidden" name="image_url_2" id="image_url_input_2">
                                    <input type="hidden" name="image_url_3" id="image_url_input_3">
                                    <input type="hidden" name="image_url_4" id="image_url_input_4">
                                    <div id="error-message" class="text-danger text-center mt-1"></div>
                                    <div id="no-camera-message" class="text-danger text-center mt-2" style="display:none;">Kamera tidak tersedia atau tidak diizinkan.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Preview Hasil Foto saja -->
                        <div class="col-12 col-md-4 d-flex flex-column align-items-center">
                            <label class="form-label">Preview Hasil Foto</label>
                            <div id="fotoCarousel" class="carousel slide w-100" data-bs-ride="carousel" style="max-width:320px;">
                                <div class="carousel-indicators">
                                    @for($i = 0; $i < 4; $i++)
                                        <button type="button" data-bs-target="#fotoCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}" aria-current="{{ $i == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $i + 1 }}"></button>
                                        @endfor
                                </div>
                                <div class="carousel-inner" id="carouselInner">
                                    @for($i = 1; $i <= 4; $i++)
                                        <div class="carousel-item {{ $i == 1 ? 'active' : '' }}" id="carousel_item_{{ $i }}">
                                        <div class="d-flex flex-column align-items-center p-2 border rounded" style="min-height:240px;">
                                            <img src="{{ asset('assets/img/aset-placeholder.png') }}" class="d-block w-100 rounded mb-2 placeholder-img aset-img" alt="Placeholder Foto {{ $i }}">
                                            <div class="carousel-caption" style="bottom: 60px;">
                                                <span class="badge bg-dark">Foto {{ $i }} dari 4</span>
                                            </div>
                                        </div>
                                </div>
                                @endfor
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#fotoCarousel" data-bs-slide="prev">
                                <span class="fa fa-chevron-left custom-arrow" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#fotoCarousel" data-bs-slide="next">
                                <span class="fa fa-chevron-right custom-arrow" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

            </div>

            <!-- Tombol Aksi -->
            <div id="fotoHiddenInputs"></div>
            <div class="d-flex justify-content-between mt-4 aksi-save aksi-reset">
                <button type="reset" class="btn btn-secondary btn-sm">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pilih Ruang --}}
<div class="modal fade" id="modalRuang" tabindex="-1" aria-labelledby="modalRuangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-hover table-bordered display">
                        <thead class="table-gradient-header">
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Kode Ruangan</th>
                                <th class="text-center">Nama Ruangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruang as $index => $r)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $r->code }}</td>
                                <td>{{ $r->name }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn-pilih-ruang"
                                        data-code="{{ $r->code }}"
                                        data-nama="{{ $r->name }}">
                                        <i class="fas fa-check-square"></i> Pilih
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient-red-tutup btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@push('scripts')
<script>
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    let cameraSelect = document.getElementById('cameraSelect');
    let cameraSelectWrapper = document.getElementById('cameraSelectWrapper');
    let noCameraMsg = document.getElementById('no-camera-message');
    let currentStream;
    const placeholderPath = "{{ asset('assets/img/aset-placeholder.png') }}";

    $(document).ready(function() {
        $('#myTable').DataTable();
        $('#filteredTable').DataTable();

        // Event delegation untuk tombol pilih ruangan
        $('#myTable').on('click', '.btn-pilih-ruang', function() {
            const code = $(this).data('code');
            const nama = $(this).data('nama');
            $('#ruang_nama').val(nama);
            $('#code_ruang').val(code);
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalRuang'));
            modal.hide();
        });
    });

    document.getElementById('harga').addEventListener('input', function(e) {
        let value = e.target.value;

        // Hapus karakter non-digit
        value = value.replace(/[^0-9]/g, '');

        // Format angka dengan titik sebagai pemisah ribuan
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        e.target.value = value;
    });

    async function getCameras() {
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                noCameraMsg.style.display = 'block';
                return [];
            }
            const devices = await navigator.mediaDevices.enumerateDevices();
            const cameras = devices.filter(device => device.kind === 'videoinput');
            cameraSelect.innerHTML = '';
            if (cameras.length === 0) {
                noCameraMsg.style.display = 'block';
                return [];
            }
            cameraSelectWrapper.style.display = cameras.length > 1 ? 'block' : 'none';
            cameras.forEach((camera, idx) => {
                const option = document.createElement('option');
                option.value = camera.deviceId;
                option.text = camera.label || `Kamera ${idx + 1}`;
                cameraSelect.appendChild(option);
            });
            // Pilih kamera belakang jika ada
            let backCam = cameras.find(cam => cam.label && cam.label.toLowerCase().includes('back'));
            let defaultDeviceId = backCam ? backCam.deviceId : cameras[0].deviceId;
            cameraSelect.value = defaultDeviceId;
            return cameras;
        } catch (e) {
            noCameraMsg.style.display = 'block';
            return [];
        }
    }

    async function startCamera(deviceId = null) {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
        let constraints = {
            video: deviceId ? {
                deviceId: {
                    exact: deviceId
                }
            } : {
                facingMode: {
                    exact: 'environment'
                }
            }
        };
        try {
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            currentStream = stream;
            video.srcObject = stream;
            noCameraMsg.style.display = 'none';
        } catch (err) {
            // fallback ke kamera default jika environment tidak tersedia
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                currentStream = stream;
                video.srcObject = stream;
                noCameraMsg.style.display = 'none';
            } catch (err2) {
                noCameraMsg.style.display = 'block';
            }
        }
    }

    cameraSelect.addEventListener('change', function() {
        startCamera(this.value);
    });

    document.addEventListener('DOMContentLoaded', async function() {
        const cameras = await getCameras();
        if (cameras.length > 0) {
            setTimeout(() => startCamera(cameraSelect.value), 500);
        }
    });
    /* document.querySelector('form').addEventListener('submit', function(e) {
    let adaFoto = false;
    for (let i = 1; i <= 4; i++) {
        if (document.getElementById(`image_url_input_${i}`).value) {
            adaFoto = true;
            break;
        }
    }
    if (!adaFoto) {
        e.preventDefault();
        document.getElementById('error-message').innerText = 'Silakan ambil minimal 1 foto terlebih dahulu!';
    }
}); */



    let maxFoto = 4;

    // ====== Global helpers untuk crop/resize & utilitas ======
    const TARGET_ASPECT = 4 / 3; // 4:3 agar konsisten dengan preview 320x240
    const MAX_WIDTH = 1280; // batas lebar maksimal hasil
    const MAX_HEIGHT = 960; // batas tinggi maksimal hasil
    const JPEG_QUALITY = 0.85; // kualitas JPEG

    function loadImageFromDataUri(dataUri) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = dataUri;
        });
    }

    function loadImageFromFile(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = async e => {
                try {
                    const img = await loadImageFromDataUri(e.target.result);
                    resolve({
                        img,
                        dataUri: e.target.result
                    });
                } catch (err) {
                    reject(err);
                }
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    function cropResizeToDataURL(img, targetAspect = TARGET_ASPECT, maxWidth = MAX_WIDTH, maxHeight = MAX_HEIGHT, quality = JPEG_QUALITY) {
        const sw0 = img.width;
        const sh0 = img.height;
        if (!sw0 || !sh0) return null;
        const srcAR = sw0 / sh0;

        let sx = 0,
            sy = 0,
            sw = sw0,
            sh = sh0;
        if (srcAR > targetAspect) {
            sw = Math.round(sh0 * targetAspect);
            sx = Math.round((sw0 - sw) / 2);
        } else if (srcAR < targetAspect) {
            sh = Math.round(sw0 / targetAspect);
            sy = Math.round((sh0 - sh) / 2);
        }

        const scale = Math.min(maxWidth / sw, maxHeight / sh, 1);
        const dw = Math.max(1, Math.round(sw * scale));
        const dh = Math.max(1, Math.round(sh * scale));

        const c = document.createElement('canvas');
        c.width = dw;
        c.height = dh;
        const ctx = c.getContext('2d');
        ctx.drawImage(img, sx, sy, sw, sh, 0, 0, dw, dh);
        return c.toDataURL('image/jpeg', quality);
    }

    async function processFileToDataUri(file) {
        const {
            img
        } = await loadImageFromFile(file);
        return cropResizeToDataURL(img);
    }

    async function processDataUri(dataUri) {
        const img = await loadImageFromDataUri(dataUri);
        return cropResizeToDataURL(img);
    }

    // set foto ke slot tertentu (dipakai kamera & galeri)
    function setFotoKeSlot(slot, data_uri) {
        // simpan ke hidden input array agar dikirim ke backend
        let hiddenInputs = document.getElementById('fotoHiddenInputs');
        let input = document.getElementById(`foto_input_${slot}`);
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'foto[]';
            input.id = `foto_input_${slot}`;
            hiddenInputs.appendChild(input);
        }
        input.value = data_uri;

        // set juga ke input image_url_input_X agar backend lama tetap menerima
        let imageInput = document.getElementById(`image_url_input_${slot}`);
        if (imageInput) imageInput.value = data_uri;

        // update carousel item
        let item = document.getElementById(`carousel_item_${slot}`);
        if (item) {
            item.innerHTML = `
        <div class="d-flex flex-column align-items-center p-2 aksi-hapus">
            <img src="${data_uri}" class="d-block w-100 rounded mb-2 aset-img"/>
            <div class=\"carousel-caption\" style=\"bottom: 60px;\">\
                <span class=\"badge bg-dark\">Foto ${slot} dari 4</span>\
            </div>\
            <button type=\"button\" class=\"btn btn-danger btn-sm mt-2\" onclick=\"hapusFoto(${slot})\">\
                <i class=\"fas fa-trash\"></i> Hapus\
            </button>\
        </div>`;
        }
    }

    // ambil foto
    async function take_snapshot() {
        // cari slot kosong
        let slot = null;
        for (let i = 1; i <= maxFoto; i++) {
            if (!document.getElementById(`image_url_input_${i}`).value) {
                slot = i;
                break;
            }
        }
        if (!slot) {
            document.getElementById('error-message').innerText = 'Maksimal 4 foto!';
            return;
        }

        document.getElementById('error-message').innerText = '';
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        const raw_data_uri = canvas.toDataURL('image/jpeg');
        try {
            const data_uri = await processDataUri(raw_data_uri);
            setFotoKeSlot(slot, data_uri);
        } catch (err) {
            console.error('Gagal memproses gambar kamera:', err);
            document.getElementById('error-message').innerText = 'Gagal memproses gambar kamera.';
        }
    }

    // hapus foto
    function hapusFoto(idx) {
        // hapus hidden input
        let input = document.getElementById(`foto_input_${idx}`);
        if (input) input.remove();
        // kosongkan juga input image_url_input_X
        let imageInput = document.getElementById(`image_url_input_${idx}`);
        if (imageInput) imageInput.value = '';

        // reset tampilan slot
        let item = document.getElementById(`carousel_item_${idx}`);
        item.innerHTML = `
        <div class=\"d-flex flex-column align-items-center p-2 border rounded\" style=\"min-height:240px;\">\
            <img src=\"${placeholderPath}\" class=\"d-block w-100 rounded mb-2 placeholder-img aset-img\" alt=\"Placeholder Foto ${idx}\">\
            <div class=\"carousel-caption\" style=\"bottom: 60px;\">\
                <span class=\"badge bg-dark\">Foto ${idx} dari 4</span>\
            </div>\
        </div>\
    `;
    }

    // pilih dari galeri
    const galleryInput = document.getElementById('galleryInput');
    if (galleryInput) {
        galleryInput.addEventListener('change', async function(e) {
            const files = Array.from(e.target.files || []);
            if (!files.length) return;

            // cari slot kosong yang tersedia
            let slotsKosong = [];
            for (let i = 1; i <= maxFoto; i++) {
                if (!document.getElementById(`image_url_input_${i}`).value) {
                    slotsKosong.push(i);
                }
            }
            if (slotsKosong.length === 0) {
                document.getElementById('error-message').innerText = 'Maksimal 4 foto!';
                galleryInput.value = '';
                return;
            }

            document.getElementById('error-message').innerText = '';

            // batasi jumlah file sesuai slot kosong (proses berurutan agar urutan slot konsisten)
            const filesToUse = files.slice(0, slotsKosong.length);
            for (let idx = 0; idx < filesToUse.length; idx++) {
                try {
                    const file = filesToUse[idx];
                    const data_uri = await processFileToDataUri(file);
                    const slot = slotsKosong[idx];
                    setFotoKeSlot(slot, data_uri);
                } catch (err) {
                    console.error('Gagal memproses gambar galeri:', err);
                    document.getElementById('error-message').innerText = 'Gagal memproses gambar dari galeri.';
                }
            }

            // reset input agar bisa pilih file yang sama lagi kalau perlu
            galleryInput.value = '';
        });
    }

    // validasi submit
    document.getElementById('form-aset').addEventListener('submit', function(e) {
        let errorMsg = '';

        // Validasi Ruangan
        if (!document.getElementById('code_ruang').value.trim()) {
            errorMsg += '- Ruangan harus dipilih!\n';
        }

        // Validasi minimal 1 foto (kamera atau galeri)
        let adaFoto = false;
        for (let i = 1; i <= maxFoto; i++) {
            let imageUrl = document.getElementById(`image_url_input_${i}`);
            if (imageUrl && imageUrl.value) {
                adaFoto = true;
                break;
            }
        }
        if (!adaFoto) {
            errorMsg += '- Minimal 1 foto harus diambil!\n';
        }

        // Validasi field wajib
        const wajib = [{
                id: 'nama_brg',
                label: 'Nama Barang'
            },
            {
                id: 'merk',
                label: 'Merk'
            },
            {
                id: 'bahan',
                label: 'Bahan'
            },
            {
                id: 'periode',
                label: 'Tahun Perolehan'
            },
            {
                id: 'harga',
                label: 'Harga Beli'
            }
        ];
        wajib.forEach(field => {
            let el = document.getElementById(field.id);
            if (el && !el.value.trim()) {
                errorMsg += `- ${field.label} harus diisi!\n`;
            }
        });

        // Validasi select lain
        if (!document.getElementById('st_aset').value.trim()) {
            errorMsg += '- Status harus dipilih!\n';
        }
        if (!document.getElementById('kondisi').value.trim()) {
            errorMsg += '- Kondisi harus dipilih!\n';
        }
        if (!document.getElementById('code_kategori').value.trim()) {
            errorMsg += '- Jenis Barang harus dipilih!\n';
        }

        // === DARK MODE THEME SETUP ===
        const isDarkMode = document.body.classList.contains('dark-mode');
        const swalTheme = {
            background: isDarkMode ? "#1e1e2f" : "#fff",
            color: isDarkMode ? "#f1f1f1" : "#000",
            confirmButtonColor: isDarkMode ? "#0d6efd" : "#3085d6",
            iconColor: isDarkMode ? "#f27474" : "#f27474"
        };

        // Jika ada error, tampilkan SweetAlert dengan tema sesuai mode
        if (errorMsg) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: errorMsg.replace(/\n/g, '<br>'),
                background: swalTheme.background,
                color: swalTheme.color,
                confirmButtonColor: swalTheme.confirmButtonColor,
                iconColor: swalTheme.iconColor
            });
        }
    });
</script>
@endpush

@push('styles')
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

    /* Sembunyikan default Bootstrap background */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        display: none;
    }

    /* Style Font Awesome icon */
    .custom-arrow {
        font-size: 14px;
        /* lebih kecil (default 20px → 14px) */
        color: black;
        background: white;
        padding: 8px;
        /* ruang dalam lebih kecil */
        border-radius: 6px;
        /* kotak rounded */
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
    }

    .carousel-control-prev,
    .carousel-control-next {
        top: 50%;
        transform: translateY(-50%);
        bottom: auto;
        /* supaya tidak ikut full tinggi */
    }

    .placeholder-img {
        max-height: 240px;
        object-fit: contain;
        background: #f8f9fa;
    }

    /* Samakan ukuran foto & placeholder */
    .aset-img {
        height: 250px;
        max-height: 250px;
        width: 100%;
        object-fit: contain;
        background: #f8f9fa;
    }

    /* Atur posisi caption dan indikator agar tidak menabrak tombol Hapus */
    #fotoCarousel .carousel-caption {
        bottom: 60px !important;
        /* Geser caption sedikit ke atas */
    }

    #fotoCarousel .carousel-indicators {
        bottom: 40px !important;
        /* Letakkan indikator tepat di bawah caption */
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @media screen and (min-width: 992px) {
        #modalRuang .table-responsive {
            overflow-x: auto;
            /* scroll horizontal jika perlu */
        }

        #modalRuang #myTable {
            table-layout: auto;
            /* kolom menyesuaikan konten */
            width: 100%;
        }

        /* Kolom No */
        #modalRuang #myTable th:nth-child(1),
        #modalRuang #myTable td:nth-child(1) {
            min-width: 5px;
            /* cukup untuk nomor */
            width: 2px;
            text-align: center;
            white-space: nowrap;
        }

        /* Kolom Kode Ruangan */
        #modalRuang #myTable th:nth-child(2),
        #modalRuang #myTable td:nth-child(2) {
            min-width: 10px;
            width: 10px;
            white-space: nowrap;
            text-align: center;
        }

        /* Kolom Nama Ruang */
        #modalRuang #myTable th:nth-child(3),
        #modalRuang #myTable td:nth-child(3) {
            min-width: 250px;
            width: auto;
            /* fleksibel */
            white-space: normal;
            /* bisa melipat teks */
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }

        /* Kolom Aksi */
        #modalRuang #myTable th:nth-child(4),
        #modalRuang #myTable td:nth-child(4) {
            min-width: 5px;
            width: 5px;
            text-align: center;
            white-space: nowrap;
        }
    }

    /* Agar tabel tidak auto lebar otomatis */

    /* Responsif manual tanpa opsi responsive:true */
    @media (max-width: 768px) {
        #myTable {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    /* Nonaktifkan pointer/ikon sort untuk kolom tertentu (misal kolom 6 dan 7) */
    #myTable th:nth-child(7),
    #myTable th:nth-child(8) {
        pointer-events: none;
        background-image: none !important;
    }

    #myTable {
        width: 100% !important;
        table-layout: fixed;
        /* mirip efek autoWidth: false */
    }

    /* Supaya tabel bisa di-scroll horizontal */
    .dataTables_wrapper {
        overflow-x: auto;
    }


    @media (max-width: 576px) {

        #results,
        .border.p-3 {
            max-width: 100% !important;
            min-width: 0 !important;
        }

        video,
        canvas {
            width: 100% !important;
            height: auto !important;
        }

        #judul-aset {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-asset .fa-plus-square {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }

        .col-md-3,
        .col-md-4,
        .col-md-8,
        .col-md-12,
        .col-md-6,
        .col-md-2,
        .col-md-1,
        .col-md-5,
        .col-md-7,
        .col-md-9,
        .col-md-10,
        .col-md-11 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endpush