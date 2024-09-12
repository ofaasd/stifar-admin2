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
    <li class="breadcrumb-item">Mahasiswa</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
          <div class="col-xl-4">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">My Profile</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
              </div>
              <div class="card-body">
                  <div class="row mb-2">
                    <div class="profile-title">
                      <div class="media">
                        <div class="photo-profile position-relative d-inline-block">
                            <img class="img-70 rounded-circle" alt="Foto Mahasiswa"
                                src="{{ (!empty($mahasiswa->foto_mhs)) ? asset('assets/images/mahasiswa/' . $mahasiswa->foto_mhs) : asset('assets/images/user/7.jpg') }}">
                                <a href="#" class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-5 text-white p-2 rounded-circle d-none hover-edit-foto" data-bs-toggle="modal" data-original-title="test" data-bs-target="#ubahFotoModal">
                                  ubah foto
                              </a>
                        </div>
                        <div class="media-body">
                          <h5 class="mb-1">{{$mahasiswa->nama}}</h5>
                          <p>{{$prodi[$mahasiswa->id_program_studi]}} <br /> {{$user->email}}</p>
                        </div>
                      </div>
                      <div class="d-flex align-items-center">
                        <h6 class="mb-0 me-2">Dosen Wali:</h6>
                        <p class="mb-0">{{ $mahasiswa->dosenWali }}</p>
                      </div>
                    </div>
                  </div>
                  {{-- <div class="mb-3">
                    <h6 class="form-label">Bio</h6>
                    <textarea class="form-control" rows="5">On the other hand, we denounce with righteous indignation</textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input class="form-control" placeholder="your-email@domain.com" value="{{$mahasiswa->email1}}">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Ubah Password</label>
                    <input class="form-control" type="password" value="password">
                  </div>
                   <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input class="form-control" placeholder="http://Uplor .com">
                  </div> --}}
                  {{-- <div class="form-footer"> --}}
                      {{-- <div class="row"> --}}
                          {{-- <div class="col-md-6 mb-4"> --}}
                              {{-- <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-original-title="test" data-bs-target="#ubahPasswordModal"><i class="fa fa-key"></i></a> --}}
                            {{-- </div> --}}
                            @include('mahasiswa._form_ubah_password')
                            @include('mahasiswa._form_ubah_gambar')
                          {{-- <div class="col-md-6 mb-4"> --}}
                              {{-- <a href="#" class="btn btn-primary btn-sm btn-block" data-bs-toggle="modal" data-original-title="test" data-bs-target="#ubahFotoModal"><i class="fa fa-image"></i> Ubah Foto</a> --}}
                          {{-- </div> --}}
                      {{-- </div> --}}
                  {{-- </div> --}}
              </div>
            </div>
            <div class="row text-center">
              <div class="col-10">
                <div class="card">
                  <div class="card-body p-2">
                    <div class="d-inline mb-2">
                      <a href="#"><span class="badge badge-{{ empty($mahasiswa->foto_kk) ? 'danger' : 'success'}}">KK <i class="fa fa-{{ empty($mahasiswa->foto_kk) ? 'times' : 'check'}}"></i></span></a>
                      <a href="#"><span class="badge badge-{{ empty($mahasiswa->foto_ktp) ? 'danger' : 'success'}}">KTP <i class="fa fa-{{ empty($mahasiswa->foto_ktp) ? 'times' : 'check'}}"></i></span></a>
                      <span class="badge text-dark shadow-sm">Ijazah : <a href="#" class="text-dark">Depan <i class="fa fa-{{ empty($mahasiswa->ijazah_depan) ? 'times text-danger' : 'check text-success'}}"></i></a>, <a href="#" class="text-dark">Belakang <i class="fa fa-{{ empty($mahasiswa->ijazah_belakang) ? 'times text-danger' : 'check text-success'}}"></i></a></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="card">
                  <div class="card-body p-3">
                    <p>Bayar Tagihan :
                      <span class="badge badge-{{ $statusTagihan ? 'success' : 'danger' }}"><i class="fa fa-{{ $statusTagihan ? 'check' : 'times' }}"></i></span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card">
                  <div class="card-body p-3">
                    <p>SKS Tempuh : {{ $sksTempuh }}</p>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card">
                  <div class="card-body p-3">
                    <p>IPK : {{ $ipk }}</p>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card">
                  <div class="card-body p-3">
                    <p>SKS Aktif : {{ $sksAktif }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-8"> 
            <form class="card" id="formMahasiswa" action="javascript:void(0)">
              @csrf
              <div class="card-header">
                <h4 class="card-title mb-0">Edit Profile</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
              </div>
              <div class="card-body">
                  <input type="hidden" name="id" value="{{$mahasiswa->id}}">
                  @include('mahasiswa._form_profile')
              </div>
              <div class="card-footer text-end">
                <button class="btn btn-primary update-btn" type="submit">Update Profile</button>
              </div>
            </form>
          </div>
        </div>
      </div>
</div>
@endsection

@section('script')
<script>
  $('.photo-profile').hover(function() {
      $(this).find('.hover-edit-foto').removeClass('d-none');
  }, function() {
      $(this).find('.hover-edit-foto').addClass('d-none');
  });

</script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    @include('mahasiswa._script_profile')
    @include('mahasiswa._script_user_update')
    @include('mahasiswa._script_foto_update')
@endsection
