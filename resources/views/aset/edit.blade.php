@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-edit me-3"></i> Edit Aset Inventaris
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-edit me-2"></i> Edit Aset Inventaris
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('aset.index') }}" class="btn btn-primary btn-sm "><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>

    <div class="section-body mb-5">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('aset.update', $aset->id_aset) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-12 col-md-8">
                            <div class="row">
                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label">Status</label>
                                    @if($aset->st_aset === 'PDH')
                                    <input type="text" class="form-control" value="Pindah" readonly>
                                    @else
                                    <div class="position-relative">
                                        <select name="st_aset" class="form-control" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="BL" {{ old('st_aset', $aset->st_aset) == 'BL' ? 'selected' : '' }}>Beli</option>
                                            <option value="HBH" {{ old('st_aset', $aset->st_aset) == 'HBH' ? 'selected' : '' }}>Hibah</option>
                                        </select>
                                        <i class="fas fa-chevron-down position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;">
                                        </i>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label">Tanggal Input</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', $aset->date->format('Y-m-d')) }}" readonly style="background-color: #f8f9fa;">
                                </div>
                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label">Tahun Perolehan</label>
                                    <input type="date" name="periode" class="form-control" value="{{ old('periode', $aset->periode->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_brg" class="form-control" value="{{ old('nama_brg', $aset->nama_brg) }}" required>
                                </div>
                                <div class="col-12 col-md-2 mb-3">
                                    <label for="jumlah_brg" class="form-label">Jumlah</label>
                                    <div class="input-group position-relative">
                                        <input type="number" name="jumlah_brg" id="jumlah_brg" class="form-control " value="{{ old('jumlah_brg', 1) }}" readonly style="background-color: #f8f9fa;">
                                        <div class="position-relative" style="flex: 0 0 auto;">
                                            <select name="satuan" id="satuan" class="form-control btn-satuan-blue" style="max-width: 100px; border-radius: 0 6px 6px 0; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                <option value="">Satuan</option>
                                                <option value="unit" {{ old('satuan', $aset->satuan ?? '') == 'unit' ? 'selected' : '' }}>Unit</option>
                                                <option value="buah" {{ old('satuan', $aset->satuan ?? '') == 'buah' ? 'selected' : '' }}>Buah</option>
                                                <option value="set" {{ old('satuan', $aset->satuan ?? '') == 'set' ? 'selected' : '' }}>Set</option>
                                                <option value="lbr" {{ old('satuan', $aset->satuan ?? '') == 'lbr' ? 'selected' : '' }}>Lembar</option>
                                                <option value="lsn" {{ old('satuan', $aset->satuan ?? '') == 'lsn' ? 'selected' : '' }}>Lusin</option>
                                                <option value="roll" {{ old('satuan', $aset->satuan ?? '') == 'roll' ? 'selected' : '' }}>Roll</option>
                                                <option value="psng" {{ old('satuan', $aset->satuan ?? '') == 'psng' ? 'selected' : '' }}>Pasang</option>
                                            </select>
                                            <i class="fas fa-chevron-down position-absolute" style="right: 8px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #fff;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Merk</label>
                                    <input type="text" name="merk" class="form-control" value="{{ old('merk', $aset->merk) }}" required>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">No. Seri</label>
                                    <input type="text" name="seri" class="form-control" value="{{ old('seri', $aset->seri) }}">
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Ukuran</label>
                                    <div class="input-group">
                                        <input type="text" name="ukuran" class="form-control" value="{{ old('ukuran', $aset->ukuran) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Bahan</label>
                                    <input type="text" name="bahan" class="form-control" value="{{ old('bahan', $aset->bahan) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">Harga Beli</label>
                                    <input type="text" step="0.01" min="0" name="harga" id="harga" class="form-control" value="{{ old('harga', $aset->harga) }}" required>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">Kondisi</label>
                                    <div class="position-relative">
                                        <select name="kondisi" class="form-control" required>
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="Baik" {{ old('kondisi', $aset->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="Kurang Baik" {{ old('kondisi', $aset->kondisi) == 'Kurang Baik' ? 'selected' : '' }}>Kurang Baik</option>
                                            <option value="Rusak Berat" {{ old('kondisi', $aset->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        </select>
                                        <i class="fas fa-chevron-down position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;">
                                        </i>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">Jenis Barang</label>
                                    <div class="position-relative">
                                        <select name="code_kategori" class="form-control" required>
                                            <option value="">-- Pilih Jenis Barang --</option>
                                            @foreach($kategori as $kat)
                                            <option value="{{ $kat->kode }}" {{ old('code_kategori', $aset->code_kategori) == $kat->kode ? 'selected' : '' }}>
                                                {{ $kat->kode }} - {{ $kat->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;">
                                        </i>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="code_ruang" class="form-label">Ruangan</label>
                                <div class="input-group">
                                    <input type="text" id="ruang_nama" class="form-control" placeholder="Pilih ruangan" onkeydown="return false;">
                                    <input type="hidden" name="code_ruang" id="code_ruang">
                                    <button type="button" class="btn btn-gradient-blue" data-bs-toggle="modal" data-bs-target="#modalRuang">Pilih</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $aset->keterangan) }}</textarea>
                            </div>

                            <!-- Ambil Foto Baru -->
                            <div class="mb-3 d-flex flex-column align-items-center aksi-filter aksi-galeri">
                                <div class="border border-secondary rounded p-3 d-flex flex-column align-items-center w-100 mb-3" style="max-width: 370px;">
                                    <h6 class="mb-2">Ambil Foto Baru (Opsional)</h6>
                                    <div id="cameraSelectWrapper" class="w-100 mb-2" style="display:none;">
                                        <label for="cameraSelect" class="form-label">Pilih Kamera</label>
                                        <select id="cameraSelect" class="form-select"></select>
                                    </div>
                                    <div id="my_camera" class="mb-2 w-100" style="max-width:320px; height:auto;" autoplay playsinline></div>
                                    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                                    <button type="button" class="btn btn-info btn-sm mb-2" onclick="take_snapshot()">
                                        <i class="fas fa-camera-retro me-1"></i> Ambil Foto
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm mb-2" onclick="document.getElementById('galleryInputEdit').click()">
                                        <i class="fas fa-image me-1"></i> Ambil Galeri
                                    </button>
                                    <input type="file" id="galleryInputEdit" accept="image/*" multiple style="display:none;" />
                                    <input type="hidden" name="image_url_1" id="image_url_input_1">
                                    <input type="hidden" name="image_url_2" id="image_url_input_2">
                                    <input type="hidden" name="image_url_3" id="image_url_input_3">
                                    <input type="hidden" name="image_url_4" id="image_url_input_4">
                                    <div id="error-message" class="text-danger text-center mt-2"></div>
                                    <div id="no-camera-message" class="text-danger text-center mt-2" style="display:none;">Kamera tidak tersedia atau tidak diizinkan.</div>
                                </div>
                            </div>
                        </div>
                        <!-- Kolom Kanan -->
                        <div class="col-12 col-md-4 d-flex flex-column align-items-center">
                            <label class="form-label">Foto Aset Saat Ini</label>
                            <div id="fotoCarousel" class="carousel slide w-100" data-bs-ride="carousel" style="max-width:320px;">
                                <div class="carousel-inner" id="carouselInner">
                                    @php
                                    // $aset->foto dicast sebagai array di model; handle aman jika string JSON
                                    if (is_array($aset->foto)) {
                                    $fotos = array_slice($aset->foto, -4);
                                    } elseif (is_string($aset->foto) && !empty($aset->foto)) {
                                    $decoded = json_decode($aset->foto, true);
                                    $fotos = is_array($decoded) ? array_slice($decoded, -4) : [$aset->foto];
                                    } else {
                                    $fotos = [];
                                    }
                                    @endphp

                                    @for($i = 1; $i <= 4; $i++)
                                        <div class="carousel-item {{ $i == 1 ? 'active' : '' }}" id="carousel_item_{{ $i }}">
                                        <div class="d-flex flex-column align-items-center p-2 border rounded aksi-hapus" style="min-height:240px;">

                                            {{-- Foto lama jika ada --}}
                                            @if(isset($fotos[$i-1]) && $fotos[$i-1])
                                            <img id="foto_lama_{{ $i }}"
                                                src="{{ asset('storage/' . $fotos[$i-1]) }}"
                                                class="img-fluid mb-2 aset-img"
                                                loading="lazy">
                                            <input type="hidden" name="foto_lama_{{ $i }}" value="{{ asset('storage/' . $fotos[$i-1]) }}">
                                            @else
                                            <img src="{{ asset('assets/img/aset-placeholder.png') }}" class="img-fluid mb-2 placeholder-img aset-img" alt="Placeholder Foto {{ $i }}">
                                            <img id="foto_lama_{{ $i }}" style="display:none;">
                                            <input type="hidden" name="foto_lama_{{ $i }}" value="">
                                            @endif

                                            <div id="results_{{ $i }}" style="text-align: center;"></div>

                                            <button type="button" id="hapus_btn_{{ $i }}"
                                                class="btn btn-danger btn-sm mt-2 {{ isset($fotos[$i-1]) ? '' : 'd-none' }}"
                                                onclick="hapusFoto('{{ $i }}')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>

                                            <!-- Tambahkan caption di bawah -->
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
</div>

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
                            <tr @if(old('code_ruang', $aset->code_ruang) == $r->code) style="background-color: #d1e7dd;" @endif>
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


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    // ====== Global helpers untuk crop/resize & utilitas slot ======
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

        // Hitung area crop agar sesuai aspect ratio target
        let sx = 0,
            sy = 0,
            sw = sw0,
            sh = sh0;
        if (srcAR > targetAspect) {
            // gambar terlalu lebar → crop sisi kiri/kanan
            sw = Math.round(sh0 * targetAspect);
            sx = Math.round((sw0 - sw) / 2);
        } else if (srcAR < targetAspect) {
            // gambar terlalu tinggi → crop atas/bawah
            sh = Math.round(sw0 / targetAspect);
            sy = Math.round((sh0 - sh) / 2);
        }

        // Skala ke dalam batas maksimum tanpa upscaling
        const scale = Math.min(maxWidth / sw, maxHeight / sh, 1);
        const dw = Math.max(1, Math.round(sw * scale));
        const dh = Math.max(1, Math.round(sh * scale));

        const canvas = document.createElement('canvas');
        canvas.width = dw;
        canvas.height = dh;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, sx, sy, sw, sh, 0, 0, dw, dh);
        return canvas.toDataURL('image/jpeg', quality);
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

    function setFotoBaruKeSlot(slotIndex, data_uri) {
        // Tampilkan hasil
        const resultsEl = document.getElementById('results_' + slotIndex);
        if (resultsEl) {
            resultsEl.innerHTML = `<h6>Hasil Foto (Slot ${slotIndex})</h6>\n             <img src="${data_uri}" class="img-thumbnail mb-2 aset-img"/>`;
        }
        // Simpan ke hidden input
        const inputHidden = document.getElementById('image_url_input_' + slotIndex);
        if (inputHidden) inputHidden.value = data_uri;

        // Tampilkan tombol hapus
        const hapusBtn = document.getElementById('hapus_btn_' + slotIndex);
        if (hapusBtn) hapusBtn.classList.remove('d-none');

        // Sembunyikan foto lama dan kosongkan nilai lama
        const fotoLamaImg = document.getElementById('foto_lama_' + slotIndex);
        if (fotoLamaImg) fotoLamaImg.style.display = 'none';
        const fotoLamaInput = document.querySelector('input[name="foto_lama_' + slotIndex + '"]');
        if (fotoLamaInput) fotoLamaInput.value = '';

        // Sembunyikan placeholder pada slot ini jika ada
        const itemContainer = document.getElementById('carousel_item_' + slotIndex);
        if (itemContainer) {
            const ph = itemContainer.querySelector('img.placeholder-img');
            if (ph) ph.style.display = 'none';
        }
    }

    function findNextSlotIndex() {
        const maxFoto = 4;
        let slotIndex = null;

        // 1) Cari slot placeholder (tanpa foto baru dan tanpa foto lama)
        for (let i = 1; i <= maxFoto; i++) {
            const input = document.getElementById('image_url_input_' + i);
            const fotoLamaInput = document.querySelector('input[name="foto_lama_' + i + '"]');
            const hasNewPhoto = input && input.value !== '';
            const hasOldPhotoVal = fotoLamaInput && fotoLamaInput.value !== '';
            if (!hasNewPhoto && !hasOldPhotoVal) {
                slotIndex = i;
                break;
            }
        }

        // 2) Jika tidak ada placeholder, ganti foto lama yang belum diganti
        if (!slotIndex) {
            for (let i = 1; i <= maxFoto; i++) {
                const input = document.getElementById('image_url_input_' + i);
                const fotoLamaImg = document.getElementById('foto_lama_' + i);
                const fotoLamaInput = document.querySelector('input[name="foto_lama_' + i + '"]');
                const hasNewPhoto = input && input.value !== '';
                const hasOldPhoto = fotoLamaImg && fotoLamaImg.src && !fotoLamaImg.src.includes('data:image') && fotoLamaInput && fotoLamaInput.value !== '';
                if (!hasNewPhoto && hasOldPhoto) {
                    slotIndex = i;
                    break;
                }
            }
        }

        // 3) Jika penuh, round-robin
        if (!slotIndex) {
            window.lastReplacedSlot = (window.lastReplacedSlot || 0) % maxFoto + 1;
            slotIndex = window.lastReplacedSlot;
        }
        return slotIndex;
    }

    // Pointer round-robin untuk menimpa foto saat semua slot terisi
    let nextReplaceIndex = 1;
    const placeholderPath = "{{ asset('assets/img/aset-placeholder.png') }}";
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi foto lama ke hidden input
        setTimeout(() => {
            initializeExistingPhotos();
        }, 100);

        // Fungsi untuk menginisialisasi foto lama
        function initializeExistingPhotos() {
            const maxFoto = 4;
            for (let i = 1; i <= maxFoto; i++) {
                const fotoLamaImg = document.getElementById('foto_lama_' + i);
                const fotoLamaInput = document.querySelector('input[name="foto_lama_' + i + '"]');

                if (fotoLamaImg && fotoLamaImg.src && !fotoLamaImg.src.includes('data:image')) {
                    // Ada foto lama, pastikan input terisi
                    if (fotoLamaInput) {
                        fotoLamaInput.value = fotoLamaImg.src;
                    }
                }
            }
        }

        // Kamera & Webcam
        const cameraSelect = document.getElementById('cameraSelect');
        const cameraSelectWrapper = document.getElementById('cameraSelectWrapper');
        const noCameraMsg = document.getElementById('no-camera-message');
        let cameras = [];
        let currentDeviceId = null;

        async function setupCameras() {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                    noCameraMsg.style.display = 'block';
                    return;
                }
                const devices = await navigator.mediaDevices.enumerateDevices();
                cameras = devices.filter(device => device.kind === 'videoinput');
                cameraSelect.innerHTML = '';
                if (cameras.length === 0) {
                    noCameraMsg.style.display = 'block';
                    return;
                }
                // Sembunyikan dropdown jika hanya satu kamera
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
                attachWebcam(defaultDeviceId);
            } catch (err) {
                noCameraMsg.style.display = 'block';
            }
        }

        function attachWebcam(deviceId) {
            Webcam.reset();
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90,
                constraints: {
                    deviceId: deviceId ? {
                        exact: deviceId
                    } : undefined,
                    facingMode: 'environment'
                }
            });
            Webcam.attach('#my_camera');
        }

        cameraSelect.addEventListener('change', function() {
            currentDeviceId = this.value;
            attachWebcam(currentDeviceId);
        });

        setupCameras();

        // Handler pilih dari galeri
        const galleryInputEdit = document.getElementById('galleryInputEdit');
        if (galleryInputEdit) {
            galleryInputEdit.addEventListener('change', async function() {
                const files = Array.from(galleryInputEdit.files || []);
                if (!files.length) return;
                document.getElementById('error-message').innerText = '';

                for (const file of files) {
                    try {
                        const dataUri = await processFileToDataUri(file);
                        const slotIndex = findNextSlotIndex();
                        setFotoBaruKeSlot(slotIndex, dataUri);
                        updateSlotStatus();
                    } catch (err) {
                        console.error('Gagal memproses gambar:', err);
                        document.getElementById('error-message').innerText = 'Gagal memproses gambar dari galeri.';
                    }
                }

                // reset agar bisa pilih file yang sama lagi
                galleryInputEdit.value = '';
            });
        }

        // Tombol simpan
        const btnSimpan = document.querySelector('button[type="submit"]');
        btnSimpan.disabled = false;

        // Update status slot setelah inisialisasi
        setTimeout(() => {
            updateSlotStatus();
        }, 100);

        // Data ruang dari PHP ke JS
        const ruangList = JSON.parse(`{!! addslashes(json_encode($ruang)) !!}`);
        const selectedCode = "{{ old('code_ruang', $aset->code_ruang) }}";
        const selectedRuang = ruangList.find(r => r.code === selectedCode);

        if (selectedRuang) {
            document.getElementById('ruang_nama').value = selectedRuang.name;
            document.getElementById('code_ruang').value = selectedRuang.code;
        } else {
            document.getElementById('ruang_nama').value = '';
            document.getElementById('code_ruang').value = '';
        }

    });

    // (fungsi updateSlotStatus yang lengkap ada di bawah)

    // (fungsi take_snapshot global berada di bawah dan sudah memproses crop/resize)

    // simpan slot terakhir yang ditimpa
    let lastReplacedSlot = 0;

    function take_snapshot() {
        Webcam.snap(async function(raw_data_uri) {
            try {
                const data_uri = await processDataUri(raw_data_uri);
                const slotIndex = findNextSlotIndex();
                setFotoBaruKeSlot(slotIndex, data_uri);
                updateSlotStatus();
            } catch (err) {
                console.error('Gagal memproses gambar kamera:', err);
                const el = document.getElementById('error-message');
                if (el) el.innerText = 'Gagal memproses gambar kamera.';
            }
        });
    }

    function hapusFoto(slotIndex) {
        const maxFoto = 4;

        // Hapus foto dari slot yang dipilih
        let currentInput = document.getElementById('image_url_input_' + slotIndex);
        let currentResult = document.getElementById('results_' + slotIndex);
        let currentFotoLamaInput = document.querySelector('input[name="foto_lama_' + slotIndex + '"]');
        let currentFotoLamaImg = document.getElementById('foto_lama_' + slotIndex);

        // Kosongkan slot yang dipilih
        if (currentInput) currentInput.value = "";
        if (currentResult) currentResult.innerHTML = `<img src="${placeholderPath}" class="img-thumbnail mb-2 placeholder-img aset-img" alt="Placeholder Foto ${slotIndex}"/>`;
        if (currentFotoLamaInput) currentFotoLamaInput.value = "";
        if (currentFotoLamaImg) currentFotoLamaImg.style.display = "none";

        // Geser foto dari slot berikutnya ke slot yang kosong
        for (let i = slotIndex; i < maxFoto; i++) {
            let currInput = document.getElementById('image_url_input_' + i);
            let nextInput = document.getElementById('image_url_input_' + (i + 1));
            let currResult = document.getElementById('results_' + i);
            let nextResult = document.getElementById('results_' + (i + 1));
            let currFotoLamaInput = document.querySelector('input[name="foto_lama_' + i + '"]');
            let nextFotoLamaInput = document.querySelector('input[name="foto_lama_' + (i + 1) + '"]');
            let currFotoLamaImg = document.getElementById('foto_lama_' + i);
            let nextFotoLamaImg = document.getElementById('foto_lama_' + (i + 1));

            // Geser foto baru
            if (currInput && nextInput) {
                currInput.value = nextInput.value;
            }
            if (currResult && nextResult) {
                if (nextInput && nextInput.value === "" && (!nextFotoLamaInput || nextFotoLamaInput.value === "")) {
                    currResult.innerHTML = `<img src="${placeholderPath}" class="img-thumbnail mb-2 placeholder-img aset-img" alt="Placeholder Foto ${i}"/>`;
                } else {
                    currResult.innerHTML = nextResult.innerHTML;
                }
            }

            // Geser foto lama
            if (currFotoLamaInput && nextFotoLamaInput) {
                currFotoLamaInput.value = nextFotoLamaInput.value;
            }
            if (currFotoLamaImg && nextFotoLamaImg) {
                currFotoLamaImg.style.display = nextFotoLamaImg.style.display;
                currFotoLamaImg.src = nextFotoLamaImg.src;
            }
        }

        // Kosongkan slot terakhir
        let lastInput = document.getElementById('image_url_input_' + maxFoto);
        let lastResult = document.getElementById('results_' + maxFoto);
        let lastFotoLamaInput = document.querySelector('input[name="foto_lama_' + maxFoto + '"]');
        let lastFotoLamaImg = document.getElementById('foto_lama_' + maxFoto);

        if (lastInput) lastInput.value = "";
        if (lastResult) lastResult.innerHTML = `<img src="${placeholderPath}" class="img-thumbnail mb-2 placeholder-img aset-img" alt="Placeholder Foto ${maxFoto}"/>`;
        if (lastFotoLamaInput) lastFotoLamaInput.value = "";
        if (lastFotoLamaImg) lastFotoLamaImg.style.display = "none";

        updateSlotStatus();
    }

    function updateSlotStatus() {
        const maxFoto = 4;
        for (let i = 1; i <= maxFoto; i++) {
            let input = document.getElementById('image_url_input_' + i);
            let fotoLamaImg = document.getElementById('foto_lama_' + i);
            let fotoLamaInput = document.querySelector('input[name="foto_lama_' + i + '"]');
            let hapusBtn = document.getElementById('hapus_btn_' + i);

            // Cek apakah slot terisi (foto baru atau foto lama)
            let hasNewPhoto = input && input.value !== "";
            let hasOldPhoto = fotoLamaImg && fotoLamaImg.src && !fotoLamaImg.src.includes('data:image') && fotoLamaInput && fotoLamaInput.value !== "";
            let isSlotOccupied = hasNewPhoto || hasOldPhoto;

            if (hapusBtn) {
                if (isSlotOccupied) {
                    hapusBtn.classList.remove("d-none");
                } else {
                    hapusBtn.classList.add("d-none");
                }
            }
        }
    }

    // DataTable untuk modal ruangan
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

    .custom-caption {
        bottom: 10px;
        /* jarak dari bawah (naikkan nilainya kalau mau lebih ke atas) */
        background: rgba(0, 0, 0, 0.6);
        /* kotak hitam transparan */
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-block;
        /* supaya kotaknya sesuai panjang teks */
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

    /* Samakan ukuran foto & placeholder pada halaman edit */
    .aset-img {
        height: 250px;
        max-height: 250px;
        width: 100%;
        object-fit: contain;
        background: #f8f9fa;
    }

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Pastikan konten video/canvas tidak keluar dari border kamera */
    #my_camera {
        overflow: hidden;
        /* cegah konten keluar border */
        box-sizing: border-box;
        /* hitung border ke dalam lebar */
        border-radius: .375rem;
        /* samakan dengan .rounded Bootstrap */
        clip-path: inset(0 round .375rem);
        line-height: 0;
        /* hilangkan celah baseline */
        padding: 0 !important;
        /* jangan ada padding di dalam */
    }

    /* Wrapper box kamera (dengan .border.p-3) juga ikut clip */
    .mb-3.d-flex.flex-column.align-items-center>.border.p-3 {
        overflow: hidden;
        border-radius: .375rem;
    }

    #my_camera>* {
        display: block;
        width: 100% !important;
        height: auto !important;
        border-radius: .375rem;
    }

    #my_camera video,
    #my_camera canvas,
    #my_camera object,
    #my_camera embed {
        display: block;
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        object-fit: contain;
    }

    .webcamjs-container {
        /* elemen pembungkus yang disuntik WebcamJS */
        width: 100% !important;
        height: auto !important;
        border-radius: .375rem !important;
        overflow: hidden !important;
        clip-path: inset(0 round .375rem);
        line-height: 0;
        /* hilangkan celah baseline */
        padding: 0 !important;
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

    /* Responsif smartphone: samakan dengan halaman create */
    @media (max-width: 576px) {

        /* Batasi box kamera seperti halaman create (max 340px) dan center */
        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 {
            width: 100%;
            max-width: 340px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Video/Img WebcamJS responsif & dibatasi max 320px, center */
        #my_camera {
            width: 100% !important;
            max-width: 320px;
            margin-left: auto;
            margin-right: auto;
            border: none !important;
            /* hilangkan border dalam agar tidak keluar */
        }

        #my_camera video,
        #my_camera canvas,
        #my_camera img {
            width: 100% !important;
            height: auto !important;
            object-fit: contain;
            border: none !important;
            /* jangan tampilkan border pada child */
        }

        #judul-aset {
            /* Mengurangi ukuran font untuk teks judul di HP */
            font-size: 1.5rem !important;
            /* Ganti angka 1.5rem sesuai kebutuhan Anda */
        }

        #judul-aset .fa-edit {
            /* Mengurangi ukuran ikon di HP */
            font-size: 1.2rem !important;
            /* Ganti angka 1.2rem sesuai kebutuhan Anda */
        }

        /* Perkecil jarak antara kamera dan tombol */
        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 #my_camera {
            margin-bottom: 0 !important;
        }

        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 {
            padding-bottom: .75rem !important;
        }

        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 #canvas {
            margin: 0 !important;
        }

        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 input.btn-primary {
            margin-top: 0 !important;
        }

        /* Samakan lebar kedua tombol kamera pada mobile */
        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 input[type="button"] {
            width: 100% !important;
            display: block;
        }

        /* Ikuti perilaku create: semua kolom md menjadi 100% di mobile */
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