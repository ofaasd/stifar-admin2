@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item active">Kuesioner</li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header bg-grey">
                        <div class="row">
                            <div class="col-md-12">
                                 <table class="table" >
                                    <tr>
                                        <td>Nama Matakuliah</td>
                                        <td>: <b><?=$matkul?></b></td>
                                    </tr>
                                    <tr>
                                        <td>Total Kuesioner</td>
                                        <td>: <?=$kuesioner_terisi + 1?> / <?= $kuesioner_total ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0">
                        <form method="post" action="{{url('/mhs/kuesioner_mhs')}}">
                            @csrf
                            <input type="hidden" name="nim" value="<?=$nim?>">
                            <input type="hidden" name="id_jadwal" value="<?=$new_jadwal?>">
                            <input type="hidden" name="id_ta" value="<?=$ta?>">
                            <table class="table" style="table-layout:fixed; ">
                                <thead>
                                    <tr>
                                        <th style="word-wrap: break-word;min-width: 160px;max-width: 200px; white-space: normal !important;">Pertanyaan</th>
                                        <th class="text-center">Sangat Tidak Setuju</th>
                                        <th class="text-center">Tidak Setuju</th>
                                        <th class="text-center">Setuju</th>
                                        <th class="text-center">Sangat Setuju</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($pertanyaan as $row){
                                            if($row->category <=3 ){
                                    ?>
                                        <tr>
                                            <td style="word-wrap: break-word;min-width: 160px;max-width: 160px; white-space: normal !important;"><?=$row->soal?></td>
                                            <td class="text-center"><input type="radio" name="soal[<?=$row->id?>]" value=1 required></td>
                                            <td class="text-center"><input type="radio" name="soal[<?=$row->id?>]" value=2 required></td>
                                            <td class="text-center"><input type="radio" name="soal[<?=$row->id?>]" value=3 required></td>
                                            <td class="text-center"><input type="radio" name="soal[<?=$row->id?>]" value=4 required></td>
                                        </tr>
                                    <?php
                                            }
                                        }
                                    ?>
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td colspan=5>
                                            <input type="submit" class="btn btn-primary" value="Simpan">
                                        </td>
                                    </tr>
                                </tfooter>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
