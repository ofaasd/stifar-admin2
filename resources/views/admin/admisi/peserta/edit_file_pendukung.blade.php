@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3> <a href='{{URL::to('admin/admisi/peserta')}}' class='btn btn-primary'><i class='fa fa-arrow-left'></i> Back</a> {{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Edit Peserta Didik</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            @include('admin/admisi/peserta/menu_edit')
        </div>
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>File Pendukung</h3>
                </div>

                <div class="card-body">
                    <form action="{{URL::to('admin/admisi/peserta/' . $id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="put" />
                        <input type="hidden" name="id" value="{{$peserta->id}}">
                        <input type="hidden" name="action" value="{{$action}}">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="mb-2">
                                    <label for="info_pmb">Dapat Info PMB darimana?</label>
                                    <select name="info_pmb" class="form-control">
                                        <option value="">- Pilih -</option>
                                        <option value="1" {{($peserta->info_pmb == 1)?"selected":""}}>Teman</option>
                                        <option value="2" {{($peserta->info_pmb == 2)?"selected":""}}>Kerabat / Orang Tua</option>
                                        <option value="3" {{($peserta->info_pmb == 3)?"selected":""}}>Sosial Media</option>
                                        <option value="4" {{($peserta->info_pmb == 4)?"selected":""}}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="info_pmb">Ukuran Seragam</label>
                                    <select name="ukuran_seragam" class="form-control">
                                        <option value="">- Pilih Ukuran Seragam -</option>
                                        <option value="S" {{($peserta->ukuran_seragam == 'S')?"selected":""}}>S</option>
                                        <option value="M" {{($peserta->ukuran_seragam == 'M')?"selected":""}}>M</option>
                                        <option value="L" {{($peserta->ukuran_seragam == 'L')?"selected":""}}>L</option>
                                        <option value="XL" {{($peserta->ukuran_seragam == 'XL')?"selected":""}}>XL</option>
                                        <option value="XXL" {{($peserta->ukuran_seragam == 'XXL')?"selected":""}}>XXL</option>
                                        <option value="XXXL" {{($peserta->ukuran_seragam == 'XXXL')?"selected":""}}>XXXL</option>
                                      </select>
                                </div>
                                <div class="mb-2">
                                    <label for="info_pmb">Upload File Pendukung</label>
                                    <input type='file' class="form-control" name="foto" />
                                    <p>Maksimal 5 MB dengan format pdf.</p>
                                    <a href="{{(!empty($peserta->file_pendukung))?asset('assets/pdf/pmb/' . $peserta->file_pendukung ):""}}" class="btn btn-primary" target="_blank">Lihat File Upload</a>
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <input type="submit" value="Simpan" class="btn btn-primary col-md-12">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
