@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Progress Skripsi - Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .progress-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .progress-ring {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: conic-gradient(#ffffff 0deg 144deg, rgba(255,255,255,0.3) 144deg 360deg);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 0 auto;
        }
        .progress-ring::before {
            content: '';
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            position: absolute;
        }
        .progress-text {
            position: relative;
            z-index: 1;
            font-weight: bold;
            font-size: 24px;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 30px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #007bff;
            border: 3px solid white;
        }
        .timeline-item.completed::before {
            background: #28a745;
        }
        .timeline-item.current::before {
            background: #ffc107;
            animation: pulse 2s infinite;
        }
        .timeline-item.pending::before {
            background: #6c757d;
        }
        .milestone-card {
            border-left: 4px solid #007bff;
            background: #f0f8ff;
        }
        .milestone-card.completed {
            border-left-color: #28a745;
            background: #f0fff4;
        }
        .milestone-card.current {
            border-left-color: #ffc107;
            background: #fffbf0;
        }
        .feedback-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }
        .document-card {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .document-card:hover {
            border-color: #007bff;
            background: #f0f8ff;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar d-flex flex-column p-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="bi bi-mortarboard-fill"></i>
                            Mahasiswa
                        </h4>
                        <small class="text-white-50">Ahmad Fauzi</small>
                        <small class="text-white-50 d-block">12345678</small>
                    </div>
                    
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="mahasiswa-dashboard.html">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pengajuan">
                                <i class="bi bi-file-earmark-plus me-2"></i>Pengajuan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#bimbingan">
                                <i class="bi bi-people me-2"></i>Bimbingan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#dokumen">
                                <i class="bi bi-folder me-2"></i>Dokumen
                            </a>
                        </li>
                    </ul>
                    
                    <div class="mt-auto">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>Ahmad Fauzi
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="mahasiswa-dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Detail Progress</li>
                        </ol>
                    </nav>
                    
                    <!-- Progress Header -->
                    <div class="progress-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-2">Progress Skripsi</h2>
                                <h5 class="mb-3">"Sistem Manajemen Inventory Berbasis Web dengan Teknologi Machine Learning"</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><i class="bi bi-person me-2"></i>Pembimbing 1: Dr. Budi Rahardjo</p>
                                        <p class="mb-0"><i class="bi bi-person me-2"></i>Pembimbing 2: Dr. Siti Aminah</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><i class="bi bi-calendar me-2"></i>Mulai: 15 Oktober 2023</p>
                                        <p class="mb-0"><i class="bi bi-calendar-check me-2"></i>Target: 30 Mei 2024</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="progress-ring mb-3">
                                    <div class="progress-text">40%</div>
                                </div>
                                <h5>Tahap Proposal</h5>
                                <span class="badge fs-6" style="background: rgba(255,255,255,0.2);">Sedang Berlangsung</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Main Timeline -->
                        <div class="col-md-8">
                            <!-- Milestone Overview -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-flag me-2"></i>Milestone Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="milestone-card completed p-3">
                                                <h6 class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Pengajuan Dosbim</h6>
                                                <small class="text-muted">Selesai: 15 Oktober 2023</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="milestone-card completed p-3">
                                                <h6 class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Pengajuan Judul</h6>
                                                <small class="text-muted">Selesai: 22 Oktober 2023</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="milestone-card current p-3">
                                                <h6 class="mb-2"><i class="bi bi-clock text-warning me-2"></i>Pengerjaan Proposal</h6>
                                                <small class="text-muted">Target: 30 Januari 2024</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="milestone-card p-3">
                                                <h6 class="mb-2"><i class="bi bi-circle text-muted me-2"></i>Sidang Proposal</h6>
                                                <small class="text-muted">Target: Februari 2024</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detailed Timeline -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Timeline Detail</h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item completed">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="mb-0">Pengajuan Dosen Pembimbing</h6>
                                                <span class="badge bg-success">Selesai</span>
                                            </div>
                                            <p class="mb-2">Pengajuan dosbim telah disetujui koordinator. Dosen pembimbing yang disetujui sesuai dengan bidang keahlian yang dibutuhkan.</p>
                                            <small class="text-muted"><i class="bi bi-calendar me-1"></i>15 Oktober 2023</small>
                                            
                                            <div class="feedback-card">
                                                <strong>Feedback Koordinator:</strong>
                                                <p class="mb-0 mt-1">"Pemilihan dosen pembimbing sudah sesuai dengan topik penelitian yang diajukan."</p>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item completed">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="mb-0">Pengajuan Judul</h6>
                                                <span class="badge bg-success">Disetujui</span>
                                            </div>
                                            <p class="mb-2">Judul skripsi "Sistem Manajemen Inventory Berbasis Web dengan Teknologi Machine Learning" telah disetujui oleh pembimbing dan koordinator.</p>
                                            <small class="text-muted"><i class="bi bi-calendar me-1"></i>22 Oktober 2023</small>
                                            
                                            <div class="feedback-card">
                                                <strong>Feedback Dr. Budi Rahardjo:</strong>
                                                <p class="mb-0 mt-1">"Judul sudah bagus dan sesuai dengan perkembangan teknologi terkini. Fokus pada implementasi machine learning untuk prediksi inventory."</p>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item current">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="mb-0">Pengerjaan Proposal (BAB 1-3)</h6>
                                                <span class="badge bg-warning">Berlangsung</span>
                                            </div>
                                            <p class="mb-2">Sedang mengerjakan proposal skripsi BAB 1-3. Progress saat ini 40% dengan fokus pada penyelesaian BAB 2 (Tinjauan Pustaka).</p>
                                            <small class="text-muted"><i class="bi bi-calendar me-1"></i>Mulai: 25 Oktober 2023</small>
                                            
                                            <div class="progress mt-3 mb-3">
                                                <div class="progress-bar bg-warning" style="width: 40%">40%</div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <small class="text-success"><i class="bi bi-check-circle me-1"></i>BAB 1: Selesai</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-warning"><i class="bi bi-clock me-1"></i>BAB 2: 60%</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted"><i class="bi bi-circle me-1"></i>BAB 3: Belum</small>
                                                </div>
                                            </div>
                                            
                                            <div class="feedback-card">
                                                <strong>Catatan Bimbingan Terakhir (18 Jan 2024):</strong>
                                                <p class="mb-0 mt-1">"BAB 1 sudah bagus, lanjutkan ke BAB 2. Perbanyak referensi jurnal internasional untuk tinjauan pustaka."</p>
                                            </div>
                                        </div>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/tagify.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ 'Daftar Mahasiwa Pengajuan' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">{{ 'Daftar Mahasiwa Pengajuan' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="pembimbing-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nim</th>
                                    <th>Nama</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
    


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">Detail Mahasiswa</h5>
             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label for="judul">Judul Skripsi:</label>
                <p id="judul">-</p>
            </div>
            <div class="form-group">
                <label for="abstrak">Abstrak:</label>
                <p id="abstrak">-</p>
            </div>
            <div class="form-group">
                <label for="transkrip_nilai">Transkrip Nilai:</label>
                <p id="transkrip_nilai">-</p>
            </div>
            <div class="form-group">
                <label for="file_pendukung_1">File Pendukung 1:</label>
                <p id="file_pendukung_1">-</p>
            </div>
            <div class="form-group">
                <label for="file_pendukung_2">File Pendukung 2:</label>
                <p id="file_pendukung_2">-</p>
            </div>
        </div>
          <div class="modal-footer">
             <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
          </div>
       </div>
    </div>
 </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#pembimbing-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dosen.pengajuan.getDataMahasiswa') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'button',
                        name: 'button',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data dosen pembimbing yang tersedia." // Pesan ketika data kosong
                }


            });


            $(document).on('click', '.btnShowModal', function() {
        var nim = $(this).data('id'); // Get nim from button

        // Fetch data using AJAX
        $.ajax({
            url: '{{ route('dosen.pengajuan.getDetailMhs', '') }}/' + nim,
            method: 'GET',
            success: function(data) {
                // Fill modal fields with data
                $('#judul').text(data.judul || '-');
                $('#abstrak').text(data.abstrak || '-');
                $('#transkrip_nilai').html(data.transkrip_nilai 
                ? `<a href="{{ asset('storage/') }}/${data.transkrip_nilai}" target="_blank">Download</a>` 
                : '-');
            $('#file_pendukung_1').html(data.file_pendukung_1 
                ? `<a href="{{ asset('storage/') }}/${data.file_pendukung_1}" target="_blank">Download</a>` 
                : '-');
            $('#file_pendukung_2').html(data.file_pendukung_2 
                ? `<a href="{{ asset('storage/') }}/${data.file_pendukung_2}" target="_blank">Download</a>` 
                : '-');

                // Show modal
                $('#detailModal').modal('show');
            },
            error: function(xhr) {
                swal("Error", "Gagal mengambil data detail mahasiswa.", "error");
                console.log(xhr.responseText);
            }
        });
    });

            $(document).on('click', '.btn-info', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('dosen.pengajuan.acc', '') }}/' + id,
                    method: 'GET',
                    success: function(response) {
                        swal("Success", response.message, "success");
                        $('#pembimbing-table').DataTable().ajax
                                .reload(); // Reload DataTables
                    },
                    error: function(xhr) {
                        swal("error", "Gagal Menerima mahasiswa ", "error");
                        console.log(xhr.responseText);
                    }
                });
            });
            $(document).on('click', '.btn-danger', function() {
                var nip = $(this).data('id');

                $.ajax({
                    url: '{{ route('dosen.pengajuan.delete', '') }}/' + nip,
                    method: 'GET',
                    success: function(response) {
                        swal("Success", "Delete Mahasiswa Berhasil ", "success");
                        $('#pembimbing-table').DataTable().ajax
                                .reload(); // Reload DataTables
                    },
                    error: function(xhr) {
                        swal("error", "Gagal menghapus data mahasiswa ", "error");
                        console.log(xhr.responseText);
                    }
                });
            });


        });
    </script>
@endsection
