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
    <li class="breadcrumb-item active">KHS</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>Daftar Nilai Mahasiswa</b></h5>
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
                                        <th>Nilai Akhir</th>
                                    </thead>
                                    <tbody>
                                        @php $total_sks = 0; $total_ips = 0; @endphp
                                        @foreach($get_nilai as $row_krs)
                                            @php $total_sks += ($row_krs->sks_teori+$row_krs->sks_praktek);@endphp
                                            <tr>
                                                <td>{{ $row_krs['kode_matkul'] }}</td>
                                                <td>{{ $row_krs['nama_matkul'] }}</td>
                                                <td>{{ ($row_krs->sks_teori+$row_krs->sks_praktek) }}</td>
                                                <td>
                                                    @if($row_krs->validasi_tugas == 1 && $row_krs->validasi_uts == 1 && $row_krs->validasi_uas == 1)
                                                        @php $total_ips +=  ($row_krs->sks_teori+$row_krs->sks_praktek) * $kualitas[$row_krs['nhuruf']]; @endphp
                                                        {{ $row_krs['nakhir']}} | {{ $row_krs['nhuruf']}}
                                                    @else
                                                        - | -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan=2></td>
                                            <th>{{$total_sks}}</th>
                                            <th>IPK : {{$total_ips / $total_sks}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
