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
    <li class="breadcrumb-item">KRM</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>[{{ $jadwal['kode_matkul'] }}] - {{ $jadwal['nama_matkul'] }}</h5>
                                <h6>{{ $jadwal['hari'] }}, {{ $jadwal['nama_sesi'] }}</h6>
                                <b>Kontrak Kuliah</b>
                                <table>
                                    <tr>
                                        <td>
                                            Persentase Tugas (%)
                                        </td>
                                        <td>
                                            Persentase UTS (%)
                                        </td>
                                        <td>
                                            Persentase UAS (%)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0px;">
                                            <input type="number" id="persentase_tugas" class="form-control form-control-sm" value="{{ $kontrak->tugas?? 0 }}">
                                        </td>
                                        <td style="padding-left: 0px;">
                                            <input type="number" id="persentase_uts" class="form-control form-control-sm" value="{{ $kontrak->uts?? 0 }}">
                                        </td>
                                        <td style="padding-left: 0px;">
                                            <input type="number" id="persentase_uas" class="form-control form-control-sm" value="{{ $kontrak->uas?? 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button onclick="simpanKontrak({{ $jadwal['id'] }})" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Simpan Kontrak</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table>
                                    <tr>
                                        <td><button class="btn btn-info btn-sm">Publish Tugas</button></td>
                                        <td style="padding-left: 10px;"><button class="btn btn-info btn-sm">Validasi Tugas</button></td>
                                    </tr>
                                    <tr>
                                        <td><button class="btn btn-info btn-sm">Publish UTS</button></td>
                                        <td style="padding-left: 10px;"><button class="btn btn-info btn-sm">Validasi UTS</button></td>
                                    </tr>
                                    <tr>
                                        <td><button class="btn btn-info btn-sm">Publish UAS</button></td>
                                        <td style="padding-left: 10px;"><button class="btn btn-info btn-sm">Validasi UAS</button></td>
                                    </tr>
                                </table>
                                <div class="mt-4"></div>
                                <span>A = 0; B = 0; C = 0; D = 0; E = 0;</span>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="display" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Nilai Tugas</th>
                                        <th>Nilai UTS</th>
                                        <th>Nilai UAS</th>
                                        <th>Nilai Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($daftar_mhs as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row['nims'] }}</td>
                                            <td>{{ $row['nama'] }}</td>
                                            <td>
                                                <input type="number" onchange="simpanNilai({{ $row['idmhs'] }}, {{ $id }}, '1', $(this).val())" class="form-control" id="nilai_tugas" value="{{ $row['ntugas'] }}">
                                            </td>
                                            <td>
                                                <input type="number" onchange="simpanNilai({{ $row['idmhs'] }}, {{ $id }}, '2', $(this).val())" class="form-control" id="nilai_uts" value="{{ $row['nuts'] }}">
                                            </td>
                                            <td>
                                                <input type="number" onchange="simpanNilai({{ $row['idmhs'] }}, {{ $id }}, '3', $(this).val())" class="form-control" id="nilai_uas" value="{{ $row['nuas'] }}">
                                            </td>
                                            <td>
                                                <span id="na">{{ $row['nakhir'] }} | {{ $row['nhuruf'] }}  </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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

    <script>
        const baseUrl = {!! json_encode(url('/')) !!};
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
        })
        function simpanNilai(idmhs, idjadwal, tipe, nilai){
            $.ajax({
                url: baseUrl+'/dosen/simpan-nilai',
                type: 'post',
                data: {
                    id_mhs: idmhs,
                    id_jadwal: idjadwal,
                    tipe: tipe,
                    nilai: nilai
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    console.log(res)
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil disimpan.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        $('#na').html(`<span>${ res.na } | ${ res.nh }</span>`)
                    }else{
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Server Error.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })
        }
        function simpanKontrak(id_jadwal){
            var persentase_tugas = $('#persentase_tugas').val();
            var persentase_uts = $('#persentase_uts').val();
            var persentase_uas = $('#persentase_uas').val();
            $.ajax({
                url: baseUrl+'/dosen/simpan-kontrak',
                type: 'post',
                data: {
                    id_jadwal: id_jadwal,
                    tugas: persentase_tugas,
                    uts: persentase_uts,
                    uas:persentase_uas
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil disimpan.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }else{
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Server Error.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })
        }
    </script>
@endsection
