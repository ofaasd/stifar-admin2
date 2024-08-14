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
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Asal Sekolah PMB</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-4">
                            @if($permission->krs == 0)
                                <div class="alert alert-danger">Anda belum diizinkan untuk melakukan input krs harap hubungi admin sistem</div>
                            @else
                                @if($mk == 0)
                                    <div class="alert alert-danger">Belum ada Kurikulum untuk angkatan anda. Harap hubungi admin</div>
                                @endif
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="matakuliah">Pilih Matakuliah</label>
                                    <input type="number" value="{{ $ta }}" id="ta" hidden="" />
                                    <input type="number" value="{{ $idmhs }}" id="idmhs" hidden="" />
                                    <select name="matakuliah" onchange="getmk()" id="matakuliah" class="form-control js-example-basic-single">
                                        <option value="" disabled selected>Pilih Matakuliah</option>
                                        @if($mk != 0)
                                            @foreach($mk as $row)
                                                <option value="{{ $row['id'] }}">Kode Matakuliah : {{ $row['kode_matkul'] }} | Nama Matakuliah : {{ $row['nama_matkul'] }} | Semester : {{ $row['semester'] ?? '-' }} | Status : {{ $row['status_mk'] ?? '-' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div id="showJadwal"></div>
                            </div>
                            <?php
                                if(!is_null(Session::get('krs'))){
                                    echo Session::get('krs');
                                    Session::forget('krs');
                                }
                            ?>
                            <div class="mt-4">
                                <h3>KRS diinputkan : </h3>
                                <table class="table" id="tablekrs">
                                    <thead>
                                        <td>No.</td>
                                        <td>Kelas</td>
                                        <td>Nama Matakuliah</td>
                                        <!-- <td>SKS</td> -->
                                        <td>Hari, Waktu</td>
                                        <td>Ruang</td>
                                        <td>Aksi</td>
                                    </thead>
                                    <tbody>
                                        @foreach($krs as $row_krs)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row_krs['kel'] }}</td>
                                                <td>{{ $row_krs['nama_matkul'] }}</td>
                                                <!-- <td>{{ $row_krs['sks_teori'] }}T/ {{ $row_krs['sks_praktek'] }}P</td> -->
                                                <td>{{ $row_krs['hari'] }}, {{ $row_krs['nama_sesi'] }}</td>
                                                <td>{{ $row_krs['nama_ruang'] }}</td>
                                                <td>
                                                    <a href="{{ url('admin/masterdata/krs/admin/hapus/'.$row_krs['id']) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
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

    <script>
        $(function() {
            $("#tablekrs").DataTable({
                responsive: true
            })
        })
        function getmk(){
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/admin/masterdata/krs/list-jadwal',
                type: 'post',
                data: {
                    id_mk: $('#matakuliah').val(),
                    ta: $('#ta').val(),
                    idmhs: $('#idmhs').val(),
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    $("#showJadwal").html(res)
                }
            })
        }
    </script>
@endsection
