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
    <li class="breadcrumb-item">Mahasiswa</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            @csrf
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>[{{ $jadwal['kode_matkul'] }}] - {{ $jadwal['nama_matkul'] }}</h5>
                                <h6>{{ $jadwal['hari'] }}, {{ $jadwal['nama_sesi'] }}</h6>

                                <select name="pertemuan" id="pertemuan" class="form-control" onchange="PertemuanFunc()">
                                    <option value="" selected disabled> Pilih Pertemuan </option>
                                    @foreach($pertemuan as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['tgl_pertemuan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div id="vPertemuan"></div>
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
        })
        function PertemuanFunc(){
            var id_pertemuan = $('#pertemuan').val()
            $.ajax({
                url: baseUrl+'/dosen/tampil-pertemuan-absensi',
                type: 'post',
                data: {
                    id_pertemuan: id_pertemuan,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#vPertemuan').html(res)
                    swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                }
            })
        }
    </script>
@endsection
