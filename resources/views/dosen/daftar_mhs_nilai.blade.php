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
    <li class="breadcrumb-item">KRM</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="alert alert-primary">
                    Pada halaman ini user dapat menginput nilai dan mempublish(jika ingin menampilkan nilai tugas,uts atau uas pada halaman mahasiswa) dan validasi(Jika sudah divalidasi maka nilai tidak bisa diubah-ubah lagi). Jika nilai tugas,UTS dan UAS sudah di validasi maka nilai akhir akan otomatis tampil di halaman mahasiswa
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>[{{ $jadwal['kode_jadwal'] }}] - {{ $jadwal['nama_matkul'] }}</h5>
                                <h6>{{ $jadwal['hari'] }}, {{ $jadwal['nama_sesi'] }}</h6>
                                <b>Kontrak Kuliah</b>
                                <table>
                                    <tr>
                                        <td>
                                            Persentase Tugas (%)
                                        </td>
                                        <td>
                                            Persentase UTS (%)
                                        </td>
                                        <td>
                                            Persentase UAS (%)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0px;">
                                            <input type="number" step="any" id="persentase_tugas" class="form-control form-control-sm" value="{{ $kontrak->tugas?? 0 }}">
                                        </td>
                                        <td style="padding-left: 0px;">
                                            <input type="number" step="any" id="persentase_uts" class="form-control form-control-sm" value="{{ $kontrak->uts?? 0 }}">
                                        </td>
                                        <td style="padding-left: 0px;">
                                            <input type="number" step="any" id="persentase_uas" class="form-control form-control-sm" value="{{ $kontrak->uas?? 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button onclick="simpanKontrak({{ $jadwal['id'] }})" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Simpan Kontrak</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table>
                                    <tr>
                                        <td colspan=2>{{(empty($daftar_mhs))?"<div class='alert alert-danger'>Belum ada data nilai yang di input</div>":""}}</td>
                                    </tr>
                                    <tr>
                                        <td><button class="btn {{($action[1] == 0)?"btn-info":"btn-danger"}} btn-sm publish-btn" data-id="{{$id}}" data-status="tugas" data-action="{{$action[1]}}">{{($action[1] == 0)?"Publish":"UnPublish"}} TGS</button></td>
                                        <td style="padding-left: 10px;"><button class="btn {{($actionvalid[1] == 0)?"btn-info":"btn-danger"}} btn-sm validasi-btn" data-id="{{$id}}" data-status="tugas" data-action="{{$actionvalid[1]}}">{{($actionvalid[1] == 0)?"Validasi":"Batalkan Validasi"}}  TGS</button></td>
                                    </tr>
                                    <tr>
                                        <td><button class="btn {{($action[2] == 0)?"btn-info":"btn-danger"}} btn-sm publish-btn" data-id="{{$id}}"  data-status="uts" data-action="{{$action[2]}}">{{($action[2] == 0)?"Publish":"UnPublish"}} UTS</button></td>
                                        <td style="padding-left: 10px;"><button class="btn {{($actionvalid[2] == 0)?"btn-info":"btn-danger"}} btn-sm validasi-btn" data-id="{{$id}}"  data-status="uts" data-action="{{$actionvalid[2]}}">{{($actionvalid[2] == 0)?"Validasi":"Batalkan Validasi"}} UTS</button></td>
                                    </tr>
                                    <tr>
                                        <td><button class="btn {{($action[3] == 0)?"btn-info":"btn-danger"}} btn-sm publish-btn" data-id="{{$id}}"  data-status="uas" data-action="{{$action[3]}}">{{($action[3] == 0)?"Publish":"UnPublish"}} UAS</button></td>
                                        <td style="padding-left: 10px;"><button class="btn {{($actionvalid[3] == 0)?"btn-info":"btn-danger"}} btn-sm validasi-btn" data-id="{{$id}}"  data-status="uas" data-action="{{$actionvalid[3]}}">{{($actionvalid[3] == 0)?"Validasi":"Batalkan Validasi"}} UAS</button></td>
                                    </tr>
                                </table>
                                <div class="mt-4"></div>
                                <span>A = 0; B = 0; C = 0; D = 0; E = 0;</span>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <form method="POST" action="{{url('dosen/simpan-nilai-all')}}">
                                @csrf
                                <input type="hidden" name="id_jadwal" value="{{$id}}">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Nilai Tugas</th>
                                            <th>Nilai UTS</th>
                                            <th>Nilai UAS</th>
                                            <th>Nilai Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($daftar_mhs as $row)

                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <input type="hidden" name="nim[]" value="{{$row['nims']}}">
                                                    <input type="hidden" name="id_mhs[]" value="{{$row['idmhs']}}">
                                                    {{ $row['nims'] }}
                                                </td>
                                                <td>{{ $row['nama'] }}</td>
                                                <td>
                                                    <input type="number" step="any" max="100" min="0" onchange="simpanNilai({{ $row['idmhs'] }}, {{ $id }}, '1', $(this).val())" class="form-control" id="nilai_tugas{{ $row['idmhs'] }}" name="nilai_tugas[{{$row['nims']}}]" data-id="{{ $row['idmhs'] }}" value="{{ $row['ntugas'] }}" {{$actionvalid[1] == 1?"readonly":""}}>
                                                </td>
                                                <td>
                                                    <input type="number" step="any" max="100" min="0" onchange="simpanNilai({{ $row['idmhs'] }}, {{ $id }}, '2', $(this).val())" class="form-control" id="nilai_uts{{ $row['idmhs'] }}" name="nilai_uts[{{$row['nims']}}]" data-id="{{ $row['idmhs'] }}" value="{{ $row['nuts'] }}" {{$actionvalid[2] == 1?"readonly":""}}>
                                                </td>
                                                <td>
                                                    <input type="number" step="any" max="100" min="0" onchange="simpanNilai({{ $row['idmhs'] }}, {{ $id }}, '3', $(this).val())" class="form-control" id="nilai_uas{{ $row['idmhs'] }}" name="nilai_uas[{{$row['nims']}}]" data-id="{{ $row['idmhs'] }}" value="{{ $row['nuas'] }}" {{$actionvalid[3] == 1?"readonly":""}}>
                                                </td>
                                                <td>
                                                    <span id="na{{ $row['idmhs'] }}">{{ $row['nakhir'] }} | {{ $row['nhuruf'] }}  </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <input type="submit" value="SIMPAN" class="btn btn-primary col-md-12 mt-2">
                            </form>
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
        $(function() {

            $(".publish-btn").click(function(){
                $(this).attr("disabled",true);
                const status = $(this).data('status');
                const id_jadwal = $(this).data('id');
                const action = $(this).data('action');
                $.ajax({
                url: baseUrl+'/dosen/publish-nilai',
                type: 'post',
                data: {
                    id_jadwal: id_jadwal,
                    status: status,
                    action: action,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil disimpan.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then(function(){
                            location.reload();
                        });
                    }else{
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Server Error.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        $(this).attr("disabled",false);
                    }
                }
            })
            })
            $(".validasi-btn").click(function(){
                $(this).attr("disabled",true);
                const status = $(this).data('status');
                const id_jadwal = $(this).data('id');
                const action = $(this).data('action');
                $.ajax({
                url: baseUrl+'/dosen/validasi-nilai',
                type: 'post',
                data: {
                    id_jadwal: id_jadwal,
                    status: status,
                    action: action,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil disimpan.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then(function(){
                            location.reload();
                        });
                    }else{
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Server Error.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        $(this).attr("disabled",false);
                    }
                }
            })
            })
        })
        function simpanNilai(idmhs, idjadwal, tipe, nilai){
            $.ajax({
                url: baseUrl+'/dosen/simpan-nilai',
                type: 'post',
                data: {
                    id_mhs: idmhs,
                    id_jadwal: idjadwal,
                    tipe: tipe,
                    nilai: nilai
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    console.log(res)
                    if(res.kode == 200){
                        $.notify({
                            title:'Berhasil !',
                            message:'Data Berhasil disimpan'
                        },
                        {
                            type:'primary',
                            allow_dismiss:false,
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
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        });
                        $('#na' + idmhs).html(`<span>${ res.na } | ${ res.nh }</span>`)
                    }else{
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Server Error.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })
        }
        function simpanKontrak(id_jadwal){
            var persentase_tugas = $('#persentase_tugas').val();
            var persentase_uts = $('#persentase_uts').val();
            var persentase_uas = $('#persentase_uas').val();
            $.ajax({
                url: baseUrl+'/dosen/simpan-kontrak',
                type: 'post',
                data: {
                    id_jadwal: id_jadwal,
                    tugas: persentase_tugas,
                    uts: persentase_uts,
                    uas:persentase_uas
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil disimpan.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }else{
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Server Error.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })
        }
    </script>
@endsection
