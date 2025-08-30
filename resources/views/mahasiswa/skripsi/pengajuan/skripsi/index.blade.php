@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')
    <style>
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .form-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            right: -50%;
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }

        .step:last-child::before {
            display: none;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            position: relative;
            z-index: 2;
        }

        .step.active .step-number {
            background: #28a745;
            color: white;
        }

        .step.completed .step-number {
            background: #28a745;
            color: white;
        }

        .step.completed::before {
            background: #28a745;
        }

        .judul-preview {
            background: #f8f9fa;
            border: 2px dashed #28a745;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
        }

     
        .suggestion-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .suggestion-card:hover {
            border-color: #28a745;
            background: #f0fff4;
            transform: translateY(-2px);
        }

        .requirement-item {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .requirement-item.completed {
            background: #d4edda;
            border-color: #c3e6cb;
        }

        .requirement-item.missing {
            background: #f8d7da;
            border-color: #f5c6cb;
        }

        .char-counter {
            font-size: 0.8em;
            color: #6c757d;
        }

        .char-counter.warning {
            color: #ffc107;
        }

        .char-counter.danger {
            color: #dc3545;
        }
    </style>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ 'Daftar Dosen Pembimbing' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{ 'Daftar Dosen Pembimbing' }}</li>
@endsection

@section('content')
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12">
            <div class="p-4">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="mahasiswa-dashboard.html">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#pengajuan">Pengajuan</a></li>
                        <li class="breadcrumb-item active">Judul Skripsi</li>
                    </ol>
                </nav>

                <!-- Form Header -->
                <div class="form-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Pengajuan Judul Skripsi</h2>
                            <p class="mb-0">Ajukan judul penelitian skripsi Anda dengan detail yang lengkap</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="bi bi-file-text fs-1"></i>
                        </div>
                    </div>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <small>Persyaratan</small>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <small>Judul & Abstrak</small>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <small>Detail Penelitian</small>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <small>Konfirmasi</small>
                    </div>
                </div>

                <!-- Step 1: Persyaratan -->
                <div id="step1" class="step-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-check2-square me-2"></i>Checklist Persyaratan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="requirement-item completed">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>Dosen Pembimbing Sudah Disetujui</strong>
                                                <p class="mb-0 text-muted">Dr. Budi Rahardjo & Dr. Siti Aminah</p>
                                            </div>
                                            <span class="badge bg-success">✓ Terpenuhi</span>
                                        </div>
                                    </div>

                                    <div class="requirement-item completed">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>IPK Minimum (2.75)</strong>
                                                <p class="mb-0 text-muted">IPK Anda: 3.45</p>
                                            </div>
                                            <span class="badge bg-success">✓ Terpenuhi</span>
                                        </div>
                                    </div>

                                    <div class="requirement-item completed">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>SKS Minimum (144)</strong>
                                                <p class="mb-0 text-muted">SKS Anda: 146</p>
                                            </div>
                                            <span class="badge bg-success">✓ Terpenuhi</span>
                                        </div>
                                    </div>

                                    <div class="requirement-item completed">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>Mata Kuliah Prasyarat</strong>
                                                <p class="mb-0 text-muted">Metodologi Penelitian: A</p>
                                            </div>
                                            <span class="badge bg-success">✓ Terpenuhi</span>
                                        </div>
                                    </div>

                                    <div class="alert alert-success mt-3">
                                        <i class="bi bi-check-circle me-2"></i>
                                        <strong>Selamat!</strong> Semua persyaratan telah terpenuhi. Anda dapat melanjutkan
                                        pengajuan judul skripsi.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Judul</h6>
                                </div>
                                <div class="card-body">
                                    <h6>Kriteria Judul yang Baik:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Spesifik dan jelas
                                        </li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Sesuai bidang
                                            keahlian dosen</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Dapat dikerjakan
                                            dalam waktu yang tersedia</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Memiliki kontribusi
                                            ilmiah</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Maksimal 20 kata
                                        </li>
                                    </ul>

                                    <hr>

                                    <h6>Bidang Keahlian Pembimbing:</h6>
                                    <div class="mb-2">
                                        <strong>Dr. Budi Rahardjo:</strong><br>
                                        <small class="text-muted">Machine Learning, AI, Data Mining</small>
                                    </div>
                                    <div>
                                        <strong>Dr. Siti Aminah:</strong><br>
                                        <small class="text-muted">Database Systems, Web Development</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-success btn-lg" onclick="nextStep(2)">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Judul & Abstrak -->
                <div id="step2" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Judul dan Abstrak</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Judul Skripsi <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" placeholder="Masukkan judul skripsi Anda..." id="judulSkripsi"
                                            maxlength="200" oninput="updateJudulPreview(); countChars('judulSkripsi', 'judulCounter', 200)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 200 karakter (sekitar 20 kata)</small>
                                            <span id="judulCounter" class="char-counter">0/200</span>
                                        </div>
                                    </div>

                                    <div class="judul-preview">
                                        <h6 class="text-muted mb-2">Preview Judul:</h6>
                                        <h5 id="judulPreview" class="text-success">Judul akan muncul di sini...</h5>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Judul Skripsi (English) <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" placeholder="Masukkan judul skripsi..." id="judulSkripsieng"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Abstrak Singkat <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="6"
                                            placeholder="Tuliskan abstrak singkat penelitian Anda (latar belakang, tujuan, metode, hasil yang diharapkan)..."
                                            id="abstrakSingkat" maxlength="1000" oninput="countChars('abstrakSingkat', 'abstrakCounter', 1000)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 1000 karakter</small>
                                            <span id="abstrakCounter" class="char-counter">0/1000</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-secondary btn-lg" onclick="prevStep(1)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button class="btn btn-success btn-lg" onclick="nextStep(3)" id="btnStep2">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Detail Penelitian -->
                <div id="step3" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-search me-2"></i>Detail Penelitian</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Latar Belakang Masalah <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" placeholder="Jelaskan latar belakang masalah yang akan diteliti..."
                                            id="latarBelakang" maxlength="2000" oninput="countChars('latarBelakang', 'latarCounter', 2000)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 2000 karakter</small>
                                            <span id="latarCounter" class="char-counter">0/2000</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Rumusan Masalah <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="4" placeholder="Tuliskan rumusan masalah penelitian..."
                                            id="rumusanMasalah" maxlength="1500" oninput="countChars('rumusanMasalah', 'rumusanCounter', 1500)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 1500 karakter</small>
                                            <span id="rumusanCounter" class="char-counter">0/1500</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Tujuan Penelitian <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="4" placeholder="Jelaskan tujuan penelitian..." id="tujuanPenelitian"
                                            maxlength="1500" oninput="countChars('tujuanPenelitian', 'tujuanCounter', 1500)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 1500 karakter</small>
                                            <span id="tujuanCounter" class="char-counter">0/1500</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Metodologi Penelitian <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="5" placeholder="Jelaskan metodologi yang akan digunakan..."
                                            id="metodologi" maxlength="2000" oninput="countChars('metodologi', 'metodologiCounter', 2000)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 2000 karakter</small>
                                            <span id="metodologiCounter" class="char-counter">0/2000</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                       
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Penelitian</label>
                                                <select class="form-select" id="jenisPenelitian">
                                                    <option value="">Pilih jenis penelitian</option>
                                                    <option value="kualitatif">Kualitatif</option>
                                                    <option value="kuantitatif">Kuantitatif</option>
                                                    <option value="mixed">Mixed Method</option>
                                                    <option value="eksperimen">Eksperimen</option>
                                                    <option value="studi_kasus">Studi Kasus</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-question-circle me-2"></i>Tips Pengisian</h6>
                                </div>
                                <div class="card-body">
                                    <div class="accordion" id="tipsAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#tip1">
                                                    Latar Belakang
                                                </button>
                                            </h2>
                                            <div id="tip1" class="accordion-collapse collapse"
                                                data-bs-parent="#tipsAccordion">
                                                <div class="accordion-body">
                                                    <small>Jelaskan mengapa penelitian ini penting dan apa masalah yang
                                                        ingin diselesaikan.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#tip2">
                                                    Rumusan Masalah
                                                </button>
                                            </h2>
                                            <div id="tip2" class="accordion-collapse collapse"
                                                data-bs-parent="#tipsAccordion">
                                                <div class="accordion-body">
                                                    <small>Buat pertanyaan penelitian yang spesifik dan dapat dijawab
                                                        melalui penelitian.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#tip3">
                                                    Metodologi
                                                </button>
                                            </h2>
                                            <div id="tip3" class="accordion-collapse collapse"
                                                data-bs-parent="#tipsAccordion">
                                                <div class="accordion-body">
                                                    <small>Jelaskan metode pengumpulan data, analisis, dan tools yang akan
                                                        digunakan.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-secondary btn-lg" onclick="prevStep(2)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button class="btn btn-success btn-lg" onclick="nextStep(4)" id="btnStep3">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Konfirmasi -->
                <div id="step4" class="step-content" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Konfirmasi Pengajuan Judul</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="judul-preview mb-4">
                                        <h6 class="text-muted mb-2">Judul Skripsi:</h6>
                                        <h4 id="finalJudulPreview" class="text-success">-</h4>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                        

                                            <h6>Detail Penelitian:</h6>
                                            <p><strong>Jenis Penelitian:</strong> <span id="finalJenis">-</span></p>
                                        </div>

                                        <div class="col-md-6">
                                            <h6>Abstrak:</h6>
                                            <p id="finalAbstrak" class="text-muted">-</p>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Perhatian:</strong> Setelah submit, pengajuan akan dikirim ke dosen
                                        pembimbing untuk review. Anda akan mendapat notifikasi melalui email dan sistem.
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="confirmSubmit">
                                        <label class="form-check-label" for="confirmSubmit">
                                            Saya menyatakan bahwa semua informasi yang saya berikan adalah benar dan dapat
                                            dipertanggungjawabkan.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-secondary btn-lg" onclick="prevStep(3)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button class="btn btn-success btn-lg" onclick="submitPengajuan()" id="btnSubmit" disabled>
                            <i class="bi bi-send me-2"></i> Submit Pengajuan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form id="formPengajuan" method="POST" action="{{ route('mhs.pengajuan.judul.store') }}">
            @csrf
            <input type="hidden" name="judul">
            <input type="hidden" name="judulEng">
            <input type="hidden" name="abstrak">
            <input type="hidden" name="latar_belakang">
            <input type="hidden" name="rumusan_masalah">
            <input type="hidden" name="tujuan">
            <input type="hidden" name="metodologi">
            <input type="hidden" name="jenis_penelitian">
        </form>

    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        let currentStep = 1;

        // Step navigation
        function nextStep(step) {
            if (validateStep(currentStep)) {
                document.getElementById(`step${currentStep}`).style.display = 'none';
                document.getElementById(`step${step}`).style.display = 'block';

                updateStepIndicator(currentStep, step);
                currentStep = step;

                if (step === 4) {
                    updateConfirmation();
                }
            }
        }

        function prevStep(step) {
            document.getElementById(`step${currentStep}`).style.display = 'none';
            document.getElementById(`step${step}`).style.display = 'block';

            updateStepIndicator(currentStep, step);
            currentStep = step;
        }

        function updateStepIndicator(from, to) {
            // Mark previous steps as completed
            for (let i = 1; i < to; i++) {
                document.querySelector(`.step:nth-child(${i})`).classList.add('completed');
                document.querySelector(`.step:nth-child(${i})`).classList.remove('active');
            }

            // Mark current step as active
            document.querySelector(`.step:nth-child(${to})`).classList.add('active');
            document.querySelector(`.step:nth-child(${to})`).classList.remove('completed');

            // Remove active from future steps
            for (let i = to + 1; i <= 4; i++) {
                document.querySelector(`.step:nth-child(${i})`).classList.remove('active', 'completed');
            }
        }

        function validateStep(step) {
            switch (step) {
                case 1:
                    return true; // Requirements are automatically checked
                case 2:
                    const judul = document.getElementById('judulSkripsi').value.trim();
                    const abstrak = document.getElementById('abstrakSingkat').value.trim();

                    if (!judul) {
                        alert('Judul skripsi harus diisi!');
                        return false;
                    }
                    if (judul.length < 10) {
                        alert('Judul terlalu pendek! Minimal 10 karakter.');
                        return false;
                    }
                    if (!abstrak) {
                        alert('Abstrak singkat harus diisi!');
                        return false;
                    }
                  
                    return true;
                case 3:
                    const requiredFields = ['latarBelakang', 'rumusanMasalah', 'tujuanPenelitian', 'metodologi'];
                    for (let field of requiredFields) {
                        if (!document.getElementById(field).value.trim()) {
                            alert(`${field.replace(/([A-Z])/g, ' $1').toLowerCase()} harus diisi!`);
                            return false;
                        }
                    }
                    return true;
                default:
                    return true;
            }
        }

        // Character counter
        function countChars(inputId, counterId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            const currentLength = input.value.length;

            counter.textContent = `${currentLength}/${maxLength}`;

            if (currentLength > maxLength * 0.9) {
                counter.className = 'char-counter danger';
            } else if (currentLength > maxLength * 0.7) {
                counter.className = 'char-counter warning';
            } else {
                counter.className = 'char-counter';
            }
        }

        // Judul preview
        function updateJudulPreview() {
            const judul = document.getElementById('judulSkripsi').value;
            const preview = document.getElementById('judulPreview');
            preview.textContent = judul || 'Judul akan muncul di sini...';
        }


   
      
       

        // Suggestion functions
        function useJudulSuggestion(element) {
            const judul = element.querySelector('h6').textContent;
            document.getElementById('judulSkripsi').value = judul;
            updateJudulPreview();
            countChars('judulSkripsi', 'judulCounter', 200);
        }

        // Confirmation update
        function updateConfirmation() {
            document.getElementById('finalJudulPreview').textContent =
                document.getElementById('judulSkripsi').value;

            document.getElementById('finalAbstrak').textContent =
                document.getElementById('abstrakSingkat').value;

      
            
            const jenis = document.getElementById('jenisPenelitian').value;
            document.getElementById('finalJenis').textContent =
                jenis ? jenis.charAt(0).toUpperCase() + jenis.slice(1).replace('_', ' ') : '-';
        }

        function submitPengajuan() {
            if (confirm('Apakah Anda yakin ingin submit pengajuan judul ini?')) {
                document.querySelector('[name="judul"]').value = document.getElementById('judulSkripsi').value;
                document.querySelector('[name="abstrak"]').value = document.getElementById('abstrakSingkat').value;
                document.querySelector('[name="latar_belakang"]').value = document.getElementById('latarBelakang').value;
                document.querySelector('[name="rumusan_masalah"]').value = document.getElementById('rumusanMasalah').value;
                document.querySelector('[name="tujuan"]').value = document.getElementById('tujuanPenelitian').value;
                document.querySelector('[name="metodologi"]').value = document.getElementById('metodologi').value;
                document.querySelector('[name="jenis_penelitian"]').value = document.getElementById('jenisPenelitian')
                .value;
                document.querySelector('[name="judulEng"]').value = document.getElementById('judulSkripsieng')
                .value;
                document.getElementById('formPengajuan').submit();

                // Tombol loading
                const btn = document.getElementById('btnSubmit');
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Mengirim...';
                btn.disabled = true;


            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('confirmSubmit').addEventListener('change', function() {
                document.getElementById('btnSubmit').disabled = !this.checked;
            });
        });
    </script>
@endsection
