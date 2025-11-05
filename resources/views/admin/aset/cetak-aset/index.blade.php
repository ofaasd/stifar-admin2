@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title2}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Aset</li>
    <li class="breadcrumb-item active">Cetak</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="card">

            <div class="card-body">
                <form id="form-cetak" action="javascript:void(0)">
                    @csrf
                    <label for="labelDropdown">Pilih Aset:</label>
                    <select class="form-control" id="labelDropdown" name="aset">
                        <option value="#" disabled>-- Pilih Aset --</option>
                        <option value="all_all">Semua</option>
                        <optgroup label="Jenis Barang">
                            @foreach ($jenisBarang as $row)
                                <option value="barang_{{ $row->kode }}">{{ $row->kode }} - {{ $row->nama }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Jenis Kendaraan">
                            @foreach ($jenisKendaraan as $row)
                                <option value="kendaraan_{{ $row->kode }}">{{ $row->kode }} - {{ $row->nama }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Ruang">
                            @foreach ($ruang as $r)
                                <option value="ruang_{{ str_replace(' ', '', $r->nama_ruang) }}">{{ $r->nama_ruang }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    
                    <div class="col-md-6 mt-3">
                        <button id="btnCetak" type="submit" class="btn btn-primary">Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>

    <script>
        $(function () {
            const baseUrl = {!! json_encode(url('/')) !!};
            const title = "{{strtolower($title)}}";
            const page = '/'.concat("admin/aset/").concat(title);

            $('#form-cetak').on('submit',function(e){
                    e.preventDefault();
                    var btnSubmit = $('#btnCetak');
                    btnSubmit.prop('disabled', true); 
                    btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');

                    $.ajax({
                        data: $('#form-cetak').serialize(),
                        url: ''.concat(baseUrl).concat(page).concat("/cetak"),
                        type: 'POST',
                        success: function success(response) {
                            swal({
                                icon: 'success',
                                title: 'Successfully '.concat(response.message, '!'),
                                text: ''.concat(title, ' ').concat(response.message, ' Successfully.'),
                                customClass: {
                                confirmButton: 'btn btn-success'
                            }
                            });

                            const pdfWindow = window.open("", "_blank");
                            pdfWindow.document.write(
                                "<iframe width='100%' height='100%' src='data:application/pdf;base64," +
                                response.pdf +
                                "'></iframe>"
                            );

                            btnSubmit.prop('disabled', false).text('Cetak');
                        },
                        error: function error(err) {
                            console.log('====================================');
                            console.log(err);
                            console.log('====================================');
                            swal({
                                title: 'Duplicate Entry!',
                                text: title + ' Not Saved !',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                            btnSubmit.prop('disabled', false);
                            btnSubmit.text('Cetak');
                        }
                    });
                });
            });
    </script>
@endsection
