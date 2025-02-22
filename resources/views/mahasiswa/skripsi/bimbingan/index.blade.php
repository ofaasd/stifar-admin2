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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"
                        role="tab" aria-controls="home" aria-selected="true">Data</a></li>

                <li class="nav-item"><a class="nav-link" id="profile-tabs" data-bs-toggle="tab" href="#dosbim1"
                        role="tab" aria-controls="dosbim1" aria-selected="false">Pembimbing 1</a></li>
                <li class="nav-item"><a class="nav-link" id="pembimbing1" data-bs-toggle="tab" href="#dosbim2"
                        role="tab" aria-controls="dosbim2" aria-selected="false">Pembimbing 2</a></li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="col-sm-12 col-lg-10 col-xl-8">
                        <div class="row py-3 mt-3">
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
                        <div class="row py-3">
                            <div class="col-md-7">
                                <label>Percetakan</label>
                                <p>
                                    <a href="{{ Route('mhs.skripsi.berkas.BerkasBimbingan') }}" class="btn rounded-pill btn-success btn-xs txt-white">
                                        <i class="fa fa-print"></i> Nota Pembimbing
                                    </a>
                                    <a href="{{ Route('mhs.skripsi.berkas.BerkasLogbook') }}" class="btn rounded-pill btn-success btn-xs txt-white">
                                    <i class="fa fa-print"></i> Logbook bimbingan
                                   </a>
                                </p>
                            </div>
                                
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="dosbim1" role="tabpanel" aria-labelledby="pembimbing1">
                    <div class="col-sm-12 col-lg-10 col-xl-8">
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Nama Pembimbing</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing1->nama_lengkap ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>NIDN</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing1->nidn ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>NPP</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing1->npp ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Nomor HP</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing1->nohp ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Email</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing1->email1 ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Homebase</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing1->homebase ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="dosbim2" role="tabpanel" aria-labelledby="pembimbing2">
                    <div class="col-sm-12 col-lg-10 col-xl-8">
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Nama Pembimbing</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing2->nama_lengkap ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>NIDN</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing2->nidn ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>NPP</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing2->npp ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Nomor HP</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing2->nohp ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Email</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing2->email1 ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Homebase</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $pembimbing2->homebase ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

        <div class="d-flex justify-content-between items-center p-2">
            <h3>Riwayat Bimbingan</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#FormModal">Upload Dokumen</button>
        </div>

    <div class="row">
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


    <!--Centered modal-->
    <div class="modal fade" id="FormModal" tabindex="-1" role="dialog" aria-labelledby="FormModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="row g-3 needs-validation custom-input" method="POST"
                        action="{{ Route('mhs.bimbingan.UploadBimbingan') }}" enctype="multipart/form-data">>
                        @csrf
                        <div class="col-md-12 position-relative">
                            <label class="form-label" for="validationTooltip03">Kategori</label>
                            <select class="form-control" name="kategori" id="kategori">
                                <option disabled selected>Pilih Kategori</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="Bab {{ $i }}">Bab {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="number" class="d-none" name="id_master_bimbingan"
                            value="{{ $dataMaster?->id ?? '-' }}">
                        {{-- <input type="number" class="d-none" name="id_master_bimbingan" value="{{$dataMaster??->id : '-'}}"> --}}

                        <div class="col-md-12 position-relative " id="file">
                            <label class="form-label" for="validationTooltip03">File</label>
                            <input class="form-control" name="file" id="fileValue" type="file">
                        </div>
                        <div class="col-md-12 position-relative " id="judul">
                            <label class="form-label" for="validationTooltip03">keterangan</label>
                            <input class="form-control " name="keterangan" id="JudulValue" type="text">
                        </div>

                        <div class="col-6">
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
                    <p><strong>Tanggal:</strong> <span id="tanggal">-</span></p>
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

            $('.badge').on('click', function() {
                var id = $(this).data('id');

                if (id) {
                    $.ajax({
                        url: "{{ route('mhs.bimbingan.getModalLogbook', '') }}/" + id,
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
