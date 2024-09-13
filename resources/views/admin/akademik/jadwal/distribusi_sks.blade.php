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
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">   
        <div class="row">
            @include('admin.akademik.jadwal.note')
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">

                    </div>
                    <div class="card-body">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterJadwal-tab" href="{{ url('/admin/masterdata/jadwal') }}" role="tab" aria-controls="masterJadwal" aria-selected="true">Jadwal Matakuliah</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="jadwalHarian-tab" href="{{ url('/admin/masterdata/jadwal-harian') }}" aria-controls="jadwalHarian" aria-selected="false">Jadwal Harian</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="distribusi-sks-tab" href="#" aria-controls="distribusiSks" aria-selected="false">Distribusi SKS</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="distribusiSks" role="tabpanel" aria-labelledby="distribusi-sks-tab">
                                <div class="table-responsive mt-2">
                                    <div class="row">
                                        @csrf
                                        {{-- <div class="col-sm-6">
                                            <label for="hari">Pilih Hari</label>
                                            <select name="hari" id="hari" class="form-control">
                                                <option value="0">Semua Hari</option>
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jum'at">Jum'at</option>
                                                <option value="Sabtu">Sabtu</option>
                                                <option value="Minggu">Minggu</option>
                                            </select>
                                        </div> --}}
                                        {{-- <div class="col-sm-6">
                                            <label for="matakuliah">Pilih MataKuliah</label>
                                            <select name="matakuliah" id="matakuliah" class="form-control">
                                                    <option value="0">Semua Matakuliah</option>
                                                @foreach($mk as $mk)
                                                    <option value="{{ $mk['id'] }}">{{ $mk['kode_matkul'] }} - {{ $mk['nama_matkul'] }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        {{-- <div class="col-sm-6 mt-2">
                                            <button onclick="JadwalHarian()" class="btn btn-primary btn-sm">Cari</button>
                                        </div> --}}
                                        <div id="vDistribusiSks" class="mt-2">
                                            <table class="display" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama Dosen</th>
                                                        <th>Total SKS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($distribusiByDosen as $row)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $row['nama_dosen'] }}</td>
                                                            <td>{{ $row['total_sks'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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

    <script>
        const baseUrl = {!! json_encode(url('/')) !!};
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
            $("#myTable1").DataTable({
                responsive: true
            })
        })
    </script>
@endsection
