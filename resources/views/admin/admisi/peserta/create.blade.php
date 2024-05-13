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
    <li class="breadcrumb-item active">Tambah Peserta Didik</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
	  <div class="col-sm-12">
		<div class="card">
		  <div class="card-header">
			<h5>Tambah Peserta Didik Baru</h5>
		  </div>
		  <div class="card-body">
			<form class="f1" method="post" action="{{URL::to('admin/admisi/peserta')}}" enctype="multipart/form-data">
                @csrf
			  <div class="f1-steps">
				<div class="f1-progress">
				  <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3"></div>
				</div>
				<div class="f1-step active">
				  <div class="f1-step-icon"><i class="fa fa-user"></i></div>
				  <p>Data Pribadi</p>
				</div>
				<div class="f1-step">
				  <div class="f1-step-icon"><i class="fa fa-clipboard"></i></div>
				  <p>Data Pendaftaran</p>
				</div>
				<div class="f1-step">
				  <div class="f1-step-icon"><i class="fa fa-file"></i></div>
				  <p>File Pendukung</p>
				</div>
			  </div>
			  <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="ktp">No. Pendaftaran</label>
                            <input class="form-control" id="nopen" type="text" name="nopen" placeholder="No, Pendaftaran | Kosongi Field ini jika belum ada">
                          </div>
                        <div class="mb-2">
                            <label for="ktp">No. KTP</label>
                            <input class="form-control" id="ktp" type="text" name="ktp" placeholder="No. KTP" required="">
                          </div>
                          <div class="mb-2">
                            <label for="nama">Nama Lengkap </label>
                            <input class="form-control" id="nama" type="text" name="nama" placeholder="Nama Lengkap" required="">
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="tl">Tempat Lahir </label>
                                    <input class="form-control" id="tl" type="text" name="tl" placeholder="Tempat Lahir" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="tgl">Tanggal Lahir </label>
                                    <input class="form-control " id="tgl" type="date" name="tgl" placeholder="Tanggal Lahir" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="jk">Jenis Kelamin </label>
                                    <select name="jk" class="form-control" required="" id="jk">
                                        <option selected="" disabled="">Jenis Kelamin</option>
                                        <option value="1">Laki - Laki</option>
                                        <option value="2">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="tgl">Agama</label>
                                    <select name="agama" class="form-control" required="">
                                        <option value="opt1" selected="" disabled="">Pilih Agama</option>
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
                            <input class="form-control" id="ibu" type="text" name="ibu" placeholder="Nama Ibu" required="">
                        </div>
                        <div class="mb-2">
                            <label for="ayah">Nama Ayah </label>
                            <input class="form-control" id="ayah" type="text" name="ayah" placeholder="Nama Ayah" required="">
                        </div>
                        <div class="mb-2">
                            <label for="hp_ortu">Nomor HP Orang Tua </label>
                            <input class="form-control" id="hp_ortu" type="text" name="hp_ortu" placeholder="No. HP Orang Tua" required="">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="tb">Tinggi Badan</label>
                                    <input class="form-control" id="tb" type="text" name="tb" placeholder="Tinggi Badan" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="bb">Berat Badan</label>
                                    <input class="form-control" id="bb" type="text" name="bb" placeholder="Berat Badan " required="">
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="hp">Nomor Handphone</label>
                            <input class="form-control" id="hp" type="text" name="hp" placeholder="Nomor Handphone" required="">
                        </div>
                        <div class="mb-2">
                            <label for="telepon">Nomor WA Aktif</label>
                            <input class="form-control" id="telepon" type="text" name="telepon" placeholder="Nomor Telepon" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="telepon">Status Warga Negara</label>
                            <select name="warga_negara" id="wn" class="form-control" required="">
                                <option selected="" disabled="">-- Status Warga Negara --</option>
                                <option value="1">WNI (Warga Negara Indonesia)</option>
                                <option value="2">WNA (Warga Negara Asing)</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="provinsi">Provinsi</label>
                            <select name="provinsi" id="provinsi" class="form-control" required="">
                                <option selected="" disabled="">Pilih Provinsi</option>
                                @foreach($wilayah  as $w)
                                    <option value="{{$w->id_wil}}">{{$w->nm_wil}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="kotakab">Kota</label>
                            <select name="kotakab" id="kotakab" class="form-control" required="">
                                <option selected="" disabled="">Pilih Kota</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="kecamatan">kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-control" required="">
                                <option selected="" disabled="">Daftar Kecamatan</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="kelurahan">Kelurahan</label>
                            <input class="form-control" id="kelurahan" type="text" name="kelurahan" placeholder="kelurahan" required="">
                        </div>
                        <div class="mb-2">
                            <label for="pos">Kode Pos</label>
                            <input class="form-control" id="pos" type="text" name="pos" placeholder="Kode Pos" required="">
                        </div>
                        <div class="mb-2">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" type="text" name="alamat" placeholder="Hanya nama kampung, jalan dan nomor rumah saja " required=""></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="rt">RT</label>
                                    <input type="text" class="form-control" id="rt" type="text" name="rt" placeholder="RT" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="rw">RW</label>
                                    <input type="text" class="form-control" id="rw" type="text" name="rw" placeholder="RW" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="f1-buttons">
				  <button class="btn btn-primary btn-next" type="button">Next</button>
				</div>
			  </fieldset>
			  <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="gel_ta">Tahun Ajaran</label>
                            <input class="form-control" id="gel_ta" type="text" name="gel_ta" placeholder="Gel TA" required="" value="{{$ta->awal}}" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="jalur">Pilih Jalur</label>
                            <select name="jalur" id="jalur" class="form-control">
                                <option value="0">--Pilih Jalur</option>
                                @foreach($jalur as $row)
                                    <option value="{{$row->id}}">{{$row->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="gel_text">Pilih Gelombang</label>
                            <select name="gelombang" id="gelombang" class="form-control" required="">
                                <option value="0">--Pilih Gelombang</option>
                            </select>
                        </div>
                        <br>
                        <h3>Pilihan Program Studi</h3>
                        <hr>
                        <div class="mb-2" id="jurusan">

                        </div>
                        <br>
                        <h3>Asal Sekolah</h3>
                        <hr>
                        <div class="mb-2">
                            <label for="asal_sekolah">Nama Sekolah / Kampus</label>
                            <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="jurusan">Jurusan / Program Studi</label>
                            <input type="text" name="jurusan" id="jurusan" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="jurusan">Akreditasi</label>
                            <select name="akreditasi" class="form-control" id="akre_default">
                                <option value="A">A (Unggul)</option>
                                <option value="B">B (Baik Sekali)</option>
                                <option value="C">C (Baik)</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="alamat_sekolah">Alamat Sekolah / Kampus</label>
                            <input type="text" name="alamat_sekolah" id="alamat_sekolah" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="provinsi_sekolah">Provinsi Sekolah / Kampus</label>
                            <select name="provinsi_sekolah" id="provinsi_sekolah" class="form-control" required="">
                                <option selected="" disabled="">Pilih Provinsi</option>
                                @foreach($wilayah as $w)
                                    <option value="{{$w->id_wil}}">{{$w->nm_wil}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="judul_kota_sekolah">Kota Sekolah / Kampus</label>
                            <select name="kota_sekolah" id="kota_sekolah" class="form-control" required="">
                                <option selected="" disabled="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                        <div id="area_pasca">
                            <div class="mb-2">
                                <label for="ipk">Nilai IPK S1</label>
                                <input type="text" name="ipk" id="ipk" class="form-control" > <br /><small class="text-warning">Gunakan . (titik) untuk nilai desimal</small>
                            </div>
                            <div class="mb-2">
                                <label for="toefl">Nilai TOEFL</label>
                                <input type="text" name="toefl" id="toefl" class="form-control"> <br /><small class="text-warning">Gunakan . (titik) untuk nilai desimal</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="nilai_mapel">
                        @for($i=0; $i<5; $i++)
                                <h3>Nilai
                                    @if($i < 2)
                                        Kelas X
                                    @elseif($i > 1 && $i < 4)
                                        Kelas XI
                                    @else
                                        Kelas XII
                                    @endif

                                    @if($i % 2 == 1)
                                        Semester 2
                                    @else
                                        Semester 1
                                    @endif
                                </h3>
                                {{($i == 4)?"<p>(Diisi 0 jika belum mendapat rapor kelas XII semester 1)</p>" : "" }}
                            <small class="text-warning">Gunakan . (titik) untuk nilai desimal</small><br /><br />

                            @foreach($mapel as $key=>$value)
                                @php $new_mapel = 'nilai_' . $key . '_smt' . ($i+1) @endphp

                            Nilai {{ $value }}
                            <p><input type="number" value="{{(!empty($rapor[$new_mapel]))?$rapor[$new_mapel]:""}}" name="{{$new_mapel}}" class="form-control" max="100" min="0"></p>
                            @endforeach
                        @endfor
                    </div>
                </div>

				<div class="f1-buttons">
				  <button class="btn btn-primary btn-previous" type="button">Previous</button>
				  <button class="btn btn-primary btn-next" type="button">Next</button>
				</div>
			  </fieldset>
			  <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="info_pmb">Dapat Info PMB darimana?</label>
                            <select name="info_pmb" class="form-control">
                                <option value="opt1" selected="" disabled="">- Pilih -</option>
                                <option value="1">Teman</option>
                                <option value="2">Kerabat / Orang Tua</option>
                                <option value="3">Sosial Media</option>
                                <option value="4">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="info_pmb">Ukuran Seragam</label>
                            <select name="ukuran_seragam" class="form-control">
                                <option value="opt1" selected="" disabled="">- Pilih Ukuran Seragam -</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="XXXL">XXXL</option>
                              </select>
                        </div>
                        <div class="mb-2">
                            <label for="info_pmb">Upload File Pendukung     </label>
                            <input type='file' class="form-control" name="foto" />
                            Maksimal 5 MB dengan format pdf.
                        </div>

                    </div>
                </div>
				<div class="f1-buttons">
				  <button class="btn btn-primary btn-previous" type="button">Previous</button>
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
<script>
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
$('#jalur').change(function(){
    const jalur = $(this).val();
    if(jalur == 1 || jalur == 2){
        $("#nilai_mapel").show();
    }else{
        $("#nilai_mapel").hide();
    }

    if(jalur == 5 || jalur == 6){
        $("#area_pasca").show();
    }else{
        $("#area_pasca").hide();
    }
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
                    temp += `<option value='${item2.id}'>${item2.nama_jurusan} ${item2.keterangan}</option>`;
                });
                temp += `</select></div>`;
            });

            $("#jurusan").html(temp);
        }
    });
}
</script>
@endsection
