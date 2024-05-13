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
                    <h3>Asal Sekolah & Nilai</h3>
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
                                    <label for="asal_sekolah">Nama Sekolah / Kampus</label>
                                    <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control" value="{{$asal->asal_sekolah ?? ''}}">
                                </div>
                                <div class="mb-2">
                                    <label for="jurusan">Jurusan / Program Studi</label>
                                    <input type="text" name="jurusan" id="jurusan" class="form-control" value="{{$asal->jurusan ?? ''}}">
                                </div>
                                <div class="mb-2">
                                    <label for="jurusan">Akreditasi</label>
                                    <select name="akreditasi" class="form-control" id="akre_default">
                                        <option value="A" {{(!empty($asal) && $asal->akreditasi == 'A')?"Selected":""}}>A (Unggul)</option>
                                        <option value="B" {{(!empty($asal) && $asal->akreditasi == 'B')?"Selected":""}}>B (Baik Sekali)</option>
                                        <option value="C" {{(!empty($asal) && $asal->akreditasi == 'C')?"Selected":""}}>C (Baik)</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="alamat_sekolah">Alamat Sekolah / Kampus</label>
                                    <input type="text" name="alamat_sekolah" id="alamat_sekolah" class="form-control" value="{{$asal->alamat ?? ''}}">
                                </div>
                                <div class="mb-2">
                                    <label for="provinsi_sekolah">Provinsi Sekolah / Kampus</label>
                                    <select name="provinsi_sekolah" id="provinsi_sekolah" class="form-control" required="">
                                        <option selected="" disabled="">Pilih Provinsi</option>
                                        @foreach($wilayah as $w)
                                            <option value="{{$w->id_wil}}" {{(!empty($asal) && $asal->provinsi_id == $w->id_wil)?"Selected":""}}>{{$w->nm_wil}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="judul_kota_sekolah">Kota Sekolah / Kampus</label>
                                    <select name="kota_sekolah" id="kota_sekolah" class="form-control" required="">
                                        <option selected="" disabled="">Pilih Kota/Kabupaten</option>
                                        @foreach($kota as $w)
                                            <option value="{{$w->id_wil}}" {{($asal->kota_id == $w->id_wil)?"Selected":""}}>{{$w->nm_wil}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($peserta->jalur_pendaftaran == 5 || $peserta->jalur_pendaftaran == 6)
                                <div id="area_pasca">
                                    <div class="mb-2">
                                        <label for="ipk">Nilai IPK S1</label>
                                        <input type="text" name="ipk" id="ipk" class="form-control" value="{{$peserta->ipk ?? ''}}"> <br /><small class="text-warning">Gunakan . (titik) untuk nilai desimal</small>
                                    </div>
                                    <div class="mb-2">
                                        <label for="toefl">Nilai TOEFL</label>
                                        <input type="text" name="toefl" id="toefl" class="form-control" value="{{$peserta->toefl ?? ''}}"> <br /><small class="text-warning">Gunakan . (titik) untuk nilai desimal</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-4">
                                @if($peserta->jalur_pendaftaran == 1 || $peserta->jalur_pendaftaran == 2)
                                    @for($i=0; $i<5; $i++)
                                        <h4>Nilai
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
                                        </h4>
                                        {{($i == 4)?"<p>(Diisi 0 jika belum mendapat rapor kelas XII semester 1)</p>" : "" }}
                                        <small class="text-warning">Gunakan . (titik) untuk nilai desimal</small><br /><br />

                                        @foreach($mapel as $key=>$value)
                                            @php $new_mapel = 'nilai_' . $key . '_smt' . ($i+1) @endphp

                                        Nilai {{ $value }}
                                        <p><input type="number" value="{{(!empty($rapor[$new_mapel]))?$rapor[$new_mapel]:""}}" name="{{$new_mapel}}" class="form-control" max="100" min="0"></p>
                                        @endforeach
                                    @endfor
                                @endif
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
