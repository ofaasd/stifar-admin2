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
            @role('super-admin')
            <div class="col-md-12 project-list">
                <div class="card">
                   <div class="row">
                      <div class="col-md-12">
                         <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                            <li class="nav-item"><a href="{{URL::to('admin/akademik/nilai')}}" class="nav-link {{($id_prodi==0)?"active":""}}" ><i data-feather="target"></i>All</a></li>
                            @foreach($prodi as $prod)
                                <li class="nav-item"><a href="{{URL::to('admin/akademik/nilai/prodi/' . $prod->id)}}" class="nav-link {{($id_prodi==$prod->id)?"active":""}}" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} </a></li>
                            @endforeach
                         </ul>
                      </div>
                   </div>
                </div>
            </div>
            @endrole
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="jadwalHarian" role="tabpanel" aria-labelledby="jadwalHarian-tab">
                                <div class="table-responsive mt-2">
                                    <div class="row">
                                        <div id="vJadwalHarian" class="mt-2">
                                            <table class="display" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Kode Jadwal</th>
                                                        <th>Hari & Waktu</th>
                                                        <th>Dosen pengampu</th>
                                                        <th>Matakuliah</th>
                                                        <th>Ruang</th>
                                                        <th>T/P</th>
                                                        <th>Jumlah Mhs</th>
                                                        <th>Jumlah Nilai</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jadwal as $jad)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jad['kode_jadwal'] }}</td>
                                                            <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                                            <td>{{ $jad['nama_dosen'] }}</td>
                                                            <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                                            <td>{{ $jad['nama_ruang'] }}</td>
                                                            <td>{{ $jad['tp'] }}</td>
                                                            <td>{{ $jumlah_input_krs[$jad['id']] }} </td>
                                                            <td><a href="#" class="btn {{($jumlah_input_krs[$jad['id']] == $nilaiCek[$jad['id']] ) ? 'btn-success' : 'btn-danger'}}">{{ $nilaiCek[$jad['id']]}}</a></td>
                                                            <td>
                                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
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
