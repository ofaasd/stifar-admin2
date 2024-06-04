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
    <li class="breadcrumb-item">Kepawaian</li>
    <li class="breadcrumb-item active">Data Pegawai</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>Registration</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{URL::to('admin/kepegawaian/pegawai')}}" method="POST">
                            <div class="row">
                                <div class="col-md-12">
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
                                    <div class="mb-2">
                                        <label class="col-sm-4 col-form-label"></label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="posisi_pegawai" id="status_pegawai" required>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="col-sm-12 col-form-label">Nomor Induk pegawai(NIP): </label>
                                        <div class="col-sm-12">
                                            <input type="hidden" class="form-control" name="nip" id="initial_npp" value="">
                                            <div class="input-group">
                                                <input type="text" name="npp" class="form-control" placeholder="cth : 060710112" id="nip" aria-describedby="inputGroupPrepend" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div id="homebase">

                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="col-sm-4 col-form-label">Nama Lengkap : </label>
                                        <div class="col-sm-12">
                                            <input type="text" id="nama_lengkap" class="form-control" name="nama_lengkap" value="" required>
                                        </div>
                                    </div>
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
                                        <label class="col-sm-4 col-form-label">Password : </label>
                                        <div class="col-sm-12">
                                            <input type="password" class="form-control" name="password" id="password" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h3>General information</h3>
                    </div>
                    <div class="card-body">


                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Alamat Tempat Tinggal : </label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="alamat" value="" id="alamat" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Nama Provinsi :</label>
                            <div class="col-sm-10">
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
                            <div class="col-sm-10">
                                <p>
                                    <select name="kotakab" id="kotakab" class="form-control" required="">
                                        <option selected="" disabled="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Nama Kecamatan :</label>
                            <div class="col-sm-10">
                                <p>
                                    <select name="kecamatan" id="kecamatan" class="form-control" required="">
                                        <option selected="" disabled="">Daftar Kecamatan</option>
                                    </select>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Nama Kelurahan :</label>
                            <div class="col-sm-10">
                                <p><input type="text" class="form-control" placeholder="Nama Kelurahan" oninput="this.className = ''" name="kelurahan" required=""></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Golongan Darah :</label>
                            <div class="col-sm-10">
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
                            <label class="col-sm-4 col-form-label">No. KTP : </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="no_ktp" value="" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">No. KK : </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="no_kk" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">No. BPJS Kesehatan : </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="no_bpjs_kesehatan" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-form-label">No. BPJS ketenagakerjaan : </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="no_bpjs_ketenagakerjaan" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-10 col-form-label">Status Kepegawaian : </label>
                            <div class="col-sm-10">
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
                            <div class="col-sm-10">
                                <select class="form-control" name="status_nikah" id="status_nikah">
                                    <?php
                                        foreach($status_kawin as $row){
                                            echo "<option value='" . $row . "''>" . $row . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="detail_status">
                            <div class="menikah">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama Pasangan : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nama_pasangan" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tgl Lahir Pasangan : </label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" name="tgl_lahir_pasangan" value="" id="datepicker2">
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label">Pekerjaan Pasangan : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="pekerjaan_pasangan" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label">Jumlah Anak : </label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" name="jumlah_anak" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama Pasangan : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nama_pasangan" value="" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div >
                </div>
            </div>
        </div>
    </div>
@endsection
