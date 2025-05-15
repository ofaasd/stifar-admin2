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
    <h3><a href="{{URL::to('dosen')}}"><i class="fa fa-arrow-left"></i></a> {{$title}}</h3>
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
                            <img class="img-70 rounded-circle" alt="Foto Dosen"
                            src="{{ (!empty($dosen->foto)) ? asset('assets/images/dosen/' . $dosen->foto) : asset('assets/images/user/7.jpg') }}">
                        </div>
                        <div class="media-body">
                          <h5 class="mb-1">{{$dosen->nama_lengkap}}</h5>
                          <h5 class="mb-1">{{$dosen->nidnDosen}}</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                  <form id="form-berkas" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$dosen->idPegawai}}" name="id_pegawai">
                    @if (isset($updateHerregistrasi) && $updateHerregistrasi)
                        <input type="hidden" value="true" name="update_herregistrasi">
                    @endif
                    <div class="row">
                        <p class="fw-bold"><span class="text-danger">*</span>Foto berformat jpg/jpeg dan maksimal berukuran 5mb </p>
                        <small class="text-end">Terakhir diupdate <span class="fst-italic">{{ isset($berkas) ? \Carbon\Carbon::parse($berkas->updated_at)->translatedFormat('d F Y H:i:s') : "data tidak ditemukan" }}</span></small>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div id="view-ktp">
                                    <label class="col-sm-12 col-form-label">Foto KTP : </label>
                                    <p class="fs-4" style="display: {{ $dosen->ktp ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-ktp"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-ktp" style="display: {{ $dosen->ktp ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="ktp" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>
                            
                            <div class="mb-2">
                                <div class="view-kk">
                                    <label class="col-sm-12 col-form-label">Foto KK : </label>
                                    <p class="fs-4" style="display: {{ $dosen->kk ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-kk"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-kk" style="display: {{ $dosen->kk ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="kk" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>
                            
                            <div class="mb-2">
                                <div class="view-ijazah-s1">
                                    <label class="col-sm-12 col-form-label">Foto ijazah S1 : </label>
                                    <p class="fs-4" style="display: {{ $dosen->ijazah_s1 ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-ijazah-s1"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-ijazah-s1" style="display: {{ $dosen->ijazah_s1 ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="ijazah_s1" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="mb-2">
                                <div class="view-ijazah-s2">
                                    <label class="col-sm-12 col-form-label">Foto ijazah S2 : </label>
                                    <p class="fs-4" style="display: {{ $dosen->ijazah_s2 ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-ijazah-s2"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-ijazah-s2" style="display: {{ $dosen->ijazah_s2 ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="ijazah_s2" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="mb-2">
                                <div class="view-ijazah-s3">
                                    <label class="col-sm-12 col-form-label">Foto ijazah S3 : </label>
                                    <p class="fs-4" style="display: {{ $dosen->ijazah_s3 ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-ijazah-s3"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-ijazah-s3" style="display: {{ $dosen->ijazah_s3 ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="ijazah_s3" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="mb-2">
                                <div class="view-serdik-aa-pekerti">
                                    <label class="col-sm-12 col-form-label">Foto Serdik AA Pekerti : </label>
                                    <p class="fs-4" style="display: {{ $dosen->serdik_aa_pekerti ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-serdik-aa-pekerti"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-serdik-aa-pekerti" style="display: {{ $dosen->serdik_aa_pekerti ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="serdik_aa_pekerti" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="mb-2">
                                <div class="view-serdik-aa">
                                    <label class="col-sm-12 col-form-label">Foto Serdik AA : </label>
                                    <p class="fs-4" style="display: {{ $dosen->serdik_aa ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-serdik-aa"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-serdik-aa" style="display: {{ $dosen->serdik_aa ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="serdik_aa" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            
                            </div>

                            <div class="mb-2">
                                <div class="view-serdik-lektor">
                                    <label class="col-sm-12 col-form-label">Foto Serdik lektor : </label>
                                    <p class="fs-4" style="display: {{ $dosen->serdik_lektor ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-serdik-lektor"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-serdik-lektor" style="display: {{ $dosen->serdik_lektor ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="serdik_lektor" class="form-control" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="mb-2">
                                <div class="view-serdik-kepala-guru-besar">
                                    <label class="col-sm-12 col-form-label">Foto Serdik Kepala Guru Besar : </label>
                                    <p class="fs-4" style="display: {{ $dosen->serdik_kepala_guru_besar ? 'block' : 'none' }}">
                                        <i class="fa fa-check-square-o text-success"></i> | 
                                        <a href="#" id="edit-serdik-kepala-guru-besar"><i class="fa fa-pencil text-dark"></i></a>
                                    </p>
                                </div>
                                <div class="col-sm-12" id="input-serdik-kepala-guru-besar" style="display: {{ $dosen->serdik_kepala_guru_besar ? 'none' : 'block' }}">
                                    <div class="input-group">
                                        <input type="file" name="serdik_kepala_guru_besar" class="form-control" aria-describedby="inputGroupPrepend">
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

            $('#edit-serdik-aa-pekerti').on('click', function(e) {
                e.preventDefault();
                $('#input-serdik-aa-pekerti').toggle();
            });

            $('#edit-serdik-aa').on('click', function(e) {
                e.preventDefault();
                $('#input-serdik-aa').toggle();
            });

            $('#edit-serdik-lektor').on('click', function(e) {
                e.preventDefault();
                $('#input-serdik-lektor').toggle();
            });

            $('#edit-serdik-kepala-guru-besar').on('click', function(e) {
                e.preventDefault();
                $('#input-serdik-kepala-guru-besar').toggle();
            });

            // Submit form dengan AJAX
            $('#form-berkas').on('submit', function(e) {
                e.preventDefault();
                $('#btn-submit').prop('disabled', true);
                $('#btn-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');

                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('berkas-dosen') }}',  // URL ke mana data dikirimkan
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
