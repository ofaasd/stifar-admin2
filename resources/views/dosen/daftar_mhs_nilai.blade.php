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
    <li class="breadcrumb-item">Mahasiswa</li>
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
                            </div>
                            <div class="col-sm-6">
                                <b>Kontrak Kuliah</b>
                                <table>
                                    <tr>
                                        <td>
                                            Persentase Tugas
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_tugas" class="form-control" value="{{ $kontrak->tugas?? 0 }}">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Persentase UTS
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_uts" class="form-control" value="{{ $kontrak->uts?? 0 }}">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Persentase UAS
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_uas" class="form-control" value="{{ $kontrak->uas?? 0 }}">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button onclick="simpanKontrak({{ $jadwal['id'] }})" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Simpan Kontrak</button>
                                        </td>
                                    </tr>
                                </table>
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
                                            <td>{{ $row['nim'] }}</td>
                                            <td>{{ $row['nama'] }}</td>
                                            <td>
                                                <input type="number" class="form-control" id="nilai_tugas" value="0">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="nilai_uts" value="0">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="nilai_uas" value="0">
                                            </td>
                                            <td>
                                                |
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
