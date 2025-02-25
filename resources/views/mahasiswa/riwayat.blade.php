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
    <li class="breadcrumb-item active">Input KRS</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            @foreach($tahun_ajaran as $row)
            <div class="col-xxl-12 col-sm-12 box-col-12">
                <div class="card card-absolute">
                    <div class="card-header bg-primary">
                        <h6>KRS {{$row->keterangan}}</h6>
                    </div>
                    <div class="card-body">
                        <table class="table" id="tablekrs">
                            <thead>
                                <td>No.</td>
                                <td>Kode</td>
                                <td>Nama Matakuliah</td>
                                <td>Kelas</td>
                                <!-- <td>SKS</td> -->
                                <td>Hari, Waktu</td>
                                <td>Ruang</td>
                                <td>SKS</td>
                                <td>RPS</td>
                                {{-- <td>Validasi</td> --}}
                            </thead>
                            <tbody>
                                @php
                                    $total_krs = 0;
                                @endphp
                                @foreach($krs[$row->id] as $row_krs)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $row_krs['kode_matkul'] }}</td>
                                        <td>{{ $row_krs['nama_matkul'] }}</td>
                                        <td>{{ $row_krs['kel'] }}</td>
                                        <!-- <td>{{ $row_krs['sks_teori'] }}T/ {{ $row_krs['sks_praktek'] }}P</td> -->
                                        <td>{{ $row_krs['hari'] }}, {{ $row_krs['nama_sesi'] }}</td>
                                        <td>{{ $row_krs['nama_ruang'] }}</td>
                                        <td>{{ ($row_krs->sks_teori+$row_krs->sks_praktek) }}</td>
                                        <td>
                                          @if(!empty($row_krs->rps))
                                            <a href="{{URL::to('/assets/file/rps/' . $row_krs->rps)}}" class="btn btn-primary" target="_blank">RPS</a>
                                          @endif
                                        </td>
                                        {{-- <td>{!!($row_krs->is_publish == 0)?'<p class="btn btn-secondary" style="font-size:8pt;">Menunggu Validasi Dosen Wali</p>':'<p class="btn btn-success" style="font-size:8pt;">Sudah Divalidasi</p>'!!}</td> --}}
                                    </tr>
                                    @php
                                    $total_krs += ($row_krs->sks_teori+$row_krs->sks_praktek);
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan=6 class="text-center">Total SKS</th>
                                    <th>{{$total_krs}}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
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

@endsection
