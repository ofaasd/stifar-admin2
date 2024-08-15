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
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>[{{ $jadwal['kode_matkul'] }}] - {{ $jadwal['nama_matkul'] }}</h5>
                                <h6>{{ $jadwal['hari'] }}, {{ $jadwal['nama_sesi'] }}</h6>
                            </div>
                            <div class="col-sm-6">
                                <!-- <b>Kontrak Kuliah</b>
                                <table>
                                    <tr>
                                        <td>
                                            Persentase Tugas
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_tugas" class="form-control">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Persentase UTS
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_uts" class="form-control">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Persentase UAS
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_uas" class="form-control">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                </table> -->
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="display" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Kehadiran (%)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($daftar_mhs as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row['nim'] }}</td>
                                            <td>{{ $row['nama'] }}</td>
                                            <td></td>
                                            <td>
                                                <a href="{{ url('/dosen/input/'.$row['nim'].'/absensi/'.$row['id_jadwal']) }}" class="btn btn-info btn-sm"><i class="fa fa-list"></i> Input Absensi</a>
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
