@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Setting Pertemuan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                @if($SettingKeuangan->count() == 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Setting Keuangan pada tahun ajaran ini belum dilakukan
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button>
                </div>
                @endif
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h4>Setting Keuangan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form class="d-flex flex-wrap gap-3" method="GET">
                                    @csrf
                                    <label for="tahun_ajaran" class="visually-hidden">Angkatan</label>
                                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-select w-auto">
                                        @foreach($ta_all as $row)
                                        <option value="{{$row->id}}" {{($tahun_ajaran == $row->id)?"selected":""}}>{{substr($row->kode_ta,0,4)}} - {{(substr($row->kode_ta,-1,1) == 1)?"Ganjil":"Genap"}}</option>
                                        @endforeach
                                    </select>
                                    <select name="angkatan" id="angkatan" class="form-select w-auto">
                                        @for($i=date('Y');$i>=date('Y')-5;$i--)
                                            <option value="{{$i}}" {{($angkatan == $i)?"selected":""}}>{{$i}}</option>
                                        @endfor
                                    </select>

                                    <label for="alumni" class="visually-hidden">Alumni</label>
                                    <select name="alumni" id="alumni" class="form-select w-auto">
                                        <option value="1" {{($alumni == 1)?"selected":""}}>Yaphar</option>
                                        <option value="2" {{($alumni == 2)?"selected":""}}>Umum</option>
                                    </select>

                                    <label for="gelombang" class="visually-hidden">Gelombang</label>
                                    <select name="gelombang" id="gelombang" class="form-select w-auto">
                                        <option value="1" {{($gelombang == 1)?"selected":""}}>Gelombang 1</option>
                                        <option value="2" {{($gelombang == 2)?"selected":""}}>Gelombang 2</option>
                                        <option value="3" {{($gelombang == 3)?"selected":""}}>Gelombang 3</option>
                                    </select>
                                    <input type="submit" value="Filter" class="btn btn-primary">
                                    </form>
                            </div>
                        </div>
                        <div class="table-responsive" id="my-table">
                            <form method="POST" action="{{url('admin/keuangan/setting_keuangan')}}">
                            @csrf
                            <input type="hidden" name="tahun_ajaran" value="{{$tahun_ajaran}}">
                            <input type="hidden" name="alumni" value="{{$alumni}}">
                            <input type="hidden" name="gelombang" value="{{$gelombang}}">
                            <input type="hidden" name="angkatan" value="{{$angkatan}}">
                            <table class="table table-stripped" id="pengumuman-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Program Studi</th>
                                        @foreach($jenis as $jen)
                                        <th>{{$jen->nama}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach($prodi as $pro)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$pro->nama_prodi}}</td>
                                            @foreach($jenis as $jen)
                                                <td>
                                                    <input type="hidden" name="jenis[]" value="{{$jen->id}}">
                                                    <input type="hidden" name="prodi[]" value="{{$pro->id}}">
                                                    <input type="number" name="setting_keuangan[]" value="{{$setting_keuangan[$pro->id][$jen->id]}}" class="form-control">
                                                    </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                <input type="submit" name="simpan" value="simpan" class="btn btn-primary col-md-12">
                            </div>
                            <form method="POST" action="{{url('admin/keuangan/setting_keuangan')}}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script>
         $(document).ready(function(){
            // $("#pengumuman-table").DataTable();
         });
    </script>
@endsection
