@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
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
          <div class="col-xl-12">
            <form class="card" id="formMahasiswa" action="javascript:void(0)">
              @csrf
              <div class="card-header">
                <h4 class="card-title mb-0">{{!empty($mahasiswa)?'Edit':'Tambah'}} Mahasiswa</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
              </div>
              <div class="card-body">
                  <input type="hidden" name="id" value="{{$mahasiswa->id ?? 0}}">
                  @include('mahasiswa._form_profile')
              </div>
              <div class="card-footer text-end">
                <button class="btn btn-primary update-btn" type="submit">{{!empty($mahasiswa)?'Perbarui Profile':'Simpan Profile'}}</button>
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
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    @include('mahasiswa._script_profile')
@endsection
