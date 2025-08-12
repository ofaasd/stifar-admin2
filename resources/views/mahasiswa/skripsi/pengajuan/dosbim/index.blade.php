@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .form-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .dosen-card {
            border: 2px solid #dee2e6;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .dosen-card:hover {
            border-color: #007bff;
            background: #f0f8ff;
            transform: translateY(-2px);
        }

        .dosen-card.selected {
            border-color: #007bff;
            background: #e3f2fd;
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
            background: #007bff;
            color: white;
        }

        .step.completed .step-number {
            background: #28a745;
            color: white;
        }

        .step.completed::before {
            background: #28a745;
        }

        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s;
        }

        .upload-area:hover {
            border-color: #007bff;
            background: #f0f8ff;
        }

        .upload-area.dragover {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .file-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <small>Persyaratan</small>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <small>Pilih Dosen</small>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <small>Upload Dokumen</small>
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

                                    <div class="requirement-item missing">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-x-circle text-danger me-2"></i>
                                                <strong>Transkrip Nilai Terbaru</strong>
                                                <p class="mb-0 text-muted">Belum diupload</p>
                                            </div>
                                            <span class="badge bg-danger">✗ Belum</span>
                                        </div>
                                    </div>

                                    <div class="requirement-item missing">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-x-circle text-danger me-2"></i>
                                                <strong>KRS Semester Terakhir</strong>
                                                <p class="mb-0 text-muted">Belum diupload</p>
                                            </div>
                                            <span class="badge bg-danger">✗ Belum</span>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning mt-3">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Perhatian:</strong> Pastikan semua persyaratan terpenuhi sebelum melanjutkan
                                        ke tahap berikutnya.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                                </div>
                                <div class="card-body">
                                    <h6>Ketentuan Dosen Pembimbing:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Pembimbing 1: Dosen
                                            tetap prodi</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Pembimbing 2: Dosen
                                            tetap/luar biasa</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Sesuai bidang
                                            keahlian</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Maksimal 10
                                            mahasiswa bimbingan</li>
                                    </ul>

                                    <hr>

                                    <h6>Dokumen yang Diperlukan:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-1"><i class="bi bi-file-pdf text-danger me-2"></i>Transkrip Nilai
                                        </li>
                                        <li class="mb-1"><i class="bi bi-file-pdf text-danger me-2"></i>KRS Terakhir</li>
                                        <li class="mb-1"><i class="bi bi-file-pdf text-danger me-2"></i>Surat Bebas
                                            Tunggakan (opsional)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-primary btn-lg" onclick="nextStep(2)">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Pilih Dosen -->
                <div id="step2" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pembimbing1_id" class="form-label">Pilih Pembimbing 1</label>
                            <select class="form-select" name="pembimbing1_id" id="pembimbing1_id" required>
                                <option value="">-- Pilih Pembimbing 1 --</option>
                                @foreach ($pembimbing as $dosen)
                                    <option value="{{ $dosen->npp }}">{{ $dosen->nama_lengkap }} (Kuota:
                                        {{ $dosen->kuota }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="pembimbing2_id" class="form-label">Pilih Pembimbing 2</label>
                            <select class="form-select" name="pembimbing2_id" id="pembimbing2_id" required>
                                <option value="">-- Pilih Pembimbing 2 --</option>
                                @foreach ($pembimbing as $dosen)
                                    <option value="{{ $dosen->npp }}">{{ $dosen->nama_lengkap }} (Kuota:
                                        {{ $dosen->kuota }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-chat-square-text me-2"></i>Alasan Pemilihan</h6>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" rows="4" placeholder="Jelaskan alasan Anda memilih dosen pembimbing tersebut..."
                                id="alasanPemilihan"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-secondary btn-lg" onclick="prevStep(1)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button class="btn btn-primary btn-lg" onclick="nextStep(3)" id="btnStep2">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Upload Dokumen -->
                <div id="step3" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Dokumen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Transkrip Nilai <span
                                                class="text-danger">*</span></label>
                                        <div class="upload-area" onclick="document.getElementById('transkrip').click()">
                                            <i class="bi bi-cloud-upload fs-1 text-primary mb-3"></i>
                                            <h6>Klik untuk upload atau drag & drop</h6>
                                            <small class="text-muted">Format: PDF, maksimal 5MB</small>
                                            <input type="file" id="transkrip" accept=".pdf" style="display: none;"
                                                onchange="handleFileUpload(this, 'transkrip-preview')">
                                        </div>
                                        <div id="transkrip-preview" class="mt-2"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">KRS Semester Terakhir <span
                                                class="text-danger">*</span></label>
                                        <div class="upload-area" onclick="document.getElementById('krs').click()">
                                            <i class="bi bi-cloud-upload fs-1 text-success mb-3"></i>
                                            <h6>Klik untuk upload atau drag & drop</h6>
                                            <small class="text-muted">Format: PDF, maksimal 5MB</small>
                                            <input type="file" id="krs" accept=".pdf" style="display: none;"
                                                onchange="handleFileUpload(this, 'krs-preview')">
                                        </div>
                                        <div id="krs-preview" class="mt-2"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Surat Bebas Tunggakan <small
                                                class="text-muted">(Opsional)</small></label>
                                        <div class="upload-area" onclick="document.getElementById('tunggakan').click()">
                                            <i class="bi bi-cloud-upload fs-1 text-info mb-3"></i>
                                            <h6>Klik untuk upload atau drag & drop</h6>
                                            <small class="text-muted">Format: PDF, maksimal 5MB</small>
                                            <input type="file" id="tunggakan" accept=".pdf" style="display: none;"
                                                onchange="handleFileUpload(this, 'tunggakan-preview')">
                                        </div>
                                        <div id="tunggakan-preview" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Upload</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Format
                                            file: PDF</li>
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Ukuran
                                            maksimal: 5MB</li>
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>File harus
                                            jelas dan terbaca</li>
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Gunakan
                                            scan berkualitas tinggi</li>
                                    </ul>

                                    <div class="alert alert-warning">
                                        <small><i class="bi bi-exclamation-triangle me-2"></i>
                                            Pastikan semua dokumen sudah benar sebelum submit.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-secondary btn-lg" onclick="prevStep(2)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button class="btn btn-primary btn-lg" onclick="nextStep(4)" id="btnStep3">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Konfirmasi -->
                <div id="step4" class="step-content" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Konfirmasi Pengajuan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Dosen Pembimbing yang Dipilih:</h6>
                                    <div class="mb-3">
                                        <strong>Pembimbing 1:</strong>
                                        <p class="mb-1" id="selectedPembimbing1">-</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Pembimbing 2:</strong>
                                        <p class="mb-1" id="selectedPembimbing2">-</p>
                                    </div>

                                    <h6>Alasan Pemilihan:</h6>
                                    <p class="text-muted" id="displayAlasan">-</p>
                                </div>

                                <div class="col-md-6">
                                    <h6>Dokumen yang Diupload:</h6>
                                    <div id="uploadedFiles">
                                        <!-- Files will be listed here -->
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Perhatian:</strong> Setelah submit, pengajuan akan dikirim ke koordinator untuk
                                diproses. Anda akan mendapat notifikasi melalui email dan sistem.
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="confirmSubmit">
                                <label class="form-check-label" for="confirmSubmit">
                                    Saya menyatakan bahwa semua data dan dokumen yang saya berikan adalah benar dan dapat
                                    dipertanggungjawabkan.
                                </label>
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
        <form id="formPengajuan" action="{{ route('mhs.pengajuan.pembimbing.store') }}" method="POST"
            enctype="multipart/form-data"> @csrf

            <!-- Input hidden untuk dosen -->
            <input type="hidden" name="pembimbing1" id="pembimbing1">
            <input type="hidden" name="pembimbing2" id="pembimbing2">

            <!-- Alasan -->
            <textarea name="alasan_pemilihan" id="alasanPemilihanHidden" hidden></textarea>

            <!-- Upload file -->
            <input type="file" id="transkripData" name="transkrip" hidden>
            <input type="file" id="krsData" name="krs" hidden>
            <input type="file" id="tunggakanData" name="tunggakan" hidden>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        let currentStep = 1;
        let selectedPembimbing1 = null;
        let selectedPembimbing2 = null;
        let uploadedFiles = {};

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
                  
                    if (!document.getElementById('alasanPemilihan').value.trim()) {
                        alert('Alasan pemilihan harus diisi!');
                        return false;
                    }
                    return true;
                case 3:
                    if (!uploadedFiles.transkrip || !uploadedFiles.krs) {
                        alert('Upload transkrip nilai dan KRS terlebih dahulu!');
                        return false;
                    }
                    return true;
                default:
                    return true;
            }
        }
        document.getElementById('pembimbing1_id').addEventListener('change', function() {
            let pemb1 = this.value;
            let pemb2 = document.getElementById('pembimbing2_id').value;
            if (pemb1 && pemb1 === pemb2) {
                alert('Pembimbing 1 dan 2 tidak boleh sama!');
                this.value = '';
            }
        });

        document.getElementById('pembimbing2_id').addEventListener('change', function() {
            let pemb2 = this.value;
            let pemb1 = document.getElementById('pembimbing1_id').value;
            if (pemb1 && pemb1 === pemb2) {
                alert('Pembimbing 1 dan 2 tidak boleh sama!');
                this.value = '';
            }
        });

        

        // File upload handling
        function handleFileUpload(input, previewId) {
            const file = input.files[0];
            console.log(`input: ${input}`);
console.log(`previewId: ${previewId}`);

            if (file) {
                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    alert('Ukuran file terlalu besar! Maksimal 5MB.');
                    input.value = '';
                    return;
                }

                if (file.type !== 'application/pdf') {
                    alert('Format file harus PDF!');
                    input.value = '';
                    return;
                }

                // Store file info
                const fileType = input.id;
                uploadedFiles[fileType] = {
                    name: file.name,
                    size: file.size,
                    type: file.type
                };
                const hiddenInput = document.getElementById(fileType + 'Data');
    const dt = new DataTransfer();
    dt.items.add(file);
    hiddenInput.files = dt.files;

                // Show preview
                const preview = document.getElementById(previewId);
                preview.innerHTML = `
                    <div class="file-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-pdf text-danger me-2"></i>
                            <div class="flex-grow-1">
                                <strong>${file.name}</strong><br>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeFile('${fileType}', '${previewId}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }
        }

        function removeFile(fileType, previewId) {
            delete uploadedFiles[fileType];
            document.getElementById(previewId).innerHTML = '';
            document.getElementById(fileType).value = '';
        }

        // Drag and drop functionality
        document.addEventListener('DOMContentLoaded', function() {
            const uploadAreas = document.querySelectorAll('.upload-area');

            uploadAreas.forEach(area => {
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });

                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });

                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const input = this.querySelector('input[type="file"]');
                        input.files = files;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Checkbox validation
            document.getElementById('confirmSubmit').addEventListener('change', function() {
                document.getElementById('btnSubmit').disabled = !this.checked;
            });
        });

        function updateConfirmation() {
            // Update selected pembimbing
        
           const select1 = document.getElementById('pembimbing1_id');
const select2 = document.getElementById('pembimbing2_id');

const pembimbing1Name = select1.selectedIndex > 0 ? select1.options[select1.selectedIndex].text : '-';
const pembimbing2Name = select2.selectedIndex > 0 ? select2.options[select2.selectedIndex].text : '-';

document.getElementById('selectedPembimbing1').textContent = pembimbing1Name;
document.getElementById('selectedPembimbing2').textContent = pembimbing2Name;
            // Update alasan
            document.getElementById('displayAlasan').textContent =
                document.getElementById('alasanPemilihan').value || '-';

            // Update uploaded files
            const filesContainer = document.getElementById('uploadedFiles');
            filesContainer.innerHTML = '';

            Object.keys(uploadedFiles).forEach(fileType => {
                const file = uploadedFiles[fileType];
                console.log(uploadedFiles)
                const fileLabels = {
                    'transkrip': 'Transkrip Nilai',
                    'krs': 'KRS Semester Terakhir',
                    'tunggakan': 'Surat Bebas Tunggakan'
                };

                filesContainer.innerHTML += `
                    <div class="mb-2">
                        <i class="bi bi-file-pdf text-danger me-2"></i>
                        <strong>${fileLabels[fileType]}:</strong> ${file.name}
                        <small class="text-muted">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                    </div>
                `;
            });
        }

        function submitPengajuan() {
    if (confirm('Apakah Anda yakin ingin submit pengajuan ini?')) {
        // Ambil value dari dropdown pilihan
        const pembimbing1_id = document.getElementById('pembimbing1_id').value;
        const pembimbing2_id = document.getElementById('pembimbing2_id').value;

        // Validasi input pembimbing
        if (!pembimbing1_id || !pembimbing2_id) {
            alert('Silakan pilih pembimbing terlebih dahulu.');
            return;
        }

        if (pembimbing1_id === pembimbing2_id) {
            alert('Pembimbing 1 dan 2 tidak boleh sama.');
            return;
        }

        // Masukkan ke input hidden yang dikirimkan
        document.getElementById('pembimbing1').value = pembimbing1_id;
        document.getElementById('pembimbing2').value = pembimbing2_id;
        document.getElementById('alasanPemilihanHidden').value = document.getElementById('alasanPemilihan').value;

        // Tombol loading
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Mengirim...';
        btn.disabled = true;

        // Submit form
        document.getElementById('formPengajuan').submit();
    }
}


        function showToast(message, type) {
            // Create toast element
            const toastContainer = document.createElement('div');
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '20px';
            toastContainer.style.right = '20px';
            toastContainer.style.zIndex = '9999';

            const alertClass = type === 'success' ? 'alert-success' : type === 'warning' ? 'alert-warning' : 'alert-info';
            const icon = type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle';

            toastContainer.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            document.body.appendChild(toastContainer);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toastContainer.parentNode) {
                    toastContainer.parentNode.removeChild(toastContainer);
                }
            }, 3000);
        }
    </script>
@endsection
