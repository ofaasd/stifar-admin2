@extends('layouts.master')
@section('title', 'Daftar Wisuda')

@section('css')
<style>
        .bg-opacity-5 {
                background-color: rgba(0, 0, 0, 0.05);
        }
</style>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('breadcrumb-title')
<h3>
        <a href="{{ URL::to('mahasiswa') }}"><i class="fa fa-arrow-left"></i></a> {{ $title }}
</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Berkas</li>
<li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
        <div class="edit-profile">
                <div class="row">
                        <div class="col-xl-12">
                                <div class="card">
                                        <div class="card-body">
                                                <div class="row mb-2">
                                                        <div class="profile-title card-header mb-3">
                                                                <div class="media align-items-center">
                                                                        <div class="photo-profile position-relative d-inline-block">
                                                                                <img class="img-70 rounded-circle" alt="Foto Mahasiswa"
                                                                                        src="{{ !empty($mhs->fotoMhs) ? asset('assets/images/mahasiswa/' . $mhs->fotoMhs) : asset('assets/images/user/7.jpg') }}">
                                                                        </div>
                                                                        <div class="media-body ms-3">
                                                                                <h5 class="mb-1">{{ $mhs->nama }}</h5>
                                                                                <h5 class="mb-1">{{ $mhs->nim }}</h5>
                                                                        </div>
                                                                        @if (isset($registered) && $registered)
                                                                                {{-- <div class="ms-auto">
                                                                                        <span class="badge bg-primary">Pendaftaran Wisuda</span>
                                                                                </div> --}}
                                                                        @else
                                                                                <div class="ms-auto">
                                                                                        <span class="badge bg-primary">Gelombang Yudisium: {{ $mhs->namaGelombangYudisium ?? '-' }}</span>
                                                                                </div>
                                                                        @endif
                                                                </div>
                                                        </div>
                                                </div>

                                                @if (isset($registered) && $registered)
                                                         @if ($registered->status == 1)
                                                                <div class="alert alert-success" role="alert">
                                                                        Selamat 🎉 <strong>{{ $mhs->nama }}</strong> Anda telah berhasil mendaftar wisuda.<br>
                                                                        <ul class="mb-2">
                                                                                <li><strong>Nama Gelombang:</strong> {{ $registered->nama ?? '-' }}</li>
                                                                                <li><strong>Tanggal Pemberkasan:</strong> {{ \Carbon\Carbon::parse($registered->tanggal_pemberkasan)->translatedFormat('d F Y') ?? '-' }}</li>
                                                                                <li><strong>Tanggal Gladi:</strong> {{ \Carbon\Carbon::parse($registered->tanggal_gladi)->translatedFormat('d F Y') ?? '-' }}</li>
                                                                                <li><strong>Tempat Pelaksanaan:</strong> {{ $registered->tempat ?? '-' }}</li>
                                                                                <li><strong>Waktu Pelaksanaan:</strong> {{ \Carbon\Carbon::parse($registered->waktu_pelaksanaan)->translatedFormat('d F Y H:i') ?? '-' }}</li>
                                                                        </ul>
                                                                        Mohon tunggu informasi selanjutnya dari panitia.<br>
                                                                </div>
                                                        @else
                                                                <div class="alert alert-{{ $registered->status == 1 ? 'success' : 'secondary' }}" role="alert">
                                                                        Selamat 🎉 <strong>{{ $mhs->nama }}</strong> Anda telah berhasil mengajukan pendaftaran wisuda.<br>
                                                                        <ul class="mb-2">
                                                                                <li><strong>Nama Gelombang:</strong> {{ $registered->nama ?? '-' }}</li>
                                                                                <li><strong>Tanggal Pemberkasan:</strong> {{ \Carbon\Carbon::parse($registered->tanggal_pemberkasan)->translatedFormat('d F Y') ?? '-' }}</li>
                                                                                <li><strong>Tanggal Gladi:</strong> {{ \Carbon\Carbon::parse($registered->tanggal_gladi)->translatedFormat('d F Y') ?? '-' }}</li>
                                                                                <li><strong>Tempat Pelaksanaan:</strong> {{ $registered->tempat ?? '-' }}</li>
                                                                                <li><strong>Waktu Pelaksanaan:</strong> {{ \Carbon\Carbon::parse($registered->waktu_pelaksanaan)->translatedFormat('d F Y H:i') ?? '-' }}</li>
                                                                                <li><strong>Biaya Wisuda:</strong> {{ 'Rp ' . number_format($registered->tarif_wisuda ?? 0, 0, ',', '.') }}</li>
                                                                                <li>
                                                                                        <strong>Status:</strong>
                                                                                        <span class="badge {{ $registered->status == 1 ? 'bg-success' : 'bg-info' }}">
                                                                                                {{ $registered->status == 1 ? 'Sudah diacc' : 'Pengajuan' }}
                                                                                        </span>
                                                                                </li>
                                                                        </ul>
                                                                        @if ($registered->statusPembayaran === 0 || $registered->statusPembayaran === 1)
                                                                            <div class="alert" role="alert">
                                                                                Bukti pembayaran telah diunggah. <a href="{{ asset('assets/upload/mahasiswa/wisuda/bukti-bayar/' . $registered->buktiPembayaran) }}" target="_blank">disini</a>
                                                                            </div>
                                                                        @else
                                                                                Silahkan lanjutkan dengan mengunggah bukti pembayaran.<br>

                                                                                <form id="form-bukti-bayar" method="POST" enctype="multipart/form-data" class="p-3 rounded">
                                                                                        @csrf
                                                                                        <input type="hidden" name="nim" value="{{ $registered->nim }}">
                                                                                        <div class="mb-3 row">
                                                                                                <label for="atas_nama" class="col-sm-3 col-form-label">Atas Nama</label>
                                                                                                <div class="col-sm-9">
                                                                                                        <input type="text" class="form-control" id="atas_nama" name="atas_nama" placeholder="Nama pada rekening" required>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="mb-3 row">
                                                                                                <label for="bank" class="col-sm-3 col-form-label">Bank</label>
                                                                                                <div class="col-sm-9">
                                                                                                        <input type="text" class="form-control" id="bank" name="bank" placeholder="BCA / BRI / Mandiri / dll" required>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="mb-3 row">
                                                                                                <label for="nominal" class="col-sm-3 col-form-label">Nominal</label>
                                                                                                <div class="col-sm-9">
                                                                                                        <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Nominal pembayaran" value="{{ $registered->tarif_wisuda }}" required>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="mb-3 row">
                                                                                                <label for="buktiBayar" class="col-sm-3 col-form-label">Bukti Bayar</label>
                                                                                                <div class="col-sm-9">
                                                                                                        <input type="file" class="form-control" id="buktiBayar" name="bukti_bayar" accept=".jpg,.jpeg,.png,.pdf" required>
                                                                                                        <small class="text-muted">File jpg/jpeg, maksimal 5MB</small>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="text-end">
                                                                                                <button type="submit" class="btn btn-primary">Upload Bukti Bayar</button>
                                                                                        </div>
                                                                                </form>
                                                                        @endif
                                                                </div>
                                                        @endif   
                                                @else
                                                        <form id="form-daftar-wisuda" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" value="{{ $mhs->nim }}" name="nim">

                                                                {{-- Informasi Gelombang --}}
                                                                <div class="row mb-4">
                                                                        <div class="row mb-2">
                                                                                <div class="col">
                                                                                        <h5 class="mb-2">Pilih Gelombang Wisuda</h5>
                                                                                        <select class="form-select" name="gelombang_id" id="gelombang_id" {{ count($gelombangWisuda) == 0 ? 'disabled' : '' }}>
                                                                                                @if(count($gelombangWisuda) > 0)
                                                                                                        @foreach($gelombangWisuda as $row)
                                                                                                                <option value="{{ $row->id }}"> 
                                                                                                                        Wisuda: {{ $row->nama }} | Tempat: {{ $row->tempat }} | Waktu Pelaksanaan: {{ \Carbon\Carbon::parse($row->waktu_pelaksanaan)->translatedFormat('d F Y H:i'); }} | Tanggal Daftar: {{ \Carbon\Carbon::parse($row->mulai_pendaftaran)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($row->selesai_pendaftaran)->translatedFormat('d F Y') }} | Pemberkasan: {{ $row->tanggal_pemberkasan ? \Carbon\Carbon::parse($row->tanggal_pemberkasan)->translatedFormat('d F Y') : '-' }} | Gladi: {{ $row->tanggal_gladi ? \Carbon\Carbon::parse($row->tanggal_gladi)->translatedFormat('d F Y') : '-' }} | Biaya: {{ 'Rp ' . number_format($row->tarif_wisuda ?? 0, 0, ',', '.') }}
                                                                                                                </option>
                                                                                                        @endforeach
                                                                                                @else
                                                                                                        <option value="">Tidak ada gelombang wisuda tersedia</option>
                                                                                                @endif
                                                                                        </select>
                                                                                </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                                <div class="col">
                                                                                        <h5 class="mb-2">Judul Skripsi</h5>
                                                                                        <input type="text" class="form-control" name="judul_skripsi" value="{{ $mhs->judul ?? '' }}" placeholder="Masukkan Judul Skripsi">
                                                                                </div>
                                                                                <div class="col">
                                                                                        <h5 class="mb-2">Judul Skripsi Inggris</h5>
                                                                                        <input type="text" class="form-control" name="judul_skripsi_eng" value="{{ $mhs->judulEng ?? '' }}" placeholder="Masukkan Judul Skripsi Inggris">
                                                                                </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                                <div class="row mb-2">
                                                                                        <div class="col">
                                                                                                <h5 class="mb-2">NIK</h5>
                                                                                                <input type="text" class="form-control" name="no_ktp" value="{{ $mhs->noKtp ?? '' }}" placeholder="Masukkan NIK">
                                                                                        </div>
                                                                                        <div class="col">
                                                                                                <h5 class="mb-2">Tempat Lahir</h5>
                                                                                                <input type="text" class="form-control" name="tempat_lahir" value="{{ $mhs->tempatLahir ?? '' }}" placeholder="Masukkan tempat lahir">
                                                                                        </div>
                                                                                </div>
                                                                                <div class="row mb-2">
                                                                                        <div class="col">
                                                                                                <h5 class="mb-2">Nama Lengkap</h5>
                                                                                                <input type="text" class="form-control" name="nama_lengkap" value="{{ $mhs->nama ?? '' }}" placeholder="Masukkan nama lengkap">
                                                                                        </div>
                                                                                        <div class="col">
                                                                                                <h5 class="mb-2">Tanggal Lahir</h5>
                                                                                                <input type="date" class="form-control" name="tanggal_lahir" value="{{ $mhs->tanggalLahir ?? '' }}">
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                {{-- End Informasi Gelombang --}}

                                                                @if (isset($updateHerregistrasi) && $updateHerregistrasi)
                                                                        <input type="hidden" value="true" name="update_herregistrasi">
                                                                @endif

                                                                <div class="row">
                                                                        <p class="fw-bold"><span class="text-danger">*</span>Foto berformat jpg/jpeg dan maksimal berukuran 5mb </p>
                                                                        <small class="text-end">Terakhir diupdate <span class="fst-italic">{{ isset($berkas) ? \Carbon\Carbon::parse($berkas->updated_at)->translatedFormat('d F Y H:i:s') : "data tidak ditemukan" }}</span></small>
                                                                        <div class="col-md-6">
                                                                                {{-- KTP --}}
                                                                                <div class="mb-2">
                                                                                        <div class="col-sm-12" id="input-ktp">
                                                                                                <div class="input-group">
                                                                                                        <input type="file" name="ktp" class="form-control" aria-describedby="inputGroupPrepend">
                                                                                                </div>
                                                                                        </div>
                                                                                        <hr>
                                                                                </div>
                                                                                {{-- KK --}}
                                                                                <div class="mb-2">
                                                                                        <div class="col-sm-12" id="input-kk">
                                                                                                <div class="input-group">
                                                                                                        <input type="file" name="kk" class="form-control" aria-describedby="inputGroupPrepend">
                                                                                                </div>
                                                                                        </div>
                                                                                        <hr>
                                                                                </div>
                                                                                {{-- Akte --}}
                                                                                <div class="mb-2">
                                                                                        <div class="col-sm-12" id="input-akte">
                                                                                                <div class="input-group">
                                                                                                        <input type="file" name="akte" class="form-control" aria-describedby="inputGroupPrepend">
                                                                                                </div>
                                                                                        </div>
                                                                                        <hr>
                                                                                </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                                {{-- Ijazah Depan --}}
                                                                                <div class="mb-2">
                                                                                        <div class="view-ijazah">
                                                                                                <label class="col-sm-12 col-form-label">Foto Ijazah :</label>
                                                                                                <div class="col mb-3" id="input-ijazah-depan">
                                                                                                        <label for="ijazah-depan" class="mb-0">Depan</label>
                                                                                                        <div class="input-group" id="ijazah-depan">
                                                                                                                <input type="file" name="ijazah_depan" class="form-control" aria-describedby="inputGroupPrepend">
                                                                                                        </div>
                                                                                                </div>
                                                                                                <hr>
                                                                                        </div>
                                                                                        {{-- Ijazah Belakang --}}
                                                                                        <div class="col-sm-12">
                                                                                                <div class="col" id="input-ijazah-belakang">
                                                                                                        <label for="ijazah-belakang" class="mb-0">Belakang</label>
                                                                                                        <div class="input-group" id="ijazah-belakang">
                                                                                                                <input type="file" name="ijazah_belakang" class="form-control" aria-describedby="inputGroupPrepend">
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="card-footer mt-5">
                                                                        <button class="btn btn-success" id="btn-submit" type="submit">Simpan</button>
                                                                </div>
                                                        </form>
                                                @endif
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>
@endsection

