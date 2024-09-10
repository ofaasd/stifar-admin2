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
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterJadwal-tab" href="{{ url('/admin/masterdata/jadwal/create/'.$idmk) }}" role="tab" aria-controls="masterJadwal" aria-selected="true">Master Jadwal</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="DsnMK-tab" data-bs-toggle="tab" href="#DsnMK" role="tab" aria-controls="DsnMK" aria-selected="false" tabindex="-1">Koordinator & Anggota Matakuliah</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="DsnMK" role="tabpanel" aria-labelledby="DsnMK-tab">
                                @csrf
                                <div class="row" style="padding-top: 20px;">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="kode_jadwal" class="form-label">Nama Anggota</label>
                                            <input type="text" value="{{ $idmk }}" name="idmk" id="idmk" hidden="" />
                                            <select name="nama_anggota" id="nama_anggota" class="js-example-basic-single">
                                                @foreach($pegawai as $dsn)
                                                    <option value="{{ $dsn['id'] }}">{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kode_jadwal" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="" selected disabled>Pilih Status</option>
                                                <option value="1">Koordinator</option>
                                                <option value="2">Anggota</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" onclick="simpanAnggota()" id="btn_tambah"><i class="fa fa-save"></i> Tambahkan</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive" id="anggota-table">
                                    <table class="display" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>NPP</th>
                                                <th>Nama Dosen</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($anggota as $row)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $row['npp'] }}</td>
                                                    <td>{{ $row['nama_lengkap'] }}, {{ $row['gelar_belakang'] }}</td>
                                                    <td>{{ $row['status'] == 1 ? 'Koordinator':'Anggota' }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" data-id='{{$row->id}}' data-bs-toggle="modal" data-original-title="test" data-bs-target="#editMK{{ $row->id }}" class="btn btn-success btn-sm"><i class="fa fa-trash"></i> Edit</a> <a href="javascript:void(0)" data-id='{{$row->id}}' class="btn btn-danger btn-sm hapusAnggota"><i class="fa fa-trash"></i> Hapus</a>
                                                        <div class="modal fade" id="editMK{{ $row->id }}" tabindex="-1" aria-labelledby="editMK{{ $row->id }}" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div class="modal-toggle-wrapper">
                                                                            <h5 style="text-align: center">Edit Koordinator/Anggota MK</h5>
                                                                            @csrf
                                                                                <input type="hidden" name="id" id="id_{{ $row->id }}" value="{{$row->id}}">
                                                                                <hr>
                                                                                <div class="mb-3">
                                                                                    <label for="kode_jadwal" class="form-label">Nama Anggota</label>
                                                                                    <select name="nama_anggota" id="nama_anggota_{{$row->id}}" class="js-example-basic-single">
                                                                                        @foreach($pegawai as $dsn)
                                                                                            <option value="{{ $dsn['id'] }}" {{($dsn['id'] == $row->id_pegawai_bio)?"selected":""}}>{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="kode_jadwal" class="form-label">Status</label>
                                                                                    <select name="status" id="status_{{$row->id}}" class="form-control" required>
                                                                                        <option value="" selected disabled>Pilih Status</option>
                                                                                        <option value="1" {{($row->status == 1)?"selected":""}}>Koordinator</option>
                                                                                        <option value="2" {{($row->status == 2)?"selected":""}}>Anggota</option>
                                                                                    </select>
                                                                                </div>
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <button type="button" onclick="updateAnggotaJadwal('{{ $row->id }}')" class="btn btn-primary btn-sm btn-update"><i class="fa fa-save"></i>Update Anggota Matakuliah</button>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto" type="button" data-bs-dismiss="modal">Tutup<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
        $(document).on('click','.hapusAnggota',function(){
            const idmk = $('#idmk').val();
            const id = $(this).data('id');
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/jadwal/hapus-anggota/'+id,
                type: 'get',
                success: function(res){
                    swal({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Anggota Berhasil Terinputkan!',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                    update_table(baseUrl,idmk);

                        //window.location.href = baseUrl+'/admin/masterdata/anggota-mk/'+idmk;


                },
            });
        });
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })

        })
        function updateAnggotaJadwal(id){
            $(".btn-update").attr("disabled",true);
            const id_anggota = $("#id_" + id).val();
            const id_pegawai_bio = $("#nama_anggota_" + id).val();
            const status = $("#status_" + id).val();
            const idmk = $('#idmk').val();
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/jadwal/update-anggota',
                type: 'post',
                dataType: 'json',
                data: {
                    id:id_anggota,
                    id_pegawai_bio:id_pegawai_bio,
                    idmk:idmk,
                    status:status
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 204){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Simpan Gagal!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        $("btn-update").attr("disabled",false);
                    }
                    if(res.kode == 205){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Data Sudah pernah di input',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        $("btn-update").attr("disabled",false);
                    }
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Anggota Berhasil Terinputkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        update_table(baseUrl,idmk);

                        //window.location.href = baseUrl+'/admin/masterdata/anggota-mk/'+idmk;
                    }
                    $("btn-update").attr("disabled",false);

                    $("#editMK"+id).modal('hide');

                },
                error:function(data){
                    swal({
                        icon: 'warning',
                        title: 'Galat!',
                        text: 'Simpan Gagal!',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                    $(".btn-update").attr("disabled",false);
                }
            });

        }
        function simpanAnggota(){
            $("#btn_tambah").attr("disabled",true);
            var id_pegawai_bio = $('#nama_anggota').val();
            var status = $('#status').val();
            var idmk = $('#idmk').val();
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/jadwal/save-anggota',
                type: 'post',
                dataType: 'json',
                data: {
                    id_pegawai_bio:id_pegawai_bio,
                    idmk:idmk,
                    status:status
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 204){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Simpan Gagal!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 205){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Data Sudah pernah di input',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Anggota Berhasil Terinputkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        update_table(baseUrl,idmk);

                        //window.location.href = baseUrl+'/admin/masterdata/anggota-mk/'+idmk;
                    }
                    $("#btn_tambah").attr("disabled",false);

                },
                error:function(data){
                    swal({
                        icon: 'warning',
                        title: 'Galat!',
                        text: 'Simpan Gagal!',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                    $("#btn_tambah").attr("disabled",false);
                }
            });
        }
        function update_table(baseUrl,idmk){
            $.ajax({
                url: baseUrl+'/jadwal/tableAnggota',
                type: 'post',
                data : {
                    idmk:idmk,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                    $("#anggota-table").html(data);
                }
            });
        }
    </script>
@endsection
