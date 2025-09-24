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
                            <li class="nav-item"><a href="{{URL::to('admin/akademik/pengaturan-ujian')}}" class="nav-link {{($id_prodi==0)?"active":""}}" ><i data-feather="target"></i>All</a></li>
                            @foreach($prodi as $prod)
                                <li class="nav-item"><a href="{{URL::to('admin/akademik/pengaturan-ujian/prodi/' . $prod->id)}}" class="nav-link {{($id_prodi==$prod->id)?"active":""}}" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} </a></li>
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
                                                        <th>Dosen pengampu</th>
                                                        <th>Matakuliah</th>
                                                        <th>Hari & Waktu</th>
                                                        <th>T/P</th>
                                                        <th>Tanggal Ujian UTS</th>
                                                        <th>Waktu UTS</th>
                                                        <th>Ruang UTS</th>
                                                        <th>Tanggal Ujian UAS</th>
                                                        <th>Waktu UAS</th>
                                                        <th>Ruang UAS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jadwal as $jad)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jad['kode_jadwal'] }}</td>
                                                            <td>{!! $list_pengajar[$jad['id']] !!}</td>
                                                            <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                                            <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                                            <td>{{ $jad['tp'] }}</td>
                                                            <td>
                                                                <input type="date" class="form-control tanggal-ujian" data-id='{{$jad->id}}' value="{{$jad->tanggal_uts ?? ''}}"> 
                                                            </td>
                                                            <td> 
                                                                <input type="time" class="form-control waktu_mulai" data-id='{{$jad->id}}' value="{{$jad->jam_mulai_uts ?? ''}}"> 
                                                                <input type="time" class="form-control waktu_selesai" data-id='{{$jad->id}}' value="{{$jad->jam_selesai_uts ?? ''}}"> 
                                                            </td>
                                                            <td>
                                                                <select name="ruang_uts" class="form-control ruang_uts" id="ruang_uts" data-id={{$jad->id}}> 
                                                                    <option value="0">--Pilih Ruang</option>
                                                                    @foreach($ruang as $row)
                                                                    <option value="{{$row->id}}" {{(!empty($jad->id_ruang_uts)&&$jad->id_ruang_uts==$row->id)?"selected":""}}>{{$row->nama_ruang}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="date" class="form-control tanggal-ujian-uas" data-id='{{$jad->id}}' value="{{$jad->tanggal_uas ?? ''}}"> 
                                                            </td>
                                                            <td>
                                                                <input type="time" class="form-control waktu_mulai-uas" data-id='{{$jad->id}}' value="{{$jad->jam_mulai_uas ?? ''}}"> 
                                                                <input type="time" class="form-control waktu_selesai-uas" data-id='{{$jad->id}}' value="{{$jad->jam_selesai_uas ?? ''}}"> 
                                                            </td>
                                                            <td>
                                                                <select name="ruang_uts" class="form-control ruang_uas" id="ruang_uas" data-id={{$jad->id}}> 
                                                                    <option value="0">--Pilih Ruang</option>
                                                                    @foreach($ruang as $row)
                                                                    <option value="{{$row->id}}" {{(!empty($jad->id_ruang_uas)&&$jad->id_ruang_uas==$row->id)?"selected":""}}>{{$row->nama_ruang}}</option>
                                                                    @endforeach
                                                                </select>
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
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('assets/js/notify/notify-script.js')}}"></script>
    <script>

        const baseUrl = {!! json_encode(url('/')) !!};
        const update_ujian = (id, property, value) => {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id : id,
                    property: property,
                    value: value,
                },
                url: ''.concat(baseUrl).concat('/admin/akademik/pengaturan-ujian/setjadwal'),
                type: 'POST',
                success: function success(status) {
                    // sweetalert
                    $.notify({
                        title:'Set Jadwal Berhasil Disimpan',
                        message:''
                    },
                    {
                        type:'success',
                        allow_dismiss:true,
                        newest_on_top:false ,
                        mouse_over:false,
                        showProgressbar:false,
                        spacing:10,
                        timer:2000,
                        placement:{
                            from:'top',
                            align:'right'
                        },
                        offset:{
                            x:30,
                            y:30
                        },
                        delay:1000 ,
                        z_index:10000,
                        animate:{
                            enter:'animated fadeInDown',
                            exit:'animated fadeOutDown'
                        }
                    });
                },
                error: function error(err) {
                    
                    $.notify({
                        title:'Set Jadwal Gagal Disimpan',
                        message:''
                    },
                    {
                        type:'danger',
                        allow_dismiss:true,
                        newest_on_top:false ,
                        mouse_over:false,
                        showProgressbar:false,
                        spacing:10,
                        timer:2000,
                        placement:{
                            from:'top',
                            align:'right'
                        },
                        offset:{
                            x:30,
                            y:30
                        },
                        delay:1000 ,
                        z_index:10000,
                        animate:{
                            enter:'animated fadeInDown',
                            exit:'animated fadeOutDown'
                        }
                    });
                }
            });
        }
        $(function() {
            $("#myTable").DataTable({
                responsive: true,
                drawCallback: function (settings) {
                    $(".tanggal-ujian").change(function(){
                        update_ujian($(this).data('id'),'tanggal_uts',$(this).val())
                    });
                    $(".waktu_mulai").change(function(){
                        update_ujian($(this).data('id'),'jam_mulai_uts',$(this).val())
                    });
                    $(".waktu_selesai").change(function(){
                        update_ujian($(this).data('id'),'jam_selesai_uts',$(this).val())
                    });
                    $(".ruang_uts").change(function(){
                        update_ujian($(this).data('id'),'id_ruang_uts',$(this).val())
                    });
                    $(".tanggal-ujian-uas").change(function(){
                        update_ujian($(this).data('id'),'tanggal_uas',$(this).val())
                    });
                    $(".waktu_mulai-uas").change(function(){
                        update_ujian($(this).data('id'),'jam_mulai_uas',$(this).val())
                    });
                    $(".waktu_selesai-uas").change(function(){
                        update_ujian($(this).data('id'),'jam_selesai_uas',$(this).val())
                    });
                    $(".ruang_uas").change(function(){
                        update_ujian($(this).data('id'),'id_ruang_uas',$(this).val())
                    });
                },
            })
            
            

        })
    </script>
@endsection
