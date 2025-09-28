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
        <div class="col-md-12">
            <div class="p-4">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="mahasiswa-dashboard.html">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#pengajuan">Pengajuan</a></li>
                        <li class="breadcrumb-item active">Judul Skripsi</li>
                    </ol>
                </nav>

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
                        <small>Konfirmasi</small>
                    </div>
                </div>

                <div id="step1" class="step-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-check2-square me-2"></i>Checklist Persyaratan</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $syarat = 0;
                                        if ($mhs->ipk >= 2.75) {
                                            $syarat++;
                                        }
                                        if ($mhs->totalSks >= 144) {
                                            $syarat++;
                                        }
                                    @endphp
                                    <div class="requirement-item {{ $mhs->ipk >= 2.75 ? 'completed' : 'missing' }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>IPK Minimum (2.75)</strong>
                                                <p class="mb-0 text-muted">IPK Anda: {{ $mhs->ipk }}</p>
                                            </div>
                                            @if ($mhs->ipk >= 2.75)
                                                <span class="badge bg-success">✓ Terpenuhi</span>
                                            @else
                                                <span class="badge bg-danger">✗ Belum Terpenuhi</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="requirement-item {{ $mhs->totalSks >= 144 ? 'completed' : 'missing' }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>SKS Minimum (144)</strong>
                                                <p class="mb-0 text-muted">SKS Anda: {{ $mhs->totalSks ?? '-' }}</p>
                                            </div>
                                            @if ($mhs->totalSks >= 144)
                                                <span class="badge bg-success">✓ Terpenuhi</span>
                                            @else
                                                <span class="badge bg-danger">✗ Belum Terpenuhi</span>
                                            @endif
                                        </div>
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
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Spesifik dan jelas</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Sesuai bidang keahlian dosen</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Dapat dikerjakan dalam waktu yang tersedia</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Memiliki kontribusi ilmiah</li>
                                        <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Maksimal 20 kata</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-success btn-lg" onclick="nextStep(2)" {{ $syarat < 2 ? 'disabled' : '' }}>
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <div id="step2" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Judul dan Abstrak</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Judul Skripsi 1 <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" placeholder="Masukkan judul skripsi Anda..." id="judulSkripsi1"
                                            maxlength="200" oninput="countChars('judulSkripsi1', 'judulCounter1', 200)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 200 karakter (sekitar 20 kata)</small>
                                            <span id="judulCounter1" class="char-counter">0/200</span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Judul Skripsi (English) 1 <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" placeholder="Masukkan judul skripsi..." id="judulSkripsieng1"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Judul Skripsi 2 <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" placeholder="Masukkan judul skripsi Anda..." id="judulSkripsi2"
                                            maxlength="200"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 200 karakter (sekitar 20 kata)</small>
                                            <span id="judulCounter2" class="char-counter">0/200</span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Judul Skripsi (English) 2 <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="3" placeholder="Masukkan judul skripsi..." id="judulSkripsieng2"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Abstrak Singkat 1<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="6"
                                            placeholder="Tuliskan abstrak singkat penelitian Anda (latar belakang, tujuan, metode, hasil yang diharapkan)..."
                                            id="abstrakSingkat1" maxlength="1000" oninput="countChars('abstrakSingkat1', 'abstrakCounter1', 1000)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 1000 karakter</small>
                                            <span id="abstrakCounter1" class="char-counter">0/1000</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Abstrak Singkat 2<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="6"
                                            placeholder="Tuliskan abstrak singkat penelitian Anda (latar belakang, tujuan, metode, hasil yang diharapkan)..."
                                            id="abstrakSingkat2" maxlength="1000" oninput="countChars('abstrakSingkat2', 'abstrakCounter2', 1000)"></textarea>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Maksimal 1000 karakter</small>
                                            <span id="abstrakCounter2" class="char-counter">0/1000</span>
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

                <div id="step3" class="step-content" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Konfirmasi Pengajuan Judul</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="judul-preview mb-4">
                                        <h6 class="text-muted mb-2">Judul Skripsi 1 & 2:</h6>
                                        <h4 id="finalJudulPreview1" class="text-success">-</h4>
                                        <hr>
                                        <h4 id="finalJudulPreview2" class="text-success">-</h4>
                                    </div>
                                    <div class="row">
                                        {{-- <div class="col-md-6">
                                            <h6>Detail Penelitian:</h6>
                                            <p><strong>Jenis Penelitian:</strong> <span id="finalJenis">-</span></p>
                                        </div> --}}
                                        <div class="col-md-6">
                                            <h6>Abstrak 1:</h6>
                                            <p id="finalAbstrak1" class="text-muted">-</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Abstrak 2:</h6>
                                            <p id="finalAbstrak2" class="text-muted">-</p>
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
                        <button class="btn btn-outline-secondary btn-lg" onclick="prevStep(2)">
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
            <input type="hidden" name="judul1">
            <input type="hidden" name="judulEng1">
            <input type="hidden" name="judul2">
            <input type="hidden" name="judulEng2">
            <input type="hidden" name="abstrak1">
            <input type="hidden" name="abstrak2">
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

                if (step === 3) {
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
            for (let i = to + 1; i <= 3; i++) {
                document.querySelector(`.step:nth-child(${i})`).classList.remove('active', 'completed');
            }
        }

        function validateStep(step) {
            switch (step) {
            case 1:
                return true; // Requirements are automatically checked
            case 2:
                const judul1 = document.getElementById('judulSkripsi1').value.trim();
                const judul2 = document.getElementById('judulSkripsi2').value.trim();
                const abstrak1 = document.getElementById('abstrakSingkat1').value.trim();
                const abstrak2 = document.getElementById('abstrakSingkat2').value.trim();

                if (!judul1) {
                swal('Judul 1 skripsi harus diisi!', '', 'warning');
                return false;
                }
                if (judul1.length < 10) {
                swal('Judul 1 terlalu pendek! Minimal 10 karakter.', '', 'warning');
                return false;
                }

                if (!judul2) {
                swal('Judul 2 skripsi harus diisi!', '', 'warning');
                return false;
                }
                if (judul2.length < 10) {
                swal('Judul 2 terlalu pendek! Minimal 10 karakter.', '', 'warning');
                return false;
                }
                if (!abstrak1) {
                swal('Abstrak singkat 1 harus diisi!', '', 'warning');
                return false;
                }

                if (!abstrak2) {
                swal('Abstrak singkat 2 harus diisi!', '', 'warning');
                return false;
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

        // Confirmation update
        function updateConfirmation() {
            document.getElementById('finalJudulPreview1').textContent =
                document.getElementById('judulSkripsi1').value;

            document.getElementById('finalJudulPreview2').textContent =
                document.getElementById('judulSkripsi2').value;

            document.getElementById('finalAbstrak1').textContent =
                document.getElementById('abstrakSingkat1').value;
            document.getElementById('finalAbstrak2').textContent =
                document.getElementById('abstrakSingkat2').value;
            
            // const jenis = document.getElementById('jenisPenelitian').value;
            // document.getElementById('finalJenis').textContent =
            //     jenis ? jenis.charAt(0).toUpperCase() + jenis.slice(1).replace('_', ' ') : '-';
        }

        function submitPengajuan() {
            swal({
                title: 'Konfirmasi Submit',
                text: 'Apakah Anda yakin ingin submit pengajuan judul ini?',
                icon: 'warning',
                buttons: ['Batal', 'Ya, Submit'],
                dangerMode: true,
            }).then(function(willSubmit) {
                if (willSubmit) {
                    document.querySelector('[name="judul1"]').value = document.getElementById('judulSkripsi1').value;
                    document.querySelector('[name="judulEng1"]').value = document.getElementById('judulSkripsieng1').value;
                    document.querySelector('[name="judul2"]').value = document.getElementById('judulSkripsi2').value;
                    document.querySelector('[name="judulEng2"]').value = document.getElementById('judulSkripsieng2').value;
                    document.querySelector('[name="abstrak1"]').value = document.getElementById('abstrakSingkat1').value;
                    document.querySelector('[name="abstrak2"]').value = document.getElementById('abstrakSingkat2').value;

                    document.getElementById('formPengajuan').submit();

                    // Tombol loading
                    const btn = document.getElementById('btnSubmit');
                    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Mengirim...';
                    btn.disabled = true;
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('confirmSubmit').addEventListener('change', function() {
                document.getElementById('btnSubmit').disabled = !this.checked;
            });
        });
    </script>
@endsection