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
    <li class="breadcrumb-item active">Absensi</li>
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
                            <div class="mt-4">
                                <h3>KRS : </h3>
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
                                        <td>Presensi</td>
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
                                                <td>
                                                    {{-- @if(!empty($pertemuan[$row_krs['id_jadwal']]))
                                                        @foreach($pertemuan[$row_krs['id_jadwal']] as $key=>$value)

                                                        @endforeach
                                                    @endif --}}
                                                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-original-title="test" data-bs-target="#presensi" data-idmhs="{{$row_krs->id_mhs}}" data-idjadwal="{{$row_krs->id_jadwal}}">Detail Presensi</a>
                                                </td>
                                            </tr>
                                            @php
                                            $total_krs += ($row_krs->sks_teori+$row_krs->sks_praktek);
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="modal fade" id="presensi" tabindex="-1" role="dialog" aria-labelledby="presensi" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <form action="javascript:void(0)" id="formAdd">
                                                @csrf
                                                <input type="hidden" name="id" id="id">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ModalLabel">{{$title}}</h5>
                                                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-12" id="detail_absensi">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                                    <button class="btn btn-primary" type="submit" id="btn_save">Simpan</button>
                                                </div>
                                            </form>
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
