@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">

            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="jadwalHarian" role="tabpanel" aria-labelledby="jadwalHarian-tab">
                                <div class="table-responsive mt-2">
                                    <form method="POST" action="{{ url('admin/akademik/list_jawaban/' . $id . '/')}}">
                                        @csrf
                                        <input type="hidden" name="id_ta" id="id_ta" value="<?= $id ?>">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label for="Mata Kuliah">Jadwal</label>
                                                <select name="jadwal" id="jadwal" class="js-example-basic-single">
                                                    <option value="">--Pilih Jadwal--</option>
                                                    <?php foreach($krs_now as $row){
                                                        echo "<option value='" . $row->id . "'>" . $row->kode_jadwal  . ' - ' . $row->nama_matkul . "</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <input type="submit" class="btn btn-primary" value="Lihat Data">
                                            </div>
                                            @if($jadwal != 0)
                                                <h3>Hasil Kuesioner Matakuliah : <?= $jadwal_jawaban->nama_matkul ?> </h3>
                                                <table class="table table-hover" id="myTable">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan=2>Soal</th>
                                                            <th colspan=5>Jumlah Jawaban Responden</th>
                                                            <th colspan=4>Presentase</th>
                                                        </tr>
                                                            <th>Sangat Baik</th>
                                                            <th>Baik</th>
                                                            <th>Cukup</th>
                                                            <th>Kurang</th>
                                                            <th>Total</th>
                                                            <th>Sangat Baik</th>
                                                            <th>Baik</th>
                                                            <th>Cukup</th>
                                                            <th>Kurang</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $total_all = 0;

                                                        @endphp
                                                        @foreach($result as $key=>$value)
                                                            <tr>
                                                                <td><?= $value ?></td>
                                                                <?php
                                                                    $total_baris = 0;
                                                                    for($i =4; $i >= 1; $i--){
                                                                        echo "<td>" . $jawaban_detail[$key][$i] . "</td>";
                                                                        $total_baris += $jawaban_detail[$key][$i];
                                                                    }
                                                                ?>
                                                                <td><?= $total_baris ?></td>
                                                                <?php
                                                                     for($i =4; $i >= 1; $i--){
                                                                        if(empty($jawaban_detail[$key][$i])){
                                                                            echo "<td>0</td>";
                                                                        }else{
                                                                            echo "<td>" . number_format(((float)$jawaban_detail[$key][$i]/ $total_baris * 100),2,'.',',') . "</td>";
                                                                        }
                                                                    }
                                                                ?>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script>
        $(function() {
            $("#myTable").DataTable({
                responsive: true,
                "bFilter": false,
                caption: "Hasil Kuesioner Mahasiswa",
                layout: {
                    topStart: {
                        buttons: ['copy', 'excel', 'pdf'],
                    }
                }
            })
        })
    </script>
@endsection
