@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ 'Bimbingan Mahasiswa' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Bimbingan</li>
    <li class="breadcrumb-item active">{{ 'Bimbingan Skripsi' }}</li>
@endsection

@section('content')
    <div class="container mt-3">
        @if (session('message'))
            <div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show"
                role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>


    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="col-sm-12 col-lg-10 col-xl-8">
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Nama Mahasiswa</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $namaMahasiswa->nama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Judul Skripsi</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $judul->judul ?? '-' }}</p>
                            </div>
                        </div>


                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Tahap Bimbingan</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $TahapBimbingan ? $TahapBimbingan->kategori : '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Status</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>
                                    {{ $TahapBimbingan
                                        ? ($TahapBimbingan->status == 0
                                            ? 'Pengajuan'
                                            : ($TahapBimbingan->status == 1
                                                ? 'Acc'
                                                : 'Revisi'))
                                        : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h3>Data Bimbingan Skripsi</h3>
        <div class="row">
            <div class="card">
            <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataBimbinganTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataBimbingan as $bimbingan)
                                    <tr>
                                        <td>{{ $bimbingan->kategori }}</td>
                                        <td>{{ $bimbingan->keterangan }}</td>
                                        <td>
                                            <a href="{{ asset('storage/bimbingan_files/' . $bimbingan->file) }}" target="_blank"
                                                class="btn btn-sm btn-primary">
                                                Lihat File
                                            </a>
                                        </td>
                                        <td>
                                            @if($bimbingan->status == 1)
                                            <span class="text-center badge badge-success"><i class="fa fa-check"></i></span>
                                            @else
                                            <button class="btn btn-success acc" data-id="{{ $bimbingan->id_logbook }}" data-nama="{{ $bimbingan->file }}">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button class="btn btn-warning revisi" data-id="{{ $bimbingan->id_logbook }}" data-nama="{{ $bimbingan->file }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
            </div>

        </div>
    </div>

    <div class="container-fluid">
        <h3>Riwayat Bimbingan</h3>
        <div class="row grid md:grid-cols-2 grid-cols-1 items-center justify-center">
            @if ($dataBimbingan)
                @foreach ($logbookBimbingan as $item)
                    <div class="col-xxl-4 col-lg-6">
                        <div class="project-box">
                            <span
                                class="badge 
                                {{ $item->status == 0 ? 'badge-primary' : ($item->status == 1 ? 'badge-success' : ($item->status == 2 ? 'badge-warning' : '')) }}"
                                data-bs-toggle="modal" data-bs-target="#ModalBimbingan" data-id="{{ $item->id ?? '-' }}"
                                style="cursor: pointer; pointer-events: auto;">
                                {{ $item->status == 0 ? 'Pengajuan' : ($item->status == 1 ? 'Acc' : ($item->status == 2 ? 'Revisi' : '-')) }}
                            </span>



                            <h6>{{ $item->kategori }}</h6>
                            <div class="media">
                                <div class="media-body">
                                    <p>{{ $item->status == 0 ? \Carbon\Carbon::parse($item->tgl_pengajuan)->format('Y-m-d') : \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                    </p>
                                </div>
                            </div>
                            <p>{{ $item->keterangan ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"z>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Input Keterangan File</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('dosen.bimbingan.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf    
                        <input class="form-control" name="id" id="idBimbingan" type="hidden">
                        <input class="form-control" name="status" id="status" type="hidden">
                        
                        <div class="form-group">
                            <label for="keteranganInput">Deskripsi</label>
                            <input type="text" class="form-control" id="keteranganInput" name="keterangan" placeholder="Isi keterangan">
                        </div>
                        
                        <div class="form-group">
                            <label for="fileInput">Upload File</label>
                            <input type="file" class="form-control" id="fileInput" name="file">
                        </div>
                        
                        <div class="d-flex justify-content-end w-100 items-end p-3">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                    
                </div>
               
            </div>
        </div>
    </div>
    <!--Centered modal-->
    <div class="modal fade" id="ModalBimbingan" tabindex="-1" role="dialog" aria-labelledby="FormModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Detail Pembimbing</h5>
                </div>
                <div class="modal-body">
                    <p><strong>Tanggal:</strong> <span id="tanggalp">-</span></p>
                    <p><strong>Kategori:</strong> <span id="kategoriLog">-</span></p>
                    <p><strong>Keterangan:</strong> <span id="keterangan">-</span></p>
                    <p><strong>file:</strong> <span id="fileLog">-</span></p>
                </div>
                <div class="modal-body">
                    <p><strong>Pembimbing:</strong> <span id="pembimbing">-</span></p>
                    <p><strong>Tanggal:</strong> <span id="tanggal">-</span></p>
                    <p><strong>Response:</strong> <span id="komentar">-</span></p>
                    <p><strong>file Pembimbing:</strong> <span id="filep">-</span></p>
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
            $('#ModalBimbingan').on('click', function(event) {
                var button = $(event.relatedTarget);
                var noHp = button.data('nohp');
                var email = button.data('email');
                var homebase = button.data('homebase');

                $(this).find('#modalNoHp').text(noHp);
                $(this).find('#modalEmail').text(email);
                $(this).find('#modalHomebase').text(homebase);
            });


                $('.acc').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                $('#idBimbingan').val(id);
                $('#status').val(1);

                $('#modalDetail').modal('show');
            });

            $('.revisi').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');

                $('#idBimbingan').val(id);
                $('#status').val(2);

                $('#modalDetail').modal('show');
            });

        // Mengklik tombol Save di modal
            $('#saveButton').on('click', function() {
                var id = $('#idBimbingan').val();
                var keterangan = $('#keteranganInput').val();
            });


            $('.badge').on('click', function() {
                var id = $(this).data('id');

                if (id) {
                    $.ajax({
                        url: "{{ route('dosen.bimbingan.getModalLogbook', '') }}/" + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                var data = response.data;
                                // Mengisi data ke dalam modal
                                $('#kategoriLog').text(data.kategori ?? '-');
                                $('#keterangan').text(data.keterangan ?? '-');
                                $('#komentar').text(data.komentar ?? '-');
                                $('#pembimbing').text(data.kategori_pembimbing ?? '-');

                                // Mengisi link file
                                $('#fileLog').html(data.file_mhs ?
                                    `<a href="{{ asset('storage/bimbingan_files/') }}/${data.file_mhs}" target="_blank">Download</a>` :
                                    '-');
                                $('#filep').html(data.file_pembimbing ?
                                    `<a href="{{ asset('storage/bimbingan_files/') }}/${data.file_pembimbing}" target="_blank">Download</a>` :
                                    '-');
                                $('#tanggalp').text(data.formatted_created_at ?? '-');
                                $('#tanggal').text(data.formatted_created_at ?? '-');

                                $('#ModalBimbingan').modal('show');
                            } else {
                                alert(response.message);
                            }
                            $('#ModalBimbingan').modal('show');
                        },
                        error: function(xhr) {
                            console.log('Terjadi kesalahan:', xhr.responseText);
                        }
                    });
                }
            });

        });
    </script>
@endsection
