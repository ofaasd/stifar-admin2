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
                            <?php
                                if(!is_null(Session::get('krs'))){
                                    echo Session::get('krs');
                                    Session::forget('krs');
                                }
                            ?>
                            @if($permission->uts == 0 )
                                <div class="alert alert-danger">Anda belum diizinkan untuk melihat dan mencetak kartu ujian UTS harap menghubungi admin sistem / BAAK</div>
                            @else
                            <div class="mt-4">
                                <h3>Jadwal Ujian UTS : </h3>
                                <a href="{{ url('mhs/ujian/cetak_uts') }}" class="btn btn-primary btn-sm m-4" style="float: right;"><i class="fa fa-download"></i> Download Kartu Ujian UTS</a>
                                
                                <div class="mt-2"></div>
                                <table class="table" id="tablekrs">
                                    <thead>
                                        <td>No.</td>
                                        <td>Kode</td>
                                        <td>Nama Matakuliah</td>
                                        <td>Kelas</td>
                                        <td>SKS</td>
                                        <!-- <td>SKS</td> -->
                                        <td>Hari, Waktu (UTS)</td>
                                        <td>Ruang (UTS)</td>
                                        
                                        <td>Izin Dosen Pengampu</td>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_krs = 0;
                                        @endphp
                                        @foreach($krs as $row)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row['kode_jadwal'] }}</td>
                                                <td>{{ $row['nama_matkul'] }}</td>
                                                <td>{{ $row['kel'] }}</td>
                                                <td>{{ ($row->sks_teori+$row->sks_praktek) }}</td>
                                                <!-- <td>{{ $row['sks_teori'] }}T/ {{ $row['sks_praktek'] }}P</td> -->
                                                <td>{{ (!empty($row['tanggal_uts']))?date('d-m-Y',strtotime($row['tanggal_uts'])) : '' }}, {{ $row['jam_mulai_uts'] ?? '' }} - {{ $row['jam_selesai_uts'] ?? '' }}</td>
                                                <td>{{ $list_ruang[$row['id_ruang_uts'] ?? 0] }}</td>
                                                
                                                <td>{!!($row->is_uts == 0)?'<p class="btn btn-secondary btn-sm" style="font-size:8pt;">Tidak Diizinkan</p>':'<p class="btn btn-success btn-sm" style="font-size:8pt;">Sudah Diizinkan</p>'!!}</td>
                                                
                                            </tr>
                                            
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            @if($permission->uas == 0 )
                                <div class="alert alert-danger">Anda belum diizinkan untuk melihat dan mencetak kartu ujian UTS harap menghubungi admin sistem / BAAK</div>
                            @else
                            <div class="mt-4">
                                <h3>Jadwal Ujian UAS : </h3>
                                
                                <a href="#" class="btn btn-info btn-sm m-4" style="float: right;"><i class="fa fa-download"></i> Download Kartu Ujian UAS</a>
                                <div class="mt-2"></div>
                                <table class="table" id="tablekrs">
                                    <thead>
                                        <td>No.</td>
                                        <td>Kode</td>
                                        <td>Nama Matakuliah</td>
                                        <td>Kelas</td>
                                        <td>SKS</td>
                                        <!-- <td>SKS</td> -->
                                        <td>Hari, Waktu (UAS)</td>
                                        <td>Ruang (UAS)</td>
                                        
                                        <td>Izin Dosen Pengampu</td>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_krs = 0;
                                            $no = 1;
                                        @endphp
                                        @foreach($krs as $row)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row['kode_jadwal'] }}</td>
                                                <td>{{ $row['nama_matkul'] }}</td>
                                                <td>{{ $row['kel'] }}</td>
                                                <td>{{ ($row->sks_teori+$row->sks_praktek) }}</td>
                                                <!-- <td>{{ $row['sks_teori'] }}T/ {{ $row['sks_praktek'] }}P</td> -->
                                                <td>{{ (!empty($row['tanggal_uas']))?date('d-m-Y',strtotime($row['tanggal_uas'])) : '' }}, {{ $row['jam_mulai_uas'] ?? '' }} - {{ $row['jam_selesai_uas'] ?? '' }}</td>
                                                <td>{{ $list_ruang[$row['id_ruang_uas'] ?? 0] }}</td>
                                                <td>{!!($row->is_uas == 0)?'<p class="btn btn-secondary btn-sm" style="font-size:8pt;">Tidak Diizinkan</p>':'<p class="btn btn-success btn-sm" style="font-size:8pt;">Sudah Diizinkan</p>'!!}</td>
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
                responsive: true,
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
