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
            @if(empty($dosen))
            <div class="col-md-12 project-list">
                <div class="card">
                   <div class="row">
                      <div class="col-md-12">
                         <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                            <li class="nav-item"><a href="{{URL::to('admin/akademik/setting-pertemuan')}}" class="nav-link {{($id_prodi==0)?"active":""}}" ><i data-feather="target"></i>All</a></li>
                            @foreach($prodi as $prod)
                                <li class="nav-item"><a href="{{URL::to('admin/akademik/setting-pertemuan/prodi/' . $prod->id)}}" class="nav-link {{($id_prodi==$prod->id)?"active":""}}" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} </a></li>
                            @endforeach
                         </ul>
                      </div>
                   </div>
                </div>
            </div>
            @endif
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
                                                        <th>Jml Pertemuan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jadwal as $jad)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jad['kode_jadwal'] }}</td>
                                                            <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                                            <td>{!! $list_pengajar[$jad['id']] !!}</td>
                                                            <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                                            <td>{{ $jad['nama_ruang'] }}</td>
                                                            <td>{{ $jad['tp'] }}</td>
                                                            <td>{{ $jumlah_pertemuan[$jad['id']] }}</td>
                                                            <td>
                                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

                                                                    <a href="#" class="btn {{$list_pertemuan[$jad['id']]}} btn-xs edit-pertemuan" data-id="{{$jad['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalPertemuan"><i class="fa fa-gear"></i> Set Pertemuan</a>

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
                        <div class="modal fade" id="modalPertemuan" tabindex="-1" aria-labelledby="set_pertemuan" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Daftar Pertemuan</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-toggle-wrapper pertemuan-location">

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
            $("body").on("click",".edit-pertemuan",function(){
                const id_jadwal = $(this).data('id');
                $(".pertemuan-location").html(`<div class="loader-box">
                            <div class="loader-2"></div>
                        </div>`);
                $.ajax({
                    url: baseUrl+'/jadwal/get-pertemuan',
                    type: 'post',
                    data: {
                        id: id_jadwal,
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(data){
                        $(".pertemuan-location").html(data);
                    }
                });
            })
        })
        function JadwalHarian(){
            $("#vJadwalHarian").html(`<div class="loader-box">
                            <div class="loader-2"></div>
                        </div>`);
            var hari = $('#hari').val();
            var matakuliah = $('#matakuliah').val();
            const id_prodi = {{$id_prodi}};
            $.ajax({
                url: baseUrl+'/jadwal/daftar-jadwal-harian',
                type: 'post',
                data: {
                    hari: hari,
                    matakuliah: matakuliah,
                    id_prodi : id_prodi,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    var data = res.data
                    var jumlah_input = res.jumlah_input;
                    var html = `
                        <table class="table" id="myTable1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Jadwal</th>
                                    <th>Hari & Waktu</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Matakuliah</th>
                                    <th>Ruang</th>

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
                                    <td>${ data[i].nama_dosen }</td>
                                    <td>[${ data[i].kode_matkul }] ${ data[i].nama_matkul }</td>
                                    <td>${ data[i].nama_ruang }</td>

                                    <td>${ data[i].tp }</td>
                                    <td>${ jumlah_input[data[i].id]} / ${ data[i].kuota }</td>

                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <a href="#" class="btn btn-primary btn-xs">Edit</a>
                                            <a href="#" class="btn btn-success btn-xs">Setting Pertemuan</a>
                                        </div>

                                    </td>
                                </tr>
                                `
                    }
                    html += `</tbody></table>`
                    $('#vJadwalHarian').html(html)
                    $("#myTable1").DataTable({
                        responsive: true
                    })
                }
            })
        }
    </script>
@endsection
