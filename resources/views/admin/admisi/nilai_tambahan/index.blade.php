@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3><a href='{{URL::to('admin/admisi/peringkat')}}' class='btn btn-primary'><i class='fa fa-arrow-left'></i> Back</a> {{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Peringkat PMDP</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Nama Peserta</td><td>: {{$peserta->nama}}</td>
                                    </tr>
                                    <tr>
                                        <td>No. Pendaftaran</td><td>: {{$peserta->nopen}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a href="#" class="btn btn-primary add_nilai" data-id="0" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal">Tambah Nilai</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive" id="my-table">

                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
    <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form action="javascript:void(0)" id="formAdd">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Add Nilai Tambahan</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="number" name="nilai" id="nilai" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            refresh();
        });

        const baseUrl = {!! json_encode(url('/')) !!};
        const refresh = () =>{
            $.ajax({
                url: ''.concat(baseUrl).concat('/admin/admisi/nilai_tambahan/{{$id}}/table'),
                type: 'GET',
                success: function success(data) {
                    $("#my-table").html(data);
                }
            });
        }


    </script>
@endsection
