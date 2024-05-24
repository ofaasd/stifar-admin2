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




                    </div >
                </div>
            </div>
        </div>
    </div>
@endsection
