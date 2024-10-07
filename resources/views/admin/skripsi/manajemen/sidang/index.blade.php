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
    <li class="breadcrumb-item active">Asal Sekolah PMB</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            {{-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="kelMK-tab" href="{{ url('admin/masterdata/kelompok-mk') }}" role="tab" aria-controls="kelMK" aria-selected="true">Kelompok Matakuliah</a></li> --}}
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default " id="masterKur-tabs" href="{{ url('admin/masterdata/kurikulum') }}" role="tab" aria-controls="masterKur" aria-selected="false" tabindex="-1">Mahasiswa Bimbingan</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="MkKur-tab" href="{{ url('admin/masterdata/matakuliah-kurikulum') }}" role="tab" aria-controls="MkKur" aria-selected="false" tabindex="-1">Mahasiswa Sidang</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default " href="#" role="tab"  aria-selected="false" tabindex="-1">Dosen Pembimbing</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="masterMK" role="tabpanel" aria-labelledby="masterMK-tab">
                                
                                <div class="table-responsive mt-4">
                                    <table class="display" id="tableMK">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Judul</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                                {{-- <th>Aksi</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pembimbing as $dosen)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $dosen['nama'] }}</td>
                                                    <td>judul</td>
                                                    <td>{{ $dosen['kuota'] }}</td>
                                                    <td><span class="label p-1 label-warning">Pengajuan</span></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="#" class="btn btn-success btn-sm btn-icon edit-record">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                            <a href="#" class="btn btn-warning btn-sm btn-icon edit-record">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                          
                                                            <a href="#" class="btn btn-danger btn-sm btn-icon edit-record">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                          
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

    <script>
        $(function() {
            $("#tableMK").DataTable({
                responsive: true
            })
        })
        // function simpanMK(){
        //     const baseUrl = {!! json_encode(url('/')) !!};
        //     $.ajax({
        //         url: baseUrl+'/admin/masterdata/matakuliah/save',
        //         type: 'post',
        //         dataType: 'json',
        //         data: {
        //             kode_matkul: $('#kode_matkul').val(),
        //             nama_matkul: $('#nama_matkul').val(),
        //             nama_inggris: $('#nama_inggris').val(),
        //             kelompok: $('#kelompok').val(),
        //             rumpun: $('#rumpun').val(),
        //             semester: $('#semester').val(),
        //             sks_teori: $('#sks_teori').val(),
        //             sks_praktek: $('#sks_praktek').val(),
        //             status_mk: $('#status_mk').val(),
        //             status: $('#status').val()
        //         },
        //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //         success: function(res){
        //             if(res.kode == 200){
        //                 swal({
        //                     icon: 'success',
        //                     title: 'Berhasil!',
        //                     text: 'Matakuliah Berhasil ditambahkan!',
        //                     customClass: {
        //                         confirmButton: 'btn btn-danger'
        //                     }
        //                 });
        //                 window.location.href = baseUrl+'/admin/masterdata/matakuliah';
        //             }else{
        //                 swal({
        //                     icon: 'error',
        //                     title: 'Gagal!',
        //                     text: 'Matakuliah Gagal ditambahkan!',
        //                     customClass: {
        //                         confirmButton: 'btn btn-danger'
        //                     }
        //                 });
        //                 window.location.href = baseUrl+'/admin/masterdata/matakuliah';
        //             }
        //         }
        //     })
        // }
        // function updateMK(kode){
        //     const baseUrl = {!! json_encode(url('/')) !!};
        //     $.ajax({
        //         url: baseUrl+'/admin/masterdata/matakuliah/update',
        //         type: 'post',
        //         dataType: 'json',
        //         data: {
        //             id : $('#id_'+kode).val(),
        //             kode_matkul: $('#kode_matkul_'+kode).val(),
        //             nama_matkul: $('#nama_matkul_'+kode).val(),
        //             nama_inggris: $('#nama_inggris_'+kode).val(),
        //             kelompok: $('#kelompok_'+kode).val(),
        //             rumpun: $('#rumpun_'+kode).val(),
        //             semester: $('#semester_'+kode).val(),
        //             sks_teori: $('#sks_teori_'+kode).val(),
        //             sks_praktek: $('#sks_praktek_'+kode).val(),
        //             status_mk: $('#status_mk_'+kode).val(),
        //             status: $('#status_'+kode).val()
        //         },
        //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //         success: function(res){
        //             if(res.kode == 200){
        //                 swal({
        //                     icon: 'success',
        //                     title: 'Berhasil!',
        //                     text: 'Matakuliah Berhasil ditambahkan!',
        //                     customClass: {
        //                         confirmButton: 'btn btn-danger'
        //                     }
        //                 });
        //                 window.location.href = baseUrl+'/admin/masterdata/matakuliah';
        //             }else{
        //                 swal({
        //                     icon: 'error',
        //                     title: 'Gagal!',
        //                     text: 'Matakuliah Gagal ditambahkan!',
        //                     customClass: {
        //                         confirmButton: 'btn btn-danger'
        //                     }
        //                 });
        //                 window.location.href = baseUrl+'/admin/masterdata/matakuliah';
        //             }
        //         }
        //     })
        // }
    </script>
@endsection
