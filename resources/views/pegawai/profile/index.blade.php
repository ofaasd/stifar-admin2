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
                                <div class="photo-profile">
                                    <img class="img-70 rounded-circle" alt="" src="{{ (!empty($pegawai->foto))?asset('assets/images/pegawai/' . $pegawai->foto):asset('assets/images/user/7.jpg') }}">
                                </div>
                              <div class="media-body">
                                <h5 class="mb-1">{{$pegawai->gelar_depan}} {{$pegawai->nama_lengkap}} {{$pegawai->gelar_belakang}}</h5>
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
                                    <a href="{{url('cetak_cv')}}" class="btn btn-primary btn-block"><i class="fa fa-print"></i> Cetak CV</a>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <a href="{{url('cetak_cv_excel')}}" class="btn btn-primary btn-block"><i class="fa fa-print"></i> Cetak CV Excel</a>
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
                      <button class="btn btn-primary update-btn" type="submit">Update Profile</button>
                    </div>
                  </form>
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
@endsection
