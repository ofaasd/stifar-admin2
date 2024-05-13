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
                                    <label for="ktp">No. KTP</label>
                                    <input class="form-control" id="ktp" type="text" name="ktp" placeholder="No. KTP" required="" value="{{$peserta->noktp}}">
                                </div>
                                <div class="mb-2">
                                    <label for="nama">Nama Lengkap </label>
                                    <input class="form-control" id="nama" type="text" name="nama" placeholder="Nama Lengkap" required="" value="{{$peserta->nama}}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="tl">Tempat Lahir </label>
                                            <input class="form-control" id="tl" type="text" name="tl" placeholder="Tempat Lahir" required="" value="{{$peserta->tempat_lahir}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="tgl">Tanggal Lahir </label>
                                            <input class="form-control " id="tgl" type="date" name="tgl" placeholder="Tanggal Lahir" required="" value="{{date('Y-m-d', strtotime($peserta->tanggal_lahir))}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="jk">Jenis Kelamin </label>
                                            <select name="jk" class="form-control" required="" id="jk">
                                                <option selected="" value="{{$peserta->jk}}">@if($peserta->jk == 1) Laki - Laki @else Perempuan @endif</option>
                                                <option value="1">Laki - Laki</option>
                                                <option value="2">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="tgl">Agama</label>
                                            <select name="agama" class="form-control" required="">
                                                <option value="{{ $peserta->agama }}" selected="" >@php
                                                if($peserta->agama == 1 ){
                                                    echo "Islam";
                                                }else if($peserta->agama == 2){
                                                    echo "Kristen";
                                                }else if($peserta->agama == 3){
                                                    echo "Katolik";
                                                }else if($peserta->agama == 4){
                                                    echo "Hindu";
                                                }else if($peserta->agama == 5){
                                                    echo "Budha";
                                                }else if($peserta->agama == 6){
                                                    echo "Konghucu";
                                                }else{
                                                    echo "Lainnya";
                                                }
                                            @endphp </option>
                                                <option value="1">Islam</option>
                                                <option value="2">Kristen</option>
                                                <option value="3">Katolik</option>
                                                <option value="4">Hindu</option>
                                                <option value="5">Budha</option>
                                                <option value="6">Konghucu</option>
                                                <option value="99">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="ibu">Nama Ibu </label>
                                    <input class="form-control" id="ibu" type="text" name="ibu" placeholder="Nama Ibu" required="" value="{{$peserta->nama_ibu}}">
                                </div>
                                <div class="mb-2">
                                    <label for="ayah">Nama Ayah </label>
                                    <input class="form-control" id="ayah" type="text" name="ayah" placeholder="Nama Ayah" required="" value="{{$peserta->nama_ayah}}">
                                </div>
                                <div class="mb-2">
                                    <label for="hp_ortu">Nomor HP Orang Tua </label>
                                    <input class="form-control" id="hp_ortu" type="text" name="hp_ortu" placeholder="No. HP Orang Tua" required="" value="{{$peserta->hp_ortu}}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="tb">Tinggi Badan</label>
                                            <input class="form-control" id="tb" type="text" name="tb" placeholder="Tinggi Badan" required="" value="{{$peserta->tinggi_badan}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="bb">Berat Badan</label>
                                            <input class="form-control" id="bb" type="text" name="bb" placeholder="Berat Badan " required="" value="{{$peserta->berat_badan}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="hp">Nomor Handphone</label>
                                    <input class="form-control" id="hp" type="text" name="hp" placeholder="Nomor Handphone" required="" value="{{$peserta->hp}}">
                                </div>
                                <div class="mb-2">
                                    <label for="telepon">Nomor WA Aktif</label>
                                    <input class="form-control" id="telepon" type="text" name="telepon" placeholder="Nomor Telepon" required="" value="{{$peserta->telpon}}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="mb-2">
                                    <label for="telepon">Status Warga Negara</label>
                                    <select name="warga_negara" id="wn" class="form-control" required="">
                                        <option>-- Status Warga Negara --</option>
                                        <option value="1" {{($peserta->warga_negara == 1)?"selected":""}}>WNI (Warga Negara Indonesia)</option>
                                        <option value="2" {{($peserta->warga_negara == 2)?"selected":""}}>WNA (Warga Negara Asing)</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="provinsi">Provinsi</label>
                                    <select name="provinsi" id="provinsi" class="form-control" required="">
                                        <option>Pilih Provinsi</option>
                                        @foreach($wilayah  as $w)
                                            <option value="{{$w->id_wil}}" {{($peserta->provinsi == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="kotakab">Kota</label>
                                    <select name="kotakab" id="kotakab" class="form-control" required="">
                                        <option>Pilih Kota</option>
                                        @foreach($kota  as $w)
                                            <option value="{{$w->id_wil}}" {{($peserta->kotakab == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="kecamatan">kecamatan</label>
                                    <select name="kecamatan" id="kecamatan" class="form-control" required="">
                                        <option>Daftar Kecamatan</option>
                                        @foreach($kecamatan  as $w)
                                            <option value="{{$w->id_wil}}" {{($peserta->kecamatan == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="kelurahan">Kelurahan</label>
                                    <input class="form-control" id="kelurahan" type="text" name="kelurahan" placeholder="kelurahan" required="" value="{{$peserta->kelurahan}}">
                                </div>
                                <div class="mb-2">
                                    <label for="pos">Kode Pos</label>
                                    <input class="form-control" id="pos" type="text" name="pos" placeholder="Kode Pos" required="" value="{{$peserta->kodepos}}">
                                </div>
                                <div class="mb-2">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" type="text" name="alamat" placeholder="Hanya nama kampung, jalan dan nomor rumah saja " required="">{{$peserta->alamat}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="rt">RT</label>
                                            <input type="text" class="form-control" id="rt" type="text" name="rt" placeholder="RT" required="" value="{{$peserta->rt}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="rw">RW</label>
                                            <input type="text" class="form-control" id="rw" type="text" name="rw" placeholder="RW" required="" value="{{$peserta->rw}}">
                                        </div>
                                    </div>
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
    $('#provinsi').change(function(){
        //alert("asdasd");
        var id=$(this).val();
        const url = "{{URL::to('admin/admisi/peserta/daftar_kota')}}";
        $.ajax({
            url : url,
            method : "POST",
            data : {"_token": "{{ csrf_token() }}",id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '<option value="0">--Pilih Kota</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+ data[i].id_wil +'">'+data[i].nm_wil+'</option>';
                }
                $('#kotakab').html(html);

            }
        });
    });
    $('#kotakab').change(function(){
        //alert("asdasd");
        var id=$(this).val();
        const url = "{{URL::to('admin/admisi/peserta/daftar_kota')}}";
        $.ajax({
            url : url,
            method : "POST",
            data : {"_token": "{{ csrf_token() }}",id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '<option value="0">-- Pilih Kecamatan</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+ data[i].id_wil +'">'+data[i].nm_wil+'</option>';
                }
                $('#kecamatan').html(html);

            }
        });
    });
</script>
@endsection
