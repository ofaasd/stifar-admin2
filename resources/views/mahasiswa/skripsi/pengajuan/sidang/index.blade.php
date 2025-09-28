@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')
<style> .card {
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.form-header {
    background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
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
    background: #ffc107;
    color: white;
}
.step.completed .step-number {
    background: #28a745;
    color: white;
}
.step.completed::before {
    background: #28a745;
}
.sidang-type-card {
    border: 2px solid #dee2e6;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 20px;
}
.sidang-type-card:hover {
    border-color: #ffc107;
    background: #fff8e1;
    transform: translateY(-5px);
}
.sidang-type-card.selected {
    border-color: #ffc107;
    background: #fff8e1;
}
.sidang-type-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s;
}
.upload-area:hover {
    border-color: #ffc107;
    background: #fff8e1;
}
.file-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: between;
}
.jadwal-preference {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s;
}
.jadwal-preference:hover {
    border-color: #ffc107;
    background: #fff8e1;
}
.jadwal-preference.selected {
    border-color: #ffc107;
    background: #fff8e1;
}
</style>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @endsection

@section('breadcrumb-title')
    <h3>{{ 'Pengajuan Sidang Skripsi' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Pengajuan</li>
    <li class="breadcrumb-item active">{{ 'Sidang' }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Form Header -->
            <div class="form-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">Pengajuan Sidang</h2>
                        <p class="mb-0">Ajukan sidang proposal atau sidang skripsi sesuai dengan progress Anda</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="bi bi-award fs-1"></i>
                    </div>
                </div>
            </div>
            
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active">
                    <div class="step-number">1</div>
                    <small>Jenis Sidang</small>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <small>Upload Dokumen</small>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <small>Jadwal & Konfirmasi</small>
                </div>
            </div>
           
            <form action="{{ route('mhs.pengajuan.sidang.store') }}" method="POST" id="formPengajuanSidang" enctype="multipart/form-data">
                @csrf
                <!-- Step 1: Jenis Sidang -->
                <div id="step1" class="step-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Pilih Jenis Sidang</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sidang-type-card" onclick="selectSidangType('terbuka')" id="terbukaCard">
                                                <i class="bi bi-file-earmark-text fs-1 text-primary mb-3"></i>
                                                <h5>Sidang Terbuka</h5>
                                                <p class="text-muted">Presentasi proposal skripsi (BAB 1-3)</p>
                                                <div class="mt-3">
                                                    <span class="badge bg-success">Tersedia</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sidang-type-card" onclick="selectSidangType('tertutup')" id="skripsiCard">
                                                <i class="bi bi-mortarboard fs-1 text-secondary mb-3"></i>
                                                <h5>Sidang Tertutup</h5>
                                                <p class="text-muted">Presentasi skripsi lengkap (BAB 1-5)</p>
                                                <div class="mt-3">
                                                    <span class="badge bg-success">Tersedia</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="selectedSidangInfo" class="mt-4" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6 id="sidangTitle">Sidang Terbuka</h6>
                                            <p id="sidangDescription" class="mb-0">Anda akan mempresentasikan proposal skripsi yang terdiri dari BAB 1 (Pendahuluan), BAB 2 (Tinjauan Pustaka), dan BAB 3 (Metodologi Penelitian).</p>
                                        </div>
                                    </div>

                                    <div id="selectedSkripsiInfo" class="mt-4" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6 id="sidangTitle">Sidang Tertutup</h6>
                                            <p id="sidangDescription" class="mb-0">Anda akan mempresentasikan skripsi lengkap yang terdiri dari BAB 1 (Pendahuluan), BAB 2 (Tinjauan Pustaka), BAB 3 (Metodologi Penelitian), BAB 4 (Hasil dan Pembahasan), dan BAB 5 (Kesimpulan).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-warning btn-lg" onclick="nextStep(2)" id="btnStep1" disabled>
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step -: Persyaratan -->
                {{-- <div id="step2" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-check2-square me-2"></i>Checklist Persyaratan Sidang Proposal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="requirement-item completed">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>Proposal Lengkap (BAB 1-3)</strong>
                                                <p class="mb-0 text-muted">File: proposal_lengkap_v3.pdf</p>
                                            </div>
                                            <span class="badge bg-success">✓ Tersedia</span>
                                        </div>
                                    </div>
                                    
                                    <div class="requirement-item completed">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>Persetujuan Pembimbing 1</strong>
                                                <p class="mb-0 text-muted">Dr. Budi Rahardjo - Disetujui</p>
                                            </div>
                                            <span class="badge bg-success">✓ Disetujui</span>
                                        </div>
                                    </div>
                                    
                                    <div class="requirement-item missing">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-x-circle text-danger me-2"></i>
                                                <strong>Persetujuan Pembimbing 2</strong>
                                                <p class="mb-0 text-muted">Dr. Siti Aminah - Menunggu review</p>
                                            </div>
                                            <span class="badge bg-warning">⏳ Pending</span>
                                        </div>
                                    </div>
                                    
                                    <div class="requirement-item completed">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>Minimal 8 Bimbingan</strong>
                                                <p class="mb-0 text-muted">Total bimbingan: 12 kali</p>
                                            </div>
                                            <span class="badge bg-success">✓ Terpenuhi</span>
                                        </div>
                                    </div>
                                    
                                    <div class="requirement-item missing">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-x-circle text-danger me-2"></i>
                                                <strong>Kartu Bimbingan</strong>
                                                <p class="mb-0 text-muted">Belum diupload</p>
                                            </div>
                                            <span class="badge bg-danger">✗ Belum</span>
                                        </div>
                                    </div>
                                    
                                    <div class="requirement-item missing">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-x-circle text-danger me-2"></i>
                                                <strong>Presentasi PowerPoint</strong>
                                                <p class="mb-0 text-muted">Belum diupload</p>
                                            </div>
                                            <span class="badge bg-danger">✗ Belum</span>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-warning mt-3">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Perhatian:</strong> Pastikan semua persyaratan terpenuhi sebelum melanjutkan. Hubungi pembimbing untuk mempercepat proses persetujuan.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Timeline Sidang</h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <strong>Pengajuan</strong><br>
                                            <small class="text-muted">Hari ini</small>
                                        </div>
                                        <div class="timeline-item">
                                            <strong>Review Koordinator</strong><br>
                                            <small class="text-muted">1-2 hari</small>
                                        </div>
                                        <div class="timeline-item">
                                            <strong>Penjadwalan</strong><br>
                                            <small class="text-muted">3-5 hari</small>
                                        </div>
                                        <div class="timeline-item">
                                            <strong>Sidang</strong><br>
                                            <small class="text-muted">7-14 hari</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-people me-2"></i>Tim Penguji</h6>
                                </div>
                                <div class="card-body">
                                    <small class="text-muted">Tim penguji akan ditentukan oleh koordinator berdasarkan bidang keahlian:</small>
                                    <ul class="list-unstyled mt-2">
                                        <li class="mb-1"><i class="bi bi-person me-2"></i>Ketua: Dosen Senior</li>
                                        <li class="mb-1"><i class="bi bi-person me-2"></i>Penguji 1: Sesuai bidang</li>
                                        <li class="mb-1"><i class="bi bi-person me-2"></i>Penguji 2: Eksternal/Internal</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep(1)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-warning btn-lg" onclick="nextStep(3)" id="btnStep2">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div> --}}
                
                <!-- Step 2: Upload Dokumen -->
                <div id="step2" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Dokumen Sidang</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Proposal Lengkap (Final) <span class="text-danger">*</span></label>
                                        <div class="upload-area" onclick="document.getElementById('proposalFinal').click()">
                                            <i class="bi bi-file-pdf fs-1 text-danger mb-3"></i>
                                            <h6>Upload Proposal Final</h6>
                                            <small class="text-muted">Format: PDF, maksimal 10MB</small>
                                            <input type="file" id="proposalFinal" accept=".pdf" style="display: none;" name="proposalFinal" onchange="handleFileUpload(this, 'proposal-preview')">
                                        </div>
                                        <div id="proposal-preview" class="mt-2"></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Kartu Bimbingan <span class="text-danger">*</span></label>
                                        <div class="upload-area" onclick="document.getElementById('kartuBimbingan').click()">
                                            <i class="bi bi-file-earmark-check fs-1 text-success mb-3"></i>
                                            <h6>Upload Kartu Bimbingan</h6>
                                            <small class="text-muted">Format: PDF, maksimal 5MB</small>
                                            <input type="file" id="kartuBimbingan" name="kartuBimbingan" accept=".pdf" style="display: none;" onchange="handleFileUpload(this, 'kartu-preview')">
                                        </div>
                                        <div id="kartu-preview" class="mt-2"></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Presentasi PowerPoint <span class="text-danger">*</span></label>
                                        <div class="upload-area" onclick="document.getElementById('presentasi').click()">
                                            <i class="bi bi-file-earmark-slides fs-1 text-warning mb-3"></i>
                                            <h6>Upload File Presentasi</h6>
                                            <small class="text-muted">Format: PPT/PPTX, maksimal 20MB</small>
                                            <input type="file" id="presentasi" name="presentasi" accept=".ppt,.pptx" style="display: none;" onchange="handleFileUpload(this, 'presentasi-preview')">
                                        </div>
                                        <div id="presentasi-preview" class="mt-2"></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Dokumen Pendukung <small class="text-muted">(Opsional)</small></label>
                                        <div class="upload-area" onclick="document.getElementById('pendukung').click()">
                                            <i class="bi bi-files fs-1 text-info mb-3"></i>
                                            <h6>Upload Dokumen Pendukung</h6>
                                            <small class="text-muted">Format: PDF/DOC/DOCX, maksimal 10MB</small>
                                            <input type="file" id="pendukung" name="pendukung" accept=".pdf,.doc,.docx" style="display: none;" onchange="handleFileUpload(this, 'pendukung-preview')">
                                        </div>
                                        <div id="pendukung-preview" class="mt-2"></div>
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
                                    <h6>Persyaratan File:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Proposal: PDF, max 10MB</li>
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Kartu Bimbingan: PDF, max 5MB</li>
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Presentasi: PPT/PPTX, max 20MB</li>
                                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>File harus jelas dan terbaca</li>
                                    </ul>
                                    
                                    <div class="alert alert-info">
                                        <small><i class="bi bi-lightbulb me-2"></i>
                                        <strong>Tips:</strong> Pastikan semua tanda tangan dan cap sudah lengkap pada kartu bimbingan.</small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Checklist Final</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check1">
                                        <label class="form-check-label" for="check1">
                                            <small>Proposal sudah final</small>
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check2">
                                        <label class="form-check-label" for="check2">
                                            <small>Kartu bimbingan lengkap</small>
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check3">
                                        <label class="form-check-label" for="check3">
                                            <small>Presentasi siap</small>
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep(1)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-warning btn-lg" onclick="nextStep(3)" id="btnStep2">
                            Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Jadwal & Konfirmasi -->
                <div id="step3" class="step-content" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-calendar-week me-2"></i>Preferensi Jadwal</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-3">Pilih preferensi waktu sidang Anda (koordinator akan menyesuaikan dengan ketersediaan penguji):</p>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Hari Preferensi:</h6>
                                            @foreach($hariSidang as $row)
                                                <div class="jadwal-preference">
                                                    <input type="radio" name="hari" value="{{ $row->id }}" class="form-check-input me-2">
                                                    <strong>{{ $row->nama }}</strong><br>
                                                    <small class="text-muted">{{ $row->keterangan }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <h6>Waktu Preferensi:</h6>
                                            @foreach ($waktuSidang as $row)
                                                <div class="jadwal-preference">
                                                    <input type="radio" name="waktu" value="{{ $row->id }}" class="form-check-input me-2">
                                                    <strong>{{ $row->nama }}</strong><br>
                                                    <small class="text-muted">{{ $row->keterangan }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label class="form-label">Catatan Tambahan</label>
                                        <textarea class="form-control" rows="3" name="catatanTambahan" placeholder="Tambahkan catatan khusus jika ada (opsional)..." id="catatanJadwal"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-calendar-range me-2"></i>Pilih Gelombang Sidang</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Gelombang Sidang</label>
                                        <select class="form-select" id="gelombangSidangSelect" name="gelombang_sidang_id" onchange="showGelombangDetail()" required>
                                            <option value="">-- Pilih Gelombang --</option>
                                            @foreach($gelombang as $row)
                                                <option value="{{ $row->id }}"
                                                    data-nama="{{ $row->nama }}"
                                                    data-periode="{{ $row->periode }}"
                                                    data-tanggal-mulai-daftar="{{ $row->tanggal_mulai_daftar }}"
                                                    data-tanggal-selesai-daftar="{{ $row->tanggal_selesai_daftar }}"
                                                    data-tanggal-mulai-pelaksanaan="{{ $row->tanggal_mulai_pelaksanaan }}"
                                                    data-tanggal-selesai-pelaksanaan="{{ $row->tanggal_selesai_pelaksanaan }}"
                                                    data-kuota="{{ $row->jumlahPeserta }}/{{ $row->kuota }}"
                                                >
                                                    {{ $row->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="gelombangDetail" style="display:none;">
                                        <div class="alert alert-info">
                                            <h6 id="gelombangNama"></h6>
                                            <ul class="mb-0">
                                                <li><strong>Periode:</strong> <span id="gelombangPeriode"></span></li>
                                                <li><strong>Pendaftaran:</strong> <span id="gelombangTanggalDaftar"></span></li>
                                                <li><strong>Pelaksanaan:</strong> <span id="gelombangTanggalPelaksanaan"></span></li>
                                                <li><strong>Kuota:</strong> <span id="gelombangKuota"></span> peserta</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="confirmSubmitSidang">
                                <label class="form-check-label" for="confirmSubmitSidang">
                                    Saya menyatakan bahwa semua dokumen dan informasi yang saya berikan adalah benar dan lengkap. Saya siap mengikuti sidang sesuai jadwal yang akan ditentukan.
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep(3)">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-warning btn-lg" onclick="submitPengajuanSidang()" id="btnSubmitSidang" disabled>
                            <i class="bi bi-send me-2"></i> Submit Pengajuan Sidang
                        </button>
                    </div>
                </div>

                <input type="hidden" name="jenisSidang" id="jenisSidang" value="">
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        let currentStep = 1;
        let selectedSidangType = null;
        let uploadedFiles = {};
        let selectedPreferences = {};

        // Step navigation
        function nextStep(step) {
            if (validateStep(currentStep)) {
                document.getElementById(`step${currentStep}`).style.display = 'none';
                document.getElementById(`step${step}`).style.display = 'block';
                
                updateStepIndicator(currentStep, step);
                currentStep = step;
                
                if (step === 3) {
                    updateFinalConfirmation();
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
                if (!selectedSidangType) {
                swal('Peringatan', 'Pilih jenis sidang terlebih dahulu!', 'warning');
                return false;
                }
                return true;
            case 2:
                const requiredFiles = ['proposalFinal', 'kartuBimbingan', 'presentasi'];
                for (let file of requiredFiles) {
                if (!uploadedFiles[file]) {
                    swal('Peringatan', 'Upload semua dokumen yang diperlukan!', 'warning');
                    return false;
                }
                }
                return true;
            case 3:
                if (!selectedPreferences.hari || !selectedPreferences.waktu) {
                swal('Peringatan', 'Pilih preferensi jadwal terlebih dahulu!', 'warning');
                return false;
                }
                return true;
            default:
                return true;
            }
        }

        // Sidang type selection
        function selectSidangType(type) {
            selectedSidangType = type;
            
            // Remove previous selection
            document.querySelectorAll('.sidang-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Select new type
            if (type === 'terbuka') {
                document.getElementById('terbukaCard').classList.add('selected');
                document.getElementById('selectedSidangInfo').style.display = 'block';
                document.getElementById('selectedSkripsiInfo').style.display = 'none';
                document.getElementById('btnStep1').disabled = false;
                document.getElementById('jenisSidang').value = 1;
            }else if (type === 'tertutup') {
                document.getElementById('skripsiCard').classList.add('selected');
                document.getElementById('selectedSkripsiInfo').style.display = 'block';
                document.getElementById('selectedSidangInfo').style.display = 'none';
                document.getElementById('btnStep1').disabled = false;
                document.getElementById('jenisSidang').value = 2;
            }
        }

        // File upload handling
        function handleFileUpload(input, previewId) {
            const file = input.files[0];
            if (file) {
                const fileType = input.id;
                const maxSizes = {
                    'proposalFinal': 10 * 1024 * 1024, // 10MB
                    'kartuBimbingan': 5 * 1024 * 1024, // 5MB
                    'presentasi': 20 * 1024 * 1024, // 20MB
                    'pendukung': 10 * 1024 * 1024 // 10MB
                };
                
                if (file.size > maxSizes[fileType]) {
                    swal('Peringatan', `Ukuran file terlalu besar! Maksimal ${maxSizes[fileType] / 1024 / 1024}MB.`, 'warning');
                    input.value = '';
                    return;
                }
                
                // Store file info
                uploadedFiles[fileType] = {
                    name: file.name,
                    size: file.size,
                    type: file.type
                };
                
                // Show preview
                const preview = document.getElementById(previewId);
                const icon = fileType === 'presentasi' ? 'file-earmark-slides' : 'file-pdf';
                const color = fileType === 'presentasi' ? 'warning' : 'danger';
                
                preview.innerHTML = `
                    <div class="file-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-${icon} text-${color} me-2"></i>
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

        // Preference selection
        function selectPreference(type, value) {
            selectedPreferences[type] = value;
            
            // Update radio buttons
            document.querySelector(`input[name="${type}"][value="${value}"]`).checked = true;
            
            // Update visual selection
            document.querySelectorAll(`input[name="${type}"]`).forEach(radio => {
                radio.closest('.jadwal-preference').classList.remove('selected');
            });
            document.querySelector(`input[name="${type}"][value="${value}"]`).closest('.jadwal-preference').classList.add('selected');
        }

        // Final confirmation update
        function updateFinalConfirmation() {
            document.getElementById('uploadedCount').textContent = Object.keys(uploadedFiles).length;
        }

        function submitPengajuanSidang() {
            swal({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin submit pengajuan sidang ini?',
            icon: 'warning',
            buttons: ['Batal', 'Ya, Submit'],
            dangerMode: true,
            }).then(function(willSubmit) {
            if (willSubmit) {
                const btn = document.getElementById('btnSubmitSidang');
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Mengirim...';
                btn.disabled = true;
                document.getElementById('formPengajuanSidang').submit();
            }
            });
        }

        function formatTanggal(dateStr) {
            if (!dateStr) return '-';
            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const d = new Date(dateStr);
            if (isNaN(d)) return '-';
            return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
        }

        function showGelombangDetail() {
            const select = document.getElementById('gelombangSidangSelect');
            const opt = select.options[select.selectedIndex];
            if (!opt.value) {
                document.getElementById('gelombangDetail').style.display = 'none';
                return;
            }
            document.getElementById('gelombangNama').textContent = opt.getAttribute('data-nama');
            document.getElementById('gelombangPeriode').textContent = opt.getAttribute('data-periode');
            document.getElementById('gelombangTanggalDaftar').textContent =
                `${formatTanggal(opt.getAttribute('data-tanggal-mulai-daftar'))} - ${formatTanggal(opt.getAttribute('data-tanggal-selesai-daftar'))}`;
            document.getElementById('gelombangTanggalPelaksanaan').textContent =
                `${formatTanggal(opt.getAttribute('data-tanggal-mulai-pelaksanaan'))} - ${formatTanggal(opt.getAttribute('data-tanggal-selesai-pelaksanaan'))}`;
            document.getElementById('gelombangKuota').textContent = opt.getAttribute('data-kuota');
            document.getElementById('gelombangDetail').style.display = 'block';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('confirmSubmitSidang').addEventListener('change', function() {
                document.getElementById('btnSubmitSidang').disabled = !this.checked;
            });
            
            // Add drag and drop functionality
            const uploadAreas = document.querySelectorAll('.upload-area');
            uploadAreas.forEach(area => {
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.style.borderColor = '#ffc107';
                    this.style.background = '#fff8e1';
                });
                
                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.style.borderColor = '#dee2e6';
                    this.style.background = '#f8f9fa';
                });
                
                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.style.borderColor = '#dee2e6';
                    this.style.background = '#f8f9fa';
                    
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const input = this.querySelector('input[type="file"]');
                        input.files = files;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            });
        });
    </script>
@endsection
