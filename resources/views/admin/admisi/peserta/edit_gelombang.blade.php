@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3><a href='{{URL::to('admin/admisi/peserta')}}' class='btn btn-primary'><i class='fa fa-arrow-left'></i> Back</a> {{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Edit Peserta Didik</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            @include('admin/admisi/peserta/menu_edit')
        </div>
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Data pribadi</h3>
                </div>

                <div class="card-body">
                    <form action="{{URL::to('admin/admisi/peserta/' . $id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="put" />
                        <input type="hidden" name="id" value="{{$peserta->id}}">
                        <input type="hidden" name="action" value="{{$action}}">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="mb-2">
                                    <label for="gel_ta">Tahun Ajaran</label>
                                    <input class="form-control" id="gel_ta" type="text" name="gel_ta" placeholder="Gel TA" value="{{$ta->awal}}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label for="jalur">Pilih Jalur</label>
                                    <select name="jalur" id="jalur" class="form-control">
                                        <option value="0">--Pilih Jalur</option>
                                        @foreach($jalur as $row)
                                            <option value="{{$row->id}}" {{($peserta->jalur_pendaftaran == $row->id)?"selected":""}}>{{$row->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="gel_text">Pilih Gelombang</label>
                                    <select name="gelombang" id="gelombang" class="form-control" required="">
                                        <option value="0">--Pilih Gelombang</option>
                                        @foreach($gelombang as $row)
                                        <option value="{{$row->id}}" {{($peserta->gelombang == $row->id)?"selected":""}}>{{$row->nama_gel}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <h3>Pilihan Program Studi</h3>
                                <hr>
                                <div class="mb-2" id="jurusan">
                                    @for($i=1; $i <= $pilihan;$i++)
                                        <div class="mb-2">
                                            <label for='prodi'>Program Studi {{$i}}</label>
                                            <select name='prodi[]' class="form-control">
                                                @foreach($prodi as $row)
                                                <option value='{{$row->id_program_studi}}'
                                                @if($i == 1)
                                                    @if($peserta->pilihan1 == $row->id_program_studi)
                                                        selected
                                                    @endif
                                                @else
                                                    @if($peserta->pilihan2 == $row->id_program_studi)
                                                    selected
                                                    @endif
                                                @endif
                                                >{{$row->nama_prodi}} {{$row->keterangan ?? ''}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <input type="submit" value="Simpan" class="btn btn-primary col-md-12">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $('#jalur').change(function(){
    const jalur = $(this).val();
    $.ajax({
        url : "{{URL::to('admin/admisi/peserta/get_gelombang')}}",
        method: "POST",
        data:{"_token": "{{ csrf_token() }}",id:jalur},
        async: false,
        dataType: "json",
        success: function(data){
            $("#gelombang").html("<option value=''>Plih Gelombang</option>");
            let i;
            for (i = 0; i < data.length; i++) {
                $("#gelombang").append('<option value="'+ data[i].id +'">'+data[i].nama_gel+'</option>');
            }
        }
    });
});
$('#gelombang').change(function(){
    get_jurusan();
})
const get_jurusan = () =>
{
    const gelombang = $("#gelombang").val();
    $.ajax({
        url : "{{URL::to('admin/admisi/peserta/get_jurusan')}}",
        method: "POST",
        data:{"_token": "{{ csrf_token() }}",id:gelombang},
        async: false,
        dataType: "json",
        success: function(data){
            //alert(data);
            //alert(data);
            temp = "";

            data.forEach((item, index) => {
                temp += `<div class="mb-2"> <label for='prodi'>Program Studi ${index + 1}</label>
                <select name='prodi[]' class="form-control">`;
                item.forEach((item2, index2) => {
                    temp += `<option value='${item2.id_program_studi}'>${item2.nama_prodi} ${item2.keterangan ? $item2.keterangan : ""}</option>`;
                });
                temp += `</select></div>`;
            });

            $("#jurusan").html(temp);
        }
    });
}
</script>
@endsection
