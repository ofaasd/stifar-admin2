@extends('layouts.master')
@section('title', 'Detail Mahasiswa')

@section('css')
<style>
  .bg-opacity-5 {
      background-color: rgba(0, 0, 0, 0.05); /* 5% opacity */
  }
</style>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3><a href="{{URL::to('mahasiswa')}}"><i class="fa fa-arrow-left"></i></a> {{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Berkas</li>
    <li class="breadcrumb-item active">{{$title}}</li>
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
                      <div class="media">
                        <div class="photo-profile position-relative d-inline-block">
                            <img class="img-70 rounded-circle" alt="Foto Mahasiswa"
                            src="{{ (!empty($berkas->sistem)) ? asset('assets/file/berkas/mahasiswa/sistem/' . $berkas->sistem) : asset('assets/images/user/7.jpg') }}">
                        </div>
                        <div class="media-body">
                          <h5 class="mb-1">{{$mahasiswa->nama}}</h5>
                          <h5 class="mb-1">{{$mahasiswa->nimMahasiswa}}</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                  <form id="form-berkas" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$mahasiswa->nimMahasiswa}}" name="nim">
                    @if (isset($updateHerregistrasi) && $updateHerregistrasi)
                        <input type="hidden" value="true" name="update_herregistrasi">
                    @endif
                    <div class="row">
                        <p class="fw-bold"><span class="text-danger">*</span>Foto berformat jpg/jpeg dan maksimal berukuran 5mb </p>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div id="view-ktp">
                                    <label class="col-sm-12 col-form-label">Foto KTP : </label>
                                    <p class="fs-4" style="display: {{ isset($berkas) ? ($berkas->ktp ? 'block' : 'none') : 'none'}}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="{{ (isset($berkas->ktp)) ? asset('assets/file/berkas/mahasiswa/ktp/' . $berkas->ktp) : '' }}" target="_blank">
                                            <i class="fa fa-picture-o text-dark"></i>
                                        </a> | 
                                        <a href="#" id="edit-ktp"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-ktp" style="display: {{ isset($berkas) ? ($berkas->ktp ? 'none' : 'block') : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="ktp" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>
                            
                            <div class="mb-2">
                                <div class="view-kk">
                                    <label class="col-sm-12 col-form-label">Foto KK : </label>
                                    <p class="fs-4" style="display: {{ isset($berkas) ? ($berkas->kk ? 'block' : 'none') : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="{{ (isset($berkas->kk)) ? asset('assets/file/berkas/mahasiswa/kk/' . $berkas->kk) : '' }}" target="_blank">
                                            <i class="fa fa-picture-o text-dark"></i>
                                        </a> | 
                                        <a href="#" id="edit-kk"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-kk" style="display: {{ isset($berkas) ? ($berkas->kk ? 'none' : 'block') : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="kk" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>
                            
                            <div class="mb-2">
                                <div class="view-akte">
                                    <label class="col-sm-12 col-form-label">Foto Akte : </label>
                                    <p class="fs-4" style="display: {{ isset($berkas) ? ($berkas->akte ? 'block' : 'none') : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="{{ (isset($berkas->akte)) ? asset('assets/file/berkas/mahasiswa/akte/' . $berkas->akte) : '' }}" target="_blank">
                                            <i class="fa fa-picture-o text-dark"></i>
                                        </a> | 
                                        <a href="#" id="edit-akte"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-akte" style="display: {{ isset($berkas) ? ($berkas->akte ? 'none' : 'block') : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="akte" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="view-ijazah">
                                    <label class="col-sm-12 col-form-label">Foto Ijazah : </label>
                                    <p class="fs-4" style="display: {{ isset($berkas) ? ($berkas->ijazah_depan ? 'block' : 'none') : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="{{ (isset($berkas->ijazah_depan)) ? asset('assets/file/berkas/mahasiswa/ijazah_depan/' . $berkas->ijazah_depan) : '' }}" target="_blank">
                                            <i class="fa fa-picture-o text-dark"></i>
                                        </a> | 
                                        <a href="#" id="edit-ijazah-depan"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                    <div class="col mb-3" id="input-ijazah-depan" style="display: {{ isset($berkas) ? ($berkas->ijazah_depan ? 'none' : 'block') : 'block' }}">
                                        <label for="ijazah-depan" class="mb-0">Depan</label>
                                        <div class="input-group" id="ijazah-depan">
                                            <input type="file" name="ijazah_depan" class="form-control" aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                <hr>
                                </div>
                                <div class="col-sm-12">
                                    <p class="fs-4" style="display: {{ isset($berkas) ? ($berkas->ijazah_belakang ? 'block' : 'none') : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="{{ (isset($berkas->ijazah_belakang)) ? asset('assets/file/berkas/mahasiswa/ijazah_belakang/' . $berkas->ijazah_belakang) : '' }}" target="_blank">
                                            <i class="fa fa-picture-o text-dark"></i>
                                        </a> | 
                                        <a href="#" id="edit-ijazah-belakang"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                    <div class="col" id="input-ijazah-belakang" style="display: {{ isset($berkas) ? ($berkas->ijazah_belakang ? 'none' : 'block') : 'block' }}">
                                        <label for="ijazah-belakang" class="mb-0">Belakang</label>
                                        <div class="input-group" id="ijazah-belakang">
                                            <input type="file" name="ijazah_belakang" class="form-control" aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="view-akte">
                                    <label class="col-sm-12 col-form-label">Foto Profil : </label>
                                    <p class="fs-4" style="display: {{ isset($berkas) ? ($berkas->sistem ? 'block' : 'none') : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="{{ (isset($berkas->sistem)) ? asset('assets/file/berkas/mahasiswa/sistem/' . $berkas->sistem) : '' }}" target="_blank">
                                            <i class="fa fa-picture-o text-dark"></i>
                                        </a> | 
                                        <a href="#" id="edit-foto-sistem"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-foto-sistem" style="display: {{ isset($berkas) ? ($berkas->sistem ? 'none' : 'block') : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="sistem" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer mt-5">
                        <button class="btn btn-success" id="btn-submit" type="submit">Simpan</button>
                    </div>
                  </form>
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
            $('#edit-ktp').on('click', function(e) {
                e.preventDefault();
                $('#input-ktp').toggle();
            });

            $('#edit-kk').on('click', function(e) {
                e.preventDefault();
                $('#input-kk').toggle();
            });

            $('#edit-akte').on('click', function(e) {
                e.preventDefault();
                $('#input-akte').toggle();
            });

            $('#edit-ijazah-depan').on('click', function(e) {
                e.preventDefault();
                $('#input-ijazah-depan').toggle();
            });

            $('#edit-ijazah-belakang').on('click', function(e) {
                e.preventDefault();
                $('#input-ijazah-belakang').toggle();
            });

            $('#edit-foto-sistem').on('click', function(e) {
                e.preventDefault();
                $('#input-foto-sistem').toggle();
            });

            // Submit form dengan AJAX
            $('#form-berkas').on('submit', function(e) {
                e.preventDefault();
                $('#btn-submit').prop('disabled', true);
                $('#btn-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');

                var formData = new FormData(this);


                $.ajax({
                    url: '{{ route('store-mhs-berkas') }}',  // URL ke mana data dikirimkan
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        $('#btn-submit').prop('disabled', false);
                        $('#btn-submit').html('Simpan');
                        Swal.fire({
                            icbon: 'success',
                            title: 'Berkas berhasil disimpan',
                            text: response.message,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        $('#btn-submit').prop('disabled', false);
                        $('#btn-submit').html('Simpan');
                        Swal.fire({
                            icon: 'error',
                            title: 'error',
                            text: xhr.responseJSON.message,
                        });
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
