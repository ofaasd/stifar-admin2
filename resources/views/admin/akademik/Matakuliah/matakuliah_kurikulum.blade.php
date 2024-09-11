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
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterMK-tab" href="{{ url('admin/masterdata/matakuliah') }}" role="tab" aria-controls="masterMK" aria-selected="true">Master Matakuliah</a></li>
                            {{-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="kelMK-tab" href="{{ url('admin/masterdata/kelompok-mk') }}" role="tab" aria-controls="kelMK" aria-selected="true">Kelompok Matakuliah</a></li> --}}
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterKur-tabs" href="{{ url('admin/masterdata/kurikulum') }}" role="tab" aria-controls="masterKur" aria-selected="false" tabindex="-1">Master Kurikulum</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="MkKur-tab" href="#" role="tab" aria-controls="MkKur" aria-selected="false" tabindex="-1">Matakuliah Kurikulum</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="MkKur" role="tabpanel" aria-labelledby="MkKur-tab">
                                <div class="mt-4">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select name="kurikulum" onchange="getMK()" id="kurikulum" class="form-control">
                                                <option value="" disabled selected>Pilih Kurikulum</option>
                                                @foreach($kurikulum as $kur)
                                                    <option value="{{ $kur['id'] }}">{{ $kur['kode_kurikulum'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div id="listMK"></div>
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
            $("#tableMK").DataTable({
                responsive: true
            })
        })
        function getMK(){
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/admin/masterdata/matakuliah-kurikulum/get',
                type: 'post',
                data: {
                    id_kur: $('#kurikulum').val(),
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    $("#listMK").html(res)
                }
            })
        }
        function updateMK(kode){
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/admin/masterdata/matakuliah/update',
                type: 'post',
                dataType: 'json',
                data: {
                    kode_matkul: $('#kode_matkul_'+kode).val(),
                    nama_matkul: $('#nama_matkul_'+kode).val(),
                    nama_inggris: $('#nama_inggris_'+kode).val(),
                    kelompok: $('#kelompok_'+kode).val(),
                    rumpun: $('#rumpun_'+kode).val(),
                    status: $('#status_'+kode).val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Matakuliah Berhasil ditambahkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/matakuliah';
                    }else{
                        swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Matakuliah Gagal ditambahkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/matakuliah';
                    }
                }
            })
        }
    </script>
@endsection