@section('script')
<script>
        $(document).ready(function() {
                // Submit form dengan konfirmasi
                $('#form-daftar-wisuda').on('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                                title: 'Konfirmasi',
                                text: 'Apakah Anda yakin ingin menyimpan data?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Simpan',
                                cancelButtonText: 'Batal'
                        }).then((result) => {
                                if (result.isConfirmed) {
                                        $('#btn-submit').prop('disabled', true);
                                        $('#btn-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                                        
                                        var formData = new FormData(this);

                                        // Uncomment AJAX for actual use
                                        $.ajax({
                                            url: '{{ route('mhs.akademik.daftar-wisuda.store') }}',
                                            type: 'POST',
                                            data: formData,
                                            contentType: false,
                                            processData: false,
                                            success: function(response) {
                                                $('#btn-submit').prop('disabled', false);
                                                $('#btn-submit').html('Simpan');
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berkas berhasil disimpan',
                                                    text: response.message,
                                                    timer: 1500
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            },
                                            error: function(xhr) {
                                                // console.log('====================================');
                                                // console.log(xhr);
                                                // console.log('====================================');
                                                $('#btn-submit').prop('disabled', false);
                                                $('#btn-submit').html('Simpan');
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'error',
                                                    text: xhr.responseJSON.message,
                                                });
                                            }
                                        });
                                }
                        });
                });

                $('#form-bukti-bayar').on('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                                title: 'Konfirmasi',
                                text: 'Apakah Anda yakin ingin mengupload bukti bayar?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Upload',
                                cancelButtonText: 'Batal'
                        }).then((result) => {
                                if (result.isConfirmed) {
                                        $('#btn-submit').prop('disabled', true);
                                        $('#btn-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                                        
                                        var formData = new FormData(this);

                                        // Uncomment AJAX for actual use
                                        $.ajax({
                                            url: '{{ route('mhs.akademik.daftar-wisuda.upload-bukti-bayar') }}',
                                            type: 'POST',
                                            data: formData,
                                            contentType: false,
                                            processData: false,
                                            success: function(response) {
                                                $('#btn-submit').prop('disabled', false);
                                                $('#btn-submit').html('Simpan');
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Upload bukti bayar berhasil.',
                                                    text: response.message,
                                                    timer: 1500
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            },
                                            error: function(xhr) {
                                                // console.log('====================================');
                                                // console.log(xhr);
                                                // console.log('====================================');
                                                $('#btn-submit').prop('disabled', false);
                                                $('#btn-submit').html('Simpan');
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'error.',
                                                    text: xhr.responseJSON.message,
                                                });
                                            }
                                        });
                                }
                        });
                });
        });
</script>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
