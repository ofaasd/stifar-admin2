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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body" style="overflow-x: scroll">
                        <div class="mt-4">
                            @if($permission->krs == 0)
                                <div class="alert alert-danger">Anda belum diizinkan untuk melakukan input krs harap hubungi admin sistem</div>
                            @elseif($prodi->is_krs == 0)
                                <div class="alert alert-danger">Input KRS ditutup</div>
                            @else
                                @if(empty($mk))
                                    <div class="alert alert-danger">Belum ada Kurikulum untuk angkatan anda. Harap hubungi admin</div>
                                @endif
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="matakuliah">Pilih Matakuliah</label>
                                        <input type="number" value="{{ $ta }}" id="ta" hidden="" />
                                        <input type="number" value="{{ $idmhs }}" id="idmhs" hidden="" />
                                        <select name="matakuliah" onchange="getmk()" id="matakuliah" class="form-control js-example-basic-single">
                                            <option value="" selected>Pilih Matakuliah</option>
                                            @if(!empty($mk))
                                                @foreach($mk as $value)
                                                    @foreach($value as $row)
                                                        <option value="{{ $row['id'] }}">Kode Matakuliah : {{ $row['kode_matkul'] }} | Nama Matakuliah : {{ $row['nama_matkul'] }} | Semester : {{ $row['semester'] ?? '-' }} | Status : {{ $row['status_mk'] ?? '-' }}</option>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div id="showJadwal"></div>
                                </div>
                            @endif
                            <?php
                                if(!is_null(Session::get('krs'))){
                                    echo Session::get('krs');
                                    Session::forget('krs');
                                }
                            ?>
                            <div class="mt-4">
                                <h3>KRS diinputkan : </h3>
                                <a href="{{ url('admin/masterdata/krs/admin/download/'.$idmhs) }}" class="btn btn-info btn-sm" style="float: right;"><i class="fa fa-download"></i> Download KRS</a>
                                <div class="mt-2"></div>
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
                                        <td>Validasi</td>
                                        <td>Aksi</td>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_krs = 0;
                                        @endphp
                                        @foreach($krs as $row_krs)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row_krs['kode_jadwal'] }}</td>
                                                <td>{{ $row_krs['nama_matkul'] }}</td>
                                                <td>{{ $row_krs['kel'] }}</td>
                                                <!-- <td>{{ $row_krs['sks_teori'] }}T/ {{ $row_krs['sks_praktek'] }}P</td> -->
                                                <td>{{ $row_krs['hari'] }}, {{ $row_krs['nama_sesi'] }}</td>
                                                <td>{{ $row_krs['nama_ruang'] }}</td>
                                                <td>{{ ($row_krs->sks_teori+$row_krs->sks_praktek) }}</td>
                                                <td>{!!($row_krs->is_publish == 0)?'<p class="btn btn-secondary" style="font-size:8pt;">Menunggu Validasi Dosen Wali</p>':'<p class="btn btn-success" style="font-size:8pt;">Sudah Divalidasi</p>'!!}</td>
                                                <td>
                                                    @if($row_krs->is_publish == 0)
                                                        <a href="{{ url('admin/masterdata/krs/admin/hapus/'.$row_krs['id']) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                </td>
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
            // $("#tablekrs").DataTable({
            //     responsive: true,
            //     pageLength: 15,
            // })
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
