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
                                <p>{{ $skripsi->judul ?? '-' }}</p>
                            </div>
                        </div>
                       

                    
                        <div class="row py-3">
                            <div class="col-md-3">
                                <label>Status</label>
                            </div>
                            <div class="col-md-2">
                                <label>:</label>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Catatan Bimbingan</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionBimbingan">
                        {{-- @forelse($bimbingan->where('status', '!=', 0) as $index => $item) --}}
                        @forelse($bimbingan as $index => $item)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $item->id }}">
                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $item->id }}"
                                        aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $item->id }}">
                                        Bimbingan #{{ $loop->iteration }} -
                                        {{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d F Y') }}
                                        @switch($item->status)
                                            @case(1)
                                                <span class="badge bg-info ms-2">ACC</span>
                                            @break

                                            @case(2)
                                                <span class="badge bg-success ms-2">Disetujui</span>
                                            @break

                                            @case(3)
                                                <span class="badge bg-danger ms-2">Revisi</span>
                                            @break
                                        @endswitch
                                    </button>
                                </h2>
                                <div id="collapse{{ $item->id }}"
                                    class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $item->id }}" data-bs-parent="#accordionBimbingan">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Catatan Mahasiswa:</h6>
                                                <p>{{ $item->catatan_mahasiswa ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Solusi Permasalahan:</h6>
                                                <p>{{ $item->solusi_permasalahan ?? 'Belum ada catatan dari dosen' }}</p>
                                            </div>
                                        </div>
                                        @if ($item->berkas && $item->berkas->count() > 0)
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <h6>File Terkait:</h6>
                                                    <div class="list-group">
                                                        @foreach ($item->berkas as $berkas)
                                                            <a href="{{ asset('storage/' . $berkas->file) }}"
                                                                target="_blank"
                                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                {{ basename($berkas->file) }}
                                                                <span class="badge bg-primary rounded-pill">Unduh</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-muted">Belum ada catatan bimbingan</p>
                                </div>
                            @endforelse
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
