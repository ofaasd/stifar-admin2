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
    <li class="breadcrumb-item">Pewalian</li>
    <li class="breadcrumb-item active">Detail KRS</li>
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="matakuliah">Pilih Matakuliah</label>
                                        <input type="number" value="{{ $ta }}" id="ta" hidden="" />
                                        <input type="number" value="{{ $idmhs }}" id="idmhs" hidden="" />
                                        <select name="matakuliah" onchange="getmk()" id="matakuliah" class="form-control js-example-basic-single">
                                            <option value="" disabled selected>Pilih Matakuliah</option>
                                            @foreach($mk as $row)
                                                <option value="{{ $row['id'] }}">Kode Matakuliah : {{ $row['kode_matkul'] }} | Nama Matakuliah : {{ $row['nama_matkul'] }} | Semester : {{ $row['semester'] ?? '-' }} | Status : {{ $row['status_mk'] ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile-title" style="float: right;">
                                        <div class="media">
                                            <div class="photo-profile">
                                                <img class="img-70 rounded-circle" alt="" src="{{ (!empty($mhs->foto_mhs))?asset('assets/images/mahasiswa/' . $mhs->foto_mhs):asset('assets/images/user/7.jpg') }}">
                                            </div>
                                            <div class="media-body" style="margin-left: 10px;">
                                                <h5 class="mb-1">{{$mhs->nama}}</h5>
                                                <p>{{$mhs->nim}}<br>{{$prodi[$mhs->id_program_studi]}}<br>{{$mhs->email}}<br>{{$mhs->hp}}</p>
                                            </div>
                                        </div>
                                    </div>
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
                                <h5>KRS diajukan : </h5>
                                <a href="#" onclick="validasiSemua({{ $idmhs }}, {{ $ta }})" class="btn btn-success btn-sm" style="float:right;"><i class="fa fa-check"></i> Validasi Semua</a>
                                <table class="table">
                                    <thead>
                                        <td>No.</td>
                                        <td>Kelas</td>
                                        <td>Nama Matakuliah</td>
                                        <td>SKS</td>
                                        <td>Hari, Waktu</td>
                                        <td>Ruang</td>
                                        <td>Status</td>
                                        <td>Aksi</td>
                                    </thead>
                                    <tbody>
                                        @php $total_sks = 0; @endphp
                                        @foreach($krs as $row_krs)
                                            @php
                                                $total_sks += $row_krs['sks_teori'] + $row_krs['sks_praktek']
                                            @endphp
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row_krs['kel'] }}</td>
                                                <td>{{ $row_krs['nama_matkul'] }}</td>
                                                <td>{{ ($row_krs['sks_teori'] + $row_krs['sks_praktek']) }}</td>
                                                <td>{{ $row_krs['hari'] }}, {{ $row_krs['nama_sesi'] }}</td>
                                                <td>{{ $row_krs['nama_ruang'] }}</td>
                                                <td>
                                                    {{ $row_krs['is_publish'] == 0 ? 'Belum Validasi':'Sudah Validasi' }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">

                                                        @if($row_krs['is_publish'] == 0)
                                                            <a href="#" onclick="validasiSatuan({{ $row_krs['id'] }}, 1)" class="btn btn-success btn-sm" title="Validasi"><i class="fa fa-check"></i></a>
                                                        @else
                                                            <a href="#" onclick="validasiSatuan({{ $row_krs['id'] }}, 0)" class="btn btn-info btn-sm" title="Batal Validasi"><i class="fa fa-close"></i></a>
                                                        @endif
                                                        <a href="{{ url('admin/masterdata/krs/admin/hapus/'.$row_krs['id']) }}" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan=3>Total SKS</td>
                                            <td>{{$total_sks}}</td>
                                            <td colspan=4></td>
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
        const baseUrl = {!! json_encode(url('/')) !!};
        $(function() {
            $("#tablekrs").DataTable({
                responsive: true
            })
        })
        function getmk(){
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
        function validasiSatuan(id, tipe){
            $.ajax({
                url: baseUrl+'/dosen/validasi-krs-satuan',
                type: 'post',
                data: {
                    id_krs: id,
                    tipe: tipe
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    location.reload();
                }
            })
        }
        function validasiSemua(idmhs, ta){
            $.ajax({
                url: baseUrl+'/dosen/validasi-krs',
                type: 'post',
                data: {
                    idmhs: idmhs,
                    ta: ta
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    location.reload();
                }
            })
        }
    </script>
@endsection
