@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Dosbim</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            {{-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="kelMK-tab" href="{{ url('admin/masterdata/kelompok-mk') }}" role="tab" aria-controls="kelMK" aria-selected="true">Kelompok Matakuliah</a></li> --}}
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default " id="masterKur-tabs" href="{{ url('admin/masterdata/kurikulum') }}" role="tab" aria-controls="masterKur" aria-selected="false" tabindex="-1">Mahasiswa Bimbingan</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="MkKur-tab" href="{{ url('admin/masterdata/matakuliah-kurikulum') }}" role="tab" aria-controls="MkKur" aria-selected="false" tabindex="-1">Mahasiswa Sidang</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" href="#" role="tab"  aria-selected="false" tabindex="-1">Dosen Pembimbing</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="masterMK" role="tabpanel" aria-labelledby="masterMK-tab">
                                
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection
{{-- <div class="table-responsive mt-4">
    <table class="display" id="tableMK">
        <thead>
            <tr>
                <th></th>
                <th>Nip</th>
                <th>Nama Dosen</th>
                <th>Sisa Kuota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembimbing as $dosen)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $dosen['kuota'] }}</td>
                    <td>{{ $dosen['nama'] }}</td>
                    <td>{{ $dosen['kuota'] }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class="btn btn-warning btn-sm btn-icon edit-record">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="#" class="btn btn-info btn-sm btn-icon edit-record">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ URL::to('admin/masterdata/matakuliah/delete/'. $dosen['nip']) }}" class="btn btn-danger btn-sm btn-icon edit-record">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}
@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
        $(function() {
            $("#tableMK").DataTable({
                responsive: true
            })
        })
     </script>
@endsection
