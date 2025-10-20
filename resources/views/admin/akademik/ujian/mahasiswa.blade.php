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
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-4">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="tahun_ajaran">Pilih Tahun Ajaran KRS</label>
                                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
                                        @foreach($tahun_ajaran as $ta)
                                            <option value="{{ $ta['id'] }}">{{ $ta['kode_ta'] }} [ {{ $ta['tgl_awal'].' s/d '.$ta['tgl_akhir'] }} ]</option>
                                        @endforeach
                                    </select>
                                </div>
                                @role('super-admin')
                                <div class="form-group">
                                    <label for="tahun_ajaran">Program Studi</label>
                                    <select name="prodi"  id="prodi" class="form-control">
                                        @foreach($prodi as $row)
                                            <option value="{{ $row['id'] }}">{{ $row['nama_prodi'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endrole
                                @role('admin-prodi')
                                <input type="hidden" name="prodi" id="prodi" value="{{ $curr_prodi->id }}">
                                <input type="text" name="prodi_preview" id="prodi" class="form-control" readonly value="{{ $curr_prodi->nama_prodi }}">

                                @endrole
                                <div class="form-group">
                                    <label for="tahun_ajaran">Angkatan</label>
                                    <select name="angkatan"  id="angkatan" class="form-control">
                                        @foreach($angkatan as $row)
                                            <option value="{{ $row['angkatan'] }}">{{ $row['angkatan'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <button name="btn-kirim" class="btn btn-primary btn-send" onclick="getMHS()">Lihat</button>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div id="listMHS"></div>
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
        function getMHS(){
            $(".btn-send").attr("disabled",true);
            $("#listMHS").html(`<div class="loader-box">
                            <div class="loader-2"></div>
                        </div>`);
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/admin/masterdata/krs/list-mhs',
                type: 'post',
                data: {
                    ta: $('#tahun_ajaran').val(),
                    prodi: $('#prodi').val(),
                    angkatan: $('#angkatan').val(),
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    $("#listMHS").html(res)
                    $(".btn-send").attr("disabled",false);
                }
            })
            $(".btn-send").attr("disabled",false);
        }
    </script>
@endsection
