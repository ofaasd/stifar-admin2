@extends('layouts.master')
@section('title', 'Edit Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
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
                            <a class="nav-link" id="v-pills-pengabdian-tab" data-bs-toggle="pill" href="#v-pills-pengabdian" role="tab" aria-controls="v-pills-pengabdian" aria-selected="false">Pengabdian</a>
                            <a class="nav-link" id="v-pills-karya-tab" data-bs-toggle="pill" href="#v-pills-karya" role="tab" aria-controls="v-pills-karya" aria-selected="false">Publikasi </a>
                            <a class="nav-link" id="v-pills-organisasi-tab" data-bs-toggle="pill" href="#v-pills-organisasi" role="tab" aria-controls="v-pills-organisasi" aria-selected="false">Organisasi</a>
                            <a class="nav-link" id="v-pills-repository-tab" data-bs-toggle="pill" href="#v-pills-repository" role="tab" aria-controls="v-pills-repository" aria-selected="false">Repository</a>
                            <a class="nav-link" id="v-pills-pekerjaan-tab" data-bs-toggle="pill" href="#v-pills-pekerjaan" role="tab" aria-controls="v-pills-pekerjaan" aria-selected="false">Pekerjaan</a>
                            <a class="nav-link" id="v-pills-pendidikan-tab" data-bs-toggle="pill" href="#v-pills-pendidikan" role="tab" aria-controls="v-pills-pendidikan" aria-selected="false">Pendidikan</a>
                            <a class="nav-link" id="v-pills-penghargaan-tab" data-bs-toggle="pill" href="#v-pills-penghargaan" role="tab" aria-controls="v-pills-penghargaan" aria-selected="false">Penghargaan</a>
                            <a class="nav-link" id="v-pills-kompetensi-tab" data-bs-toggle="pill" href="#v-pills-kompetensi" role="tab" aria-controls="v-pills-kompetensi" aria-selected="false">Kompetensi</a>
                            <a class="nav-link" id="v-pills-kegiatan_luar-tab" data-bs-toggle="pill" href="#v-pills-kegiatan_luar" role="tab" aria-controls="v-pills-kegiatan_luar" aria-selected="false">Kegiatan di Luar</a>
                            <a class="nav-link" id="v-pills-berkas-tab" data-bs-toggle="pill" href="#v-pills-berkas" role="tab" aria-controls="v-pills-berkas" aria-selected="false">Berkas Pendukung</a>
                        </div>
                        </div>
                        <div class="col-sm-9" style="overflow-x:scroll">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-struktural" role="tabpanel" aria-labelledby="v-pills-struktural-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-fungsional" role="tabpanel" aria-labelledby="v-pills-fungsional-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-mengajar" role="tabpanel" aria-labelledby="v-pills-mengajar-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-penelitian" role="tabpanel" aria-labelledby="v-pills-penelitian-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-pengabdian" role="tabpanel" aria-labelledby="v-pills-pengabdian-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-karya" role="tabpanel" aria-labelledby="v-pills-karya-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-organisasi" role="tabpanel" aria-labelledby="v-pills-organisasi-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-repository" role="tabpanel" aria-labelledby="v-pills-repository-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-pekerjaan" role="tabpanel" aria-labelledby="v-pills-pekerjaan-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-pendidikan" role="tabpanel" aria-labelledby="v-pills-pendidikan-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-penghargaan" role="tabpanel" aria-labelledby="v-pills-penghargaan-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-kompetensi" role="tabpanel" aria-labelledby="v-pills-kompetensi-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-kegiatan_luar" role="tabpanel" aria-labelledby="v-pills-kegiatan_luar-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-berkas" role="tabpanel" aria-labelledby="v-pills-berkas-tab">
                                <div class="loader-box">
                                    <div class="loader-2"></div>
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
</div>
@endsection
@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@include('admin.kepegawaian.pegawai.struktural._script')
@include('admin.kepegawaian.pegawai.fungsional._script')
@include('admin.kepegawaian.pegawai.mengajar._script')
@include('admin.kepegawaian.pegawai.penelitian._script')
@include('admin.kepegawaian.pegawai.pengabdian._script')
@include('admin.kepegawaian.pegawai.karya._script')
@include('admin.kepegawaian.pegawai.organisasi._script')
@include('admin.kepegawaian.pegawai.repository._script')
@include('admin.kepegawaian.pegawai.pekerjaan._script')
@include('admin.kepegawaian.pegawai.pendidikan._script')
@include('admin.kepegawaian.pegawai.penghargaan._script')
@include('admin.kepegawaian.pegawai.kompetensi._script')
@include('admin.kepegawaian.pegawai.kegiatan_luar._script')
@include('admin.kepegawaian.pegawai.berkas._script')


@endsection
