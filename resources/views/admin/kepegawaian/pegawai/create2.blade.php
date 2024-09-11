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
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Tambah Pegawai</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
	  <div class="col-sm-12">
		<div class="card">
		  <div class="card-header">
			<h5>{{$title}}</h5>
		  </div>
		  <div class="card-body">
			<form class="f1" id="formPegawai" method="post" action="javascript:void(0)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="">
			  <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="col-sm-12 col-form-label">Jenis Pegawai : </label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="jenis_pegawai" id="jenis_pegawai" required>
                                        <option value="0">--- Pilih Jenis Pegawai --- </option>
                                        <?php
                                            foreach($jenis_pegawai as $row){
                                                echo "<option value='" . $row->id . "''>" . $row->nama . "</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="col-sm-12 col-form-label">Posisi Pegawai</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="posisi_pegawai" id="status_pegawai" required>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="col-sm-12 col-form-label">Nomor Induk Yayasan (NIY): </label>
                            <div class="col-sm-12">
                                <input type="hidden" class="form-control" name="nip" id="initial_npp" value="">
                                <div class="input-group">
                                    <input type="text" name="npp" class="form-control" placeholder="cth : 060710112" id="nip" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="col-sm-12 col-form-label">Homebase: </label>
                            <div id="homebase">
                                <select name="homebase" class="form-control">
                                    <option value="0">Tidak Ada</option>
                                    @foreach($progdi as $pro)
                                        <option value="{{$pro->id}}">{{$pro->nama_prodi}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">NIDN : </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="nidn" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">NUPTK : </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="nuptk" value="" >
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="col-sm-4 col-form-label">Nama Lengkap : </label>
                            <div class="col-sm-12">
                                <input type="text" id="nama_lengkap" class="form-control" name="nama_lengkap" value="" required>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="col-sm-12 col-form-label">Gelar Depan : </label>
                                    <div class="col-sm-12"> --}}
                                        <input type="hidden" class="form-control" name="gelar_depan" value="" placeholder="Ex: Dr., Ir.,">
                                    {{-- </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="col-sm-12 col-form-label">Gelar Belakang : </label>
                                    <div class="col-sm-12"> --}}
                                        <input type="hidden" class="form-control" name="gelar_belakang" value="" placeholder="Ex: S.ked, S.Farm, M.Farm">
                                    {{-- </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="mb-2">
                            <label class="col-sm-4 col-form-label">Jenis Kelamin : </label>
                            <div class="col-sm-12">
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" >
                                    <?php
                                        foreach($jenis_kelamin as $key=>$row){
                                            echo "<option value='" . $key . "''>" . $row . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="col-sm-4 col-form-label">Email : </label>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="col-sm-4 col-form-label">Password : </label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-sm-4 col-form-label">No. KTP : </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="no_ktp" value="" >
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-4 col-form-label">No. KK : </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="no_kk" value="" >
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-sm-10 col-form-label">No. BPJS Kesehatan : </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="no_bpjs_kesehatan" value="" >
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-sm-12 col-form-label">No. BPJS ketenagakerjaan : </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="no_bpjs_ketenagakerjaan" value="" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Alamat Tempat Tinggal : </label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="alamat" value="" id="alamat" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Nama Provinsi :</label>
                            <div class="col-sm-12">
                                <p>
                                    <select name="provinsi" id="provinsi" class="form-control" required="">
                                        <option selected="" disabled="">Pilih Provinsi</option>
                                        <?php foreach($wilayah as $w){?>
                                        <option value="<?php echo $w->id_wil ?>"><?php echo $w->nm_wil ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Nama Kota/Kabupaten :</label>
                            <div class="col-sm-12">
                                <p>
                                    <select name="kotakab" id="kotakab" class="form-control" required="">
                                        <option selected="" disabled="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Nama Kecamatan :</label>
                            <div class="col-sm-12">
                                <p>
                                    <select name="kecamatan" id="kecamatan" class="form-control" required="">
                                        <option selected="" disabled="">Daftar Kecamatan</option>
                                    </select>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Nama Kelurahan :</label>
                            <div class="col-sm-12">
                                <p><input type="text" class="form-control" placeholder="Nama Kelurahan" oninput="this.className = ''" name="kelurahan" required=""></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Golongan Darah :</label>
                            <div class="col-sm-12">
                                <select name="golongan_darah" id="goldar" class="form-control">
                                        <option selected="" disabled="">Pilih Golongan Darah</option>
                                        <?php
                                            $golongan = array("A","B","AB","O");
                                            foreach($golongan as $value){?>
                                            <option value="<?php echo $value ?>"><?php echo $value ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Status Kepegawaian : </label>
                            <div class="col-sm-12">
                                <select class="form-control" name="status">
                                    <?php
                                        foreach($status as $row){
                                            echo "<option value='" . $row . "''>" . $row . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status Perkawinan : </label>
                            <div class="col-sm-12">
                                <select class="form-control" name="status_nikah" id="status_nikah">
                                    <?php
                                        foreach($status_kawin as $key=>$row){
                                            echo "<option value='" . $key . "''>" . $row . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="detail_status">
                            <div class="menikah">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama Pasangan : </label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="nama_pasangan" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tgl Lahir Pasangan : </label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" name="tgl_lahir_pasangan" value="" id="datepicker2">
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label">Pekerjaan Pasangan : </label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="pekerjaan_pasangan" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label">Jumlah Anak : </label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" name="jumlah_anak" value="" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
				<div class="f1-buttons">
                    <button class="btn btn-secondary" type="reset">Reset</button>
                    <button class="btn btn-primary btn-submit" type="submit">Submit</button>
				</div>
			  </fieldset>

			</form>
		  </div>
		</div>
	  </div>
	</div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/js/form-wizard/form-wizard-three.js')}}"></script>
<script src="{{asset('assets/js/form-wizard/jquery.backstretch.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script>
$(document.body).on("submit","#formPegawai",function(){
    const form = $(this).serialize();
    $.ajax({
        url:'{{URL::to('admin/kepegawaian/pegawai')}}',
        method:'POST',
        data:form,
        success:function(status){
            swal({
                icon: 'success',
                title: 'Successfully '.concat(status, '!'),
                text: ''.concat(status, ' Successfully.'),
                customClass: {
                    confirmButton: 'btn btn-success'
                }
            }).then(function() {
                window.location = "{{URL::to('admin/kepegawaian/pegawai')}}";
            });;
        },
        error: function error(err) {

            swal({
            title: 'Error Input Data',
            text: 'Data Not Saved !',
            icon: 'error',
            customClass: {
                confirmButton: 'btn btn-success'
            }
            });
        }
    });
});
$("#detail_status").css({"display":"none"});
$(document.body).on("change","#status_nikah",function(){
    if($(this).val() == 0){
        $("#detail_status").css({"display":"none"});
    }else{
        $("#detail_status").css({"display":"block"});
    }
});
$(document.body).on("change","#jenis_pegawai",function(){
    var id=$("#jenis_pegawai").val();
    $.ajax({
        url : "{{URL::to('admin/kepegawaian/pegawai/get_status')}}",
        method : "POST",
        data : {"_token": "{{ csrf_token() }}",id: id},
        async : false,
        dataType : 'json',
        success: function(data){
            var html = '';
            var i;
            html += '<option value="0">--- Pilih Posisi Pegawai --- </option>';
            for(i=0; i<data.length; i++){
                html += '<option value="'+ data[i].id +'">'+data[i].nama+'</option>';
            }
            $('#status_pegawai').html(html);

        }
    });
});
$(document).ready(function(){

		$("#nilai_mapel").hide();
		$("#area_pasca").hide();

});
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
$('#provinsi_sekolah').change(function(){
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
            $('#kota_sekolah').html(html);

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
