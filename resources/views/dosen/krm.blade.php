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
    <li class="breadcrumb-item">Mahasiswa</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('/dosen/download-krm') }}" class="btn btn-info btn-sm"><i class="fa fa-download"></i> Unduh KRM</a>
                        <div class="table-responsive mt-2">
                            <table class="display" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Jadwal</th>
                                        <th>Hari & Waktu</th>
                                        <th>Matakuliah</th>
                                        <th>Ruang</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Status</th>
                                        <th>T/P</th>
                                        <th>Kuota</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwal as $jad)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $jad['kode_jadwal'] }}</td>
                                            <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                            <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                            <td>{{ $jad['nama_ruang'] }}</td>
                                            <td>{{ $jad['kode_ta'] }}</td>
                                            <td>{{ $jad['status'] }}</td>
                                            <td>{{ $jad['tp'] }}</td>
                                            <td>{{$jumlah_input_krs[$jad['id']]}} / {{ $jad['kuota'] }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    <a href="{{ url('/dosen/absensi/'.$jad['id'].'/input') }}" class="btn btn-info btn-xs"><i class="fa fa-list"></i>Absensi</a>
                                                    <a href="{{ url('/dosen/nilai/'.$jad['id'].'/input') }}" class="btn btn-success btn-xs"><i class="fa fa-inbox"></i>Nilai</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
        })
    </script>
@endsection
