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
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item active">Riwayat KHS</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row g-3">
            <!-- Zero Configuration  Starts-->
            @foreach($tahun_ajaran as $row_ta)
            @php $ta = $row_ta->id @endphp
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>{{$row_ta->keterangan}}</b></h5>
                            </div>
                            <div class="col-md-6">
                                {{-- <a href="{{ url('mhs/cetak_khs/')}}/{{$mhs->nim ?? ''}}" class="btn btn-info btn-sm" style="float: right;"><i class="fa fa-download"></i> Download KHS</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0">
                        <div class="mt-4">
                            <div class="mt-4">

                                <div class="mt-2"></div>
                                <table class="table table-hover table-border-horizontal mb-3" id="tablekrs">
                                    <thead>
                                        <th>Kode</th>
                                        <th>Nama Matakuliah</th>
                                        <th>SKS</th>
                                        <th>Tugas</th>
                                        <th>UTS</th>
                                        <th>UAS</th>
                                        <th>Nilai Akhir</th>
                                    </thead>
                                    <tbody>
                                        @foreach($krs as $row_krs)
                                            <tr>
                                                <td>{{ $row_krs['kode_matkul'] }}</td>
                                                <td>{{ $row_krs['nama_matkul'] }}</td>
                                                <td>{{ ($row_krs->sks_teori+$row_krs->sks_praktek) }}</td>
                                                <td>{{ ($nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] == 0)?"-":$nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_tgs']}}</td>
                                                <td>{{ ($nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uts'] == 0)?"-":$nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uts']}}</td>
                                                <td>{{ ($nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uas'] == 0)?"-":$nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uas']}}</td>
                                                <td>{{ $nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_akhir']}} | {{ $nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_huruf']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
