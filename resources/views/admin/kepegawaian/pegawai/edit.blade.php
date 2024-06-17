@extends('layouts.master')
@section('title', 'Edit Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit Pegawai</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pegawai</li>
<li class="breadcrumb-item active">Edit Pegawai</li>
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
                                <img class="img-70 rounded-circle" alt="" src="{{ (!empty($pegawai->foto))?asset('assets/images/pegawai/' . $pegawai->foto):asset('assets/images/user/7.jpg') }}">
                              <div class="media-body">
                                <h5 class="mb-1">{{$pegawai->nama_lengkap}}</h5>
                                <p>{{$posisi[$pegawai->id_posisi_pegawai]}}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- <div class="mb-3">
                          <h6 class="form-label">Bio</h6>
                          <textarea class="form-control" rows="5">On the other hand, we denounce with righteous indignation</textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Alamat Email</label>
                          <input class="form-control" placeholder="your-email@domain.com" value="{{$pegawai->email1}}">
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Ubah Password</label>
                          <input class="form-control" type="password" value="password">
                        </div>
                         <div class="mb-3">
                          <label class="form-label">Website</label>
                          <input class="form-control" placeholder="http://Uplor .com">
                        </div> --}}
                        <div class="form-footer">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <a href="#" class="btn btn-primary btn-block" data-bs-toggle="modal" data-original-title="test" data-bs-target="#ubahPasswordModal"><i class="fa fa-key"></i> Ubah Password</a>
                                    @include('admin.kepegawaian.pegawai._form_ubah_password')
                                </div>
                                <div class="col-md-12 mb-4">
                                    <a href="#" class="btn btn-primary btn-block" data-bs-toggle="modal" data-original-title="test" data-bs-target="#ubahFotoModal"><i class="fa fa-image"></i> Ubah Foto</a>
                                    @include('admin.kepegawaian.pegawai._form_ubah_gambar ')
                                </div>
                                <div class="col-md-12 mb-4">
                                    <a href="#" class="btn btn-primary btn-block"><i class="fa fa-print"></i> Cetak CV</a>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <a href="#" class="btn btn-primary btn-block"><i class="fa fa-print"></i> Cetak CV Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-8">
                  <form class="card" id="formPegawai" action="javascript:void(0)">
                    @csrf
                    <div class="card-header">
                      <h4 class="card-title mb-0">Edit Profile</h4>
                      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id" value="{{$id}}">
                        @include('admin.kepegawaian.pegawai._form_profile')
                    </div>
                    <div class="card-footer text-end">
                      <button class="btn btn-primary" type="submit">Update Profile</button>
                    </div>
                  </form>
                </div>
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title mb-0">Riwayat</h4>
                      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                          <div class="col-sm-3 tabs-responsive-side">
                            <div class="nav flex-column nav-pills border-tab nav-left" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="v-pills-struktural-tab" data-bs-toggle="pill" href="#v-pills-struktural" role="tab" aria-controls="v-pills-struktural" aria-selected="true">Jabatan Struktural</a>
                                <a class="nav-link" id="v-pills-fungsional-tab" data-bs-toggle="pill" href="#v-pills-fungsional" role="tab" aria-controls="v-pills-fungsional" aria-selected="false">Jabatan Fungsional</a>
                                <a class="nav-link" id="v-pills-mengajar-tab" data-bs-toggle="pill" href="#v-pills-mengajar" role="tab" aria-controls="v-pills-mengajar" aria-selected="false">Mengajar</a>
                                <a class="nav-link" id="v-pills-penelitian-tab" data-bs-toggle="pill" href="#v-pills-penelitian" role="tab" aria-controls="v-pills-penelitian" aria-selected="false">Penelitian</a>
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="tab-content" id="v-pills-tabContent">
                              <div class="tab-pane fade show active" id="v-pills-struktural" role="tabpanel" aria-labelledby="v-pills-struktural-tab">
                                @include('admin.kepegawaian.pegawai.struktural')
                              </div>
                              <div class="tab-pane fade" id="v-pills-fungsional" role="tabpanel" aria-labelledby="v-pills-fungsional-tab">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
                              </div>
                              <div class="tab-pane fade" id="v-pills-mengajar" role="tabpanel" aria-labelledby="v-pills-mengajar-tab">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
                              </div>
                              <div class="tab-pane fade" id="v-pills-penelitian" role="tabpanel" aria-labelledby="v-pills-penelitian-tab">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
@include('admin.kepegawaian.pegawai._script_profile')
@include('admin.kepegawaian.pegawai._script_user_update')
@include('admin.kepegawaian.pegawai._script_foto_update')
<script>
    $(document).ready(function(){
        const id = {{$id}};
        $.ajax({
            url:'{{URL::to('admin/kepegawaian/struktural')}}',
            method:'GET',
            data:{id : id},
            success:function(data){
                $("#v-pills-struktural").html(data);
            },
            error: function error(err) {
                offCanvasForm.offcanvas('hide');
                swal({
                title: 'Duplicate Entry!',
                text: 'Data Not Saved !',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
                });
            }
        });
    });
</script>

@endsection
