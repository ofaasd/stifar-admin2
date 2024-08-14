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
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">

                    </div>
                    <div class="card-body">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterJadwal-tab" href="{{ url('/admin/masterdata/jadwal') }}" role="tab" aria-controls="masterJadwal" aria-selected="true">Jadwal Matakuliah</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="jadwalHarian-tab" href="{{ url('/admin/masterdata/jadwal-harian') }}" aria-controls="jadwalHarian" aria-selected="false">Jadwal Harian</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="jadwalHarian" role="tabpanel" aria-labelledby="jadwalHarian-tab">
                                <div class="table-responsive mt-2">
                                    <div class="row">
                                        @csrf
                                        <div class="col-sm-6">
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
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="matakuliah">Pilih MataKuliah</label>
                                            <select name="matakuliah" id="matakuliah" class="form-control">
                                                    <option value="0">Semua Matakuliah</option>
                                                @foreach($mk as $mk)
                                                    <option value="{{ $mk['id'] }}">{{ $mk['kode_matkul'] }} - {{ $mk['nama_matkul'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mt-2">
                                            <button onclick="JadwalHarian()" class="btn btn-primary btn-sm">Cari</button>
                                        </div>
                                        <div id="vJadwalHarian" class="mt-2">
                                            <table class="display" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Kode Jadwal</th>
                                                        <th>Hari & Waktu</th>
                                                        <th>Matakuliah</th>
                                                        <th>Ruang</th>
                                                        <th>Tahun Ajaran</th>
                                                        <th>Status</th>
                                                        <th>T/P</th>
                                                        <th>Kuota</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jadwal as $jad)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jad['kode_jadwal'] }}</td>
                                                            <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                                            <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                                            <td>{{ $jad['nama_ruang'] }}</td>
                                                            <td>{{ $jad['kode_ta'] }}</td>
                                                            <td>{{ $jad['status'] }}</td>
                                                            <td>{{ $jad['tp'] }}</td>
                                                            <td>{{ $jad['kuota'] }}</td>
                                                            <td>#</td>
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
        function JadwalHarian(){
            var hari = $('#hari').val();
            var matakuliah = $('#matakuliah').val();

            $.ajax({
                url: baseUrl+'/jadwal/daftar-jadwal-harian',
                type: 'post',
                data: {
                    hari: hari,
                    matakuliah: matakuliah
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    var data = res.data
                    var html = `
                        <table class="table" id="myTable1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Jadwal</th>
                                    <th>Hari & Waktu</th>
                                    <th>Matakuliah</th>
                                    <th>Ruang</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th>T/P</th>
                                    <th>Kuota</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                    `
                    for (let i = 0; i < data.length; i++) {
                        console.log(data[i].kode_jadwal)
                        const no = i + 1;
                        html += `
                                <tr>
                                    <td>${ no }</td>
                                    <td>${ data[i].kode_jadwal }</td>
                                    <td>${ data[i].hari }, ${ data[i].nama_sesi }</td>
                                    <td>[${ data[i].kode_matkul }] ${ data[i].nama_matkul }</td>
                                    <td>${ data[i].nama_ruang }</td>
                                    <td>${ data[i].kode_ta }</td>
                                    <td>${ data[i].status }</td>
                                    <td>${ data[i].tp }</td>
                                    <td>${ data[i].kuota }</td>
                                    <td>#</td>
                                </tr>
                                `                        
                    }
                    html += `</tbody></table>`
                    $('#vJadwalHarian').html(html)
                }
            })
        }
    </script>
@endsection
