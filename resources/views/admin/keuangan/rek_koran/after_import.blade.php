@extends('layouts.master')
@section('title', 'Gedung')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<style>

</style>
@endsection 

@section('breadcrumb-title')
    <h3>{{$title2}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Lapor Bayar</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> Data yang akan disimpan ke dalam database pembayaran adalah data dengan status berlogo centang sedangkan data rekening koran yang tidak tercentang akan masuk ke data arsip
                    </div>
                    <div class="card-body" style="overflow-x:scroll">
                        <form method="POST" action="{{url('admin/keuangan/rekening_koran/simpan_pembayaran')}}">
                            @csrf
                            <table class="table table-stripped">
                                <thead>
                                    <th>No.</th>
                                    <th>Post Date</th>
                                    <th>Eff Date</th>
                                    <th>Description</th>
                                    <th>Credit / Jumlah Bayar</th>
                                    <th>Transaction</th>
                                    <th>Ref No</th>
                                    <th>No Pendaftaran</th>
                                    <th>NIM</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
                                </thead>
                                <tbody>
                                    @foreach($rekening as $row)
                                    <tr>
                                        <td>
                                            {{$no++}}
                                            <input type="hidden" name="id[]" value="{{$row->id}}">    
                                        </td>
                                        <td>{{$row->post_date}}</td>
                                        <td>{{$row->eff_date}}</td>
                                        <td>{{$row->description}}</td>
                                        <td>
                                            {{number_format($row->credit,0,",",".")}}
                                            <input type="hidden" name="jumlah[]" value="{{$row->credit}}">
                                        </td>
                                        <td>{{$row->transaction}}</td>
                                        <td>{{$row->ref_no}}</td>
                                        <td>
                                            {{$nopen[$row->id]}}
                                            <input type="hidden" name="nopen[]" value={{$nopen[$row->id]}}>
                                        </td>
                                        <td>
                                            <div class='nopen-{{$nopen[$row->id]}}'>
                                            {!!(empty($nim[$row->id]))?
                                            "<a href=# class='btn btn-danger btn-sm nonim' data-id=" . $row->id . " data-nopen='" . $nopen[$row->id] . "' id='nim".$row->id."' data-bs-toggle='modal' data-original-title='test' data-bs-target='#nimModal'>NIM Tidak Ditemukan</a>":
                                            "<a href=# class='btn btn-success btn-sm nonim' data-id=" . $row->id . " data-nopen='" . $nopen[$row->id] . "' id='nim".$row->id."' data-bs-toggle='modal' data-original-title='test' data-bs-target='#nimModal'>" . $nim[$row->id] . "</a>"!!}
                                            </div>
                                            <input type="hidden" class="nopentbl-{{$nopen[$row->id]}}" name="nim[]" value="{{(empty($nim[$row->id]))?'':$nim[$row->id]}}">  
                                        </td>
                                        <td width="150"><textarea name="keterangan[]" class="form-control" id="keterangan" placeholder="Pembayaran UPP : xxx.xxx DPP : xxx.xxx" style="width:15 0px;"></textarea></td>
                                        <td>
                                            <div class='status-{{$nopen[$row->id]}}'>
                                                <span {!!($status[$row->id]==1)?
                                                "class='btn btn-success btn-sm'":"class='btn btn-danger btn-sm'"!!}>
                                                {!!($status[$row->id]==1)?
                                                "<i class='fa fa-check'></i>":"<i class='fa fa-ban'></i>"!!}</span>
                                            </div>
                                            <input type="hidden" class="statustbl-{{$nopen[$row->id]}}" name="status[]" value="{{$status[$row->id]}}">  
                                        </td>
                                        {{-- <td></td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 mt-3 mb-3">
                                    <input type="submit" value="Simpan ke data pembayaran" class="btn btn-primary col-12 btn-lg">
                                </div>
                            </div>
                        </form>
                        <div class="modal fade" id="nimModal" tabindex="-1" role="dialog" aria-labelledby="importModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formNim">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Update NIM Berdasarkan No Pendaftaran</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" id="id">
                                            <div class="mb-3" id="field-nopen">
                                                <label for="nopen" class="form-label">No Pendaftaran</label>
                                                <input type="text" class="form-control" name="nopen" id="nopen" readonly>
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="nama" id="nama" readonly>
                                            </div>
                                            <div class="mb-3" id="field-nim">
                                                <label for="nim" class="form-label">NIM</label>
                                                <select name="nim" class="select2_mhs" id="nim">
                                                    @foreach($mhs_all as $row)
                                                        <option value="{{$row->nim}}">{{$row->nim}} - {{$row->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3" id="field-simpan">
                                                <input type="checkbox" class="checkbox_animated" name="simpan_nopen" id="simpan_nopen"> <label for="simpan_nopen" class="form-label">Simpan No. Pendaftaran</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" id="btn-add" type="submit">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    <script>
        $(document).ready(function() {
            const baseUrl = {!! json_encode(url('/')) !!};
            $('.select2_mhs').select2({
                dropdownParent: $('#nimModal')
            });
            $(".nonim").click(function(){
                $("#nama").val('')
                $("#id").val($(this).data('id'));
                $("#nopen").val($(this).data('nopen'));
                $.ajax({
                    url:`{{url('admin/keuangan/rekening_koran/get_nama')}}`,
                    data:{
                        nopen:$(this).data('nopen'),
                        id:$(this).data('id'),
                    },
                    dataType:'json',
                    method:'GET',
                    success:function(data){
                        $("#nama").val((data.nama)?data.nama:'')
                        $("#nim").val((data.nim)?data.nim:'').trigger('change')
                    },
                })
            })
            $('#formNim').on('submit', function (e) {
                e.preventDefault();
                const myFormData = new FormData(this);

                var btnSubmit = $('#btn-add');
                btnSubmit.prop('disabled', true);
                const nopen = $("#nopen").val();
                const nim = $("#nim").val();

                $.ajax({
                    data: myFormData,
                    url: `${baseUrl}/admin/keuangan/rekening_koran/update_nim`,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    success: function (status) {
                        
                        $("#nimModal").modal('hide');
                        swal({
                            icon: 'success',
                            title: `Successfully Saved !`,
                            text: `Data successfully Saved`,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        $(`.nopentbl-${nopen}`).val(nim)
                        $(`.statustbl-${nopen}`).val(1)
                        $(`.nopen-${nopen}`).html(`<a href=# class='btn btn-success btn-sm nonim' data-id=" . $row->id . " data-nopen='" . $nopen[$row->id] . "' id='nim".$row->id."' data-bs-toggle='modal' data-original-title='test' data-bs-target='#nimModal'>${nim}</a>`)
                        $(`.status-${nopen}`).html(`<span class='btn btn-success btn-sm'>Dapat Diproses</span>`)
                        btnSubmit.prop('disabled', false);
                    },
                    error: function (xhr) {
                        $("#nimModal").modal('hide');
                        let errMsg = 'An error occurred. Please try again.';
                        if (xhr.status === 422) { // Laravel validation error
                            errMsg = xhr.responseJSON.message;
                        }
                        swal({
                            icon: 'error',
                            title: 'Error!',
                            text: errMsg,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection