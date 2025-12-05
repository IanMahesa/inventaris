@extends('layout.backend')

@section('content')
@include('layout.alert')

<div class="container-fluid">

    {{-- Versi BESAR (Desktop/Tablet) --}}
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-edit me-3"></i> Edit Lelang Aset Inventaris
        </span>
    </h2>
    {{-- Versi KECIL (Ponsel/Mobile) --}}
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-edit me-2"></i> Edit Lelang Aset Inventaris
        </span>
    </h5>

    <hr class="section-divider">

    <div class="d-flex justify-content-between align-items-center mb-3 ms-4 aksi-back">
        <a href="{{ route('opnamhistori.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="section-body mb-5">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('aset.histori.updatelelang', $aset->id_aset) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-12 col-md-8">
                            <div class="row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Status Histori</label>
                                    <input type="hidden" name="st_histori" value="LLG">
                                    <input type="text" class="form-control" value="Lelang" readonly>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Tanggal Sebelum</label>
                                    <input type="date" name="tanggal_sblm" class="form-control"
                                        value="{{ old('tanggal_sblm', $aset->date ? \Carbon\Carbon::parse($aset->date)->format('Y-m-d') : '') }}" readonly>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Tanggal Input</label>
                                    <input type="date" name="tanggal" class="form-control"
                                        value="{{ now()->format('Y-m-d') }}" readonly>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Tahun Perolehan</label>
                                    <input type="date" name="th_oleh" class="form-control"
                                        value="{{ old('th_oleh', $aset->periode ? $aset->periode->format('Y-m-d') : '') }}" readonly required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="name_brg" class="form-control"
                                        value="{{ old('name_brg', $aset->nama_brg) }}" readonly required>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Merk</label>
                                    <input type="text" name="hmerk" class="form-control"
                                        value="{{ old('hmerk', $aset->merk ?? '') }}" readonly required>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">No. Seri</label>
                                    <input type="text" name="hseri" class="form-control"
                                        value="{{ old('hseri', $aset->seri ?? '') }}" readonly required>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Ukuran</label>
                                    <input type="text" step="0.01" name="hsize" class="form-control"
                                        value="{{ old('hsize', $aset->ukuran ?? 0) }}" readonly required>
                                </div>
                            </div>

                            <div class="row">
                                <input type="hidden" name="hjum_brg" value="1">
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Bahan</label>
                                    <input type="text" name="hbahan" class="form-control"
                                        value="{{ old('hbahan', $aset->bahan ?? '') }}" readonly required>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Harga Beli</label>
                                    @php
                                    $hargaRaw = old('hprice', $aset->harga ?? 0);
                                    $hargaClean = str_replace([' ', ','], ['', '.'], $hargaRaw);
                                    $hargaNum = is_numeric($hargaClean) ? (float) $hargaClean : 0;
                                    @endphp
                                    <input type="text" step="0.01" name="hprice" class="form-control"
                                        value="{{ number_format($hargaNum, 0, ',', '.') }}" readonly required>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Kondisi</label>
                                    <div class="position-relative">
                                        <select name="hkondisi" class="form-control" required>
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="Baik" {{ old('hkondisi', $aset->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="Kurang Baik" {{ old('hkondisi', $aset->kondisi) == 'Kurang Baik' ? 'selected' : '' }}>Kurang Baik</option>
                                            <option value="Rusak Berat" {{ old('hkondisi', $aset->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        </select>
                                        <i class="fas fa-chevron-down position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;">
                                        </i>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">Jenis Barang</label>
                                    <input type="text" name="jenis_brg" class="form-control"
                                        value="{{ old('jenis_brg', $aset->kategori->nama ?? $aset->jenis_brg ?? '') }}" readonly required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Ruangan Sebelum</label>
                                    <input type="text" name="r_sebelum_text" class="form-control"
                                        value="{{ old('r_sebelum_text', $aset->ruang->name ?? '') }}" readonly>
                                    <input type="hidden" name="r_sebelum" value="{{ old('r_sebelum', $aset->ruang->name ?? $aset->code_ruang ?? '') }}">
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="code_ruang" class="form-label">Ruangan Tujuan</label>
                                    <div class="input-group">
                                        <input type="text" id="r_sesudah_text" class="form-control"
                                            value="{{ old('r_sesudah_text', $aset->ruang->name ?? '') }}" readonly>
                                        <input type="hidden" name="r_sesudah" value="{{ old('r_sesudah', $aset->ruang->code ?? $aset->code_ruang ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="ket" id="ket" class="form-control" rows="3">{{ old('ket', $aset->keterangan ?? '') }}</textarea>
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
                                    <button type="button" class="btn btn-secondary btn-sm mb-2" onclick="document.getElementById('galleryInputHistoriEditLelang').click()">
                                        <i class="fas fa-image me-1"></i> Ambil Galeri
                                    </button>
                                    <input type="file" id="galleryInputHistoriEditLelang" accept="image/*" multiple style="display:none;" />
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
                                    $fotos = [];
                                    if ($aset->foto) {
                                    if (is_string($aset->foto)) {
                                    $fotos = json_decode($aset->foto, true) ?: [];
                                    } elseif (is_array($aset->foto)) {
                                    $fotos = $aset->foto;
                                    } else {
                                    $fotos = [$aset->foto];
                                    }
                                    }
                                    // Gunakan 4 foto terakhir untuk ditampilkan
                                    $fotosClean = array_values(array_filter($fotos, function ($v) { return !empty($v); }));
                                    $displayFotos = array_values(array_slice($fotosClean, -4));
                                    @endphp

                                    @for($i = 1; $i <= 4; $i++)
                                        <div class="carousel-item {{ $i == 1 ? 'active' : '' }}" id="carousel_item_{{ $i }}">
                                        <div class="d-flex flex-column align-items-center p-2 border rounded aksi-hapus" style="min-height:240px;">

                                            {{-- Foto lama jika ada --}}
                                            @if(isset($displayFotos[$i-1]) && $displayFotos[$i-1])
                                            <img id="foto_lama_{{ $i }}"
                                                src="{{ asset('storage/' . $displayFotos[$i-1]) }}"
                                                class="img-fluid mb-2 aset-img"
                                                loading="lazy">
                                            <input type="hidden" name="foto_lama_{{ $i }}" value="{{ asset('storage/' . $displayFotos[$i-1]) }}">
                                            @else
                                            <img src="{{ asset('assets/img/aset-placeholder.png') }}" class="img-fluid mb-2 placeholder-img aset-img" alt="Placeholder Foto {{ $i }}">
                                            <img id="foto_lama_{{ $i }}" style="display:none;">
                                            <input type="hidden" name="foto_lama_{{ $i }}" value="">
                                            @endif

                                            <div id="results_{{ $i }}" style="text-align: center;"></div>

                                            <button type="button" id="hapus_btn_{{ $i }}"
                                                class="btn btn-danger btn-sm mt-2 {{ isset($displayFotos[$i-1]) ? '' : 'd-none' }}"
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
</div>
</div>

{{-- Modal Pilih Ruang --}}
<div class="modal fade" id="modalRuang" tabindex="-1" aria-labelledby="modalRuangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Ruangan Tujuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-hover table-bordered display">
                        <thead class="table-info">
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Kode Ruangan</th>
                                <th class="text-center">Nama Ruangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruang as $index => $itemRuang)
                            <tr @if(old('r_sesudah', $aset->r_sesudah ?? '') == $itemRuang->code) style="background-color: #d1e7dd;" @endif>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $itemRuang->code }}</td>
                                <td>{{ $itemRuang->name }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn-pilih-ruang"
                                        data-code="{{ $itemRuang->code }}"
                                        data-nama="{{ $itemRuang->name }}">
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
</div>

@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    const placeholderPath = "{{ asset('assets/img/aset-placeholder.png') }}";
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
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi foto lama ke hidden input
        setTimeout(() => {
            initializeExistingPhotos();
            updateSlotStatus(); // Update status setelah inisialisasi
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
                        console.log(`Foto lama Slot ${i} diinisialisasi: ${fotoLamaImg.src}`);
                    }
                } else {
                    // Tidak ada foto lama, pastikan input kosong
                    if (fotoLamaInput) {
                        fotoLamaInput.value = "";
                    }
                    console.log(`Slot ${i} tidak memiliki foto lama, input dikosongkan`);
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
        const galleryInputHistoriEditLelang = document.getElementById('galleryInputHistoriEditLelang');
        if (galleryInputHistoriEditLelang) {
            galleryInputHistoriEditLelang.addEventListener('change', async function() {
                const files = Array.from(galleryInputHistoriEditLelang.files || []);
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
                galleryInputHistoriEditLelang.value = '';
            });
        }

        // Tombol simpan
        const btnSimpan = document.querySelector('button[type="submit"]');
        btnSimpan.disabled = false;

        // Tampilkan status slot foto saat ini (akan diupdate setelah inisialisasi)
        // updateSlotStatus(); // Dipindah ke dalam setTimeout
    });

    // Fungsi untuk menampilkan status slot foto
    function updateSlotStatus() {
        const maxFoto = 4;
        let nextSlot = null;

        // Cari slot kosong pertama
        for (let i = 1; i <= maxFoto; i++) {
            const currentValue = document.getElementById('image_url_input_' + i).value;
            const fotoLamaImg = document.getElementById('foto_lama_' + i);

            // Slot kosong jika tidak ada foto baru DAN tidak ada foto lama
            if (!currentValue && (!fotoLamaImg || !fotoLamaImg.src || fotoLamaImg.src.includes('data:image'))) {
                nextSlot = i;
                break;
            }
        }

        // Jika semua slot terisi, akan mengganti slot 1
        if (!nextSlot) {
            console.log('Semua slot terisi. Foto berikutnya akan mengganti Slot 1');
        } else {
            console.log(`Slot berikutnya: Slot ${nextSlot}`);
        }
    }
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
            let currentInput = document.getElementById('image_url_input_' + i);
            let nextInput = document.getElementById('image_url_input_' + (i + 1));
            let currentResult = document.getElementById('results_' + i);
            let nextResult = document.getElementById('results_' + (i + 1));
            let currentFotoLamaInput = document.querySelector('input[name="foto_lama_' + i + '"]');
            let nextFotoLamaInput = document.querySelector('input[name="foto_lama_' + (i + 1) + '"]');
            let currentFotoLamaImg = document.getElementById('foto_lama_' + i);
            let nextFotoLamaImg = document.getElementById('foto_lama_' + (i + 1));

            // Geser foto baru
            if (currentInput && nextInput) {
                currentInput.value = nextInput.value;
            }
            if (currentResult && nextResult) {
                // Jika slot berikutnya kosong, tampilkan placeholder
                if (nextInput && nextInput.value === "" && (!nextFotoLamaInput || nextFotoLamaInput.value === "")) {
                    currentResult.innerHTML = `<img src="${placeholderPath}" class="img-thumbnail mb-2 placeholder-img aset-img" alt="Placeholder Foto ${i}"/>`;
                } else {
                    currentResult.innerHTML = nextResult.innerHTML;
                }
            }

            // Geser foto lama
            if (currentFotoLamaInput && nextFotoLamaInput) {
                currentFotoLamaInput.value = nextFotoLamaInput.value;
            }
            if (currentFotoLamaImg && nextFotoLamaImg) {
                currentFotoLamaImg.style.display = nextFotoLamaImg.style.display;
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

        console.log(`Foto di Slot ${slotIndex} telah dihapus dan slot telah dirapikan`);

        updateSlotStatus();
    }

    function updateSlotStatus() {
        const maxFoto = 4;
        let nextSlot = null;
        let occupiedSlots = [];

        for (let i = 1; i <= maxFoto; i++) {
            let input = document.getElementById('image_url_input_' + i);
            let fotoLamaImg = document.getElementById('foto_lama_' + i);
            let fotoLamaInput = document.querySelector('input[name="foto_lama_' + i + '"]');
            let hapusBtn = document.getElementById('hapus_btn_' + i);

            // Cek apakah slot terisi (foto baru atau foto lama)
            let hasNewPhoto = input && input.value !== "";
            let hasOldPhoto = fotoLamaImg && fotoLamaImg.src && !fotoLamaImg.src.includes('data:image') && fotoLamaInput && fotoLamaInput.value !== "";
            let isSlotOccupied = hasNewPhoto || hasOldPhoto;

            if (isSlotOccupied) {
                occupiedSlots.push(i);
            }

            // Tampilkan/sembunyikan tombol hapus
            if (hapusBtn) {
                if (isSlotOccupied) {
                    hapusBtn.classList.remove("d-none");
                } else {
                    hapusBtn.classList.add("d-none");
                }
            }

            // Cari slot kosong pertama untuk foto berikutnya
            if (!nextSlot && !isSlotOccupied) {
                nextSlot = i;
            }
        }

        // Log status untuk debugging
        console.log(`Slot yang terisi: ${occupiedSlots.join(', ')}`);
        if (nextSlot) {
            console.log(`Slot kosong berikutnya: Slot ${nextSlot}`);
        } else {
            console.log('Semua slot foto sudah terisi');
        }
    }

    // DataTable untuk modal ruangan
    $(document).ready(function() {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#myTable').DataTable();
            $('#filteredTable').DataTable();
        }

        // Set r_sesudah default sama dengan r_sebelum (kode), tanpa pilih manual
        var defaultKode = $("input[name='r_sesudah']").val() || $("input[name='r_sebelum']").val() || "";
        var defaultNama = $('#r_sesudah_text').val() || $("input[name='r_sebelum_text']").val() || "";
        $("input[name='r_sesudah']").val(defaultKode);
        $('#r_sesudah_text').val(defaultNama);

        // Event delegation untuk tombol pilih ruangan (opsional jika ingin mengganti manual)
        $('#myTable').on('click', '.btn-pilih-ruang', function() {
            var code = $(this).data('code');
            var nama = $(this).data('nama');
            $('#r_sesudah_text').val(nama);
            $("input[name='r_sesudah']").val(code);
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalRuang'));
            if (modal) modal.hide();
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Pastikan konten video/canvas tidak keluar dari border kamera */
    #my_camera {
        overflow: hidden;
        box-sizing: border-box;
        border-radius: .375rem;
        clip-path: inset(0 round .375rem);
        line-height: 0;
        padding: 0 !important;
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
        border: none !important;
    }

    .webcamjs-container {
        width: 100% !important;
        height: auto !important;
        border-radius: .375rem !important;
        overflow: hidden !important;
        clip-path: inset(0 round .375rem);
        line-height: 0;
        padding: 0 !important;
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
        max-height: 250px;
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

    .card {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Responsif smartphone: samakan perilaku dengan halaman aset/edit */
    @media (max-width: 576px) {
        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 {
            width: 100%;
            max-width: 340px;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: .75rem !important;
        }

        #my_camera {
            width: 100% !important;
            max-width: 320px;
            margin-left: auto;
            margin-right: auto;
            border: none !important;
        }

        #my_camera video,
        #my_camera canvas,
        #my_camera img {
            width: 100% !important;
            height: auto !important;
            object-fit: contain;
            border: none !important;
        }

        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 #my_camera {
            margin-bottom: 0 !important;
        }

        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 #canvas {
            margin: 0 !important;
        }

        .mb-3.d-flex.flex-column.align-items-center>.border.p-3 input[type="button"] {
            width: 100% !important;
            display: block;
        }

        /* Stack semua kolom */
        .col-md-3,
        .col-md-4,
        .col-md-6,
        .col-md-8,
        .col-md-12,
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