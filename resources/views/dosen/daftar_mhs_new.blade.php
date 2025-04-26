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
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>[{{ $jadwal['kode_matkul'] }}] - {{ $jadwal['nama_matkul'] }}</h5>
                                <h6>{{ $jadwal['hari'] }}, {{ $jadwal['nama_sesi'] }}</h6>
                                <h6>Pertemuan Ke : {{$pertemuan->no_pertemuan}}</h6>
                                <h6>Tanggal Pertemuan : {{date('d-m-Y',strtotime($pertemuan->tgl_pertemuan))}}</h6>
                                <table>
                                    <tr><td>Total Hadir</td><td>: {{$total_hadir ?? 0}}</td></tr>
                                    <tr><td>Total Tidak Hadir</td><td>: {{$total_tidak_hadir ?? 0}}</td></tr>
                                    <tr><td>Total Sakit</td><td>: {{$total_sakit ?? 0}}</td></tr>
                                    <tr><td>Total Izin</td><td>: {{$total_izin ?? 0}}</td></tr>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <!-- <b>Kontrak Kuliah</b>
                                <table>
                                    <tr>
                                        <td>
                                            Persentase Tugas
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_tugas" class="form-control">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Persentase UTS
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_uts" class="form-control">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Persentase UAS
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="number" id="persentase_uas" class="form-control">
                                        </td>
                                        <td>%</td>
                                    </tr>
                                </table> -->
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <form method="post" action={{url("dosen/absensi/save_absensi_new")}}>
                                @csrf
                                <input type="hidden" name="id_jadwal" value="{{$jadwal->id}}">
                                <input type="hidden" name="id_pertemuan" value="{{$pertemuan->id}}">
                                <table class="display" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Presensi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($daftar_mhs as $row)

                                            <tr>
                                                <td>{{ $no++ }}<input type="hidden" name="id_mhs[]" value="{{$row->id_mhs}}"></td>
                                                <td>{{ $row['nim'] }}</td>
                                                <td>{{ $row['nama'] }}</td>
                                                <td>
                                                    <select name="type[]" class="form-control">
                                                        <option value="1" {{ $absensi[$row->id_mhs] == 1? 'selected=""':''}}>Hadir</option>
                                                        <option value="0" {{ $absensi[$row->id_mhs] == 0? 'selected=""':''}}>Tidak Hadir</option>
                                                        <option value="2" {{ $absensi[$row->id_mhs] == 2? 'selected=""':''}}>Sakit</option>
                                                        <option value="3" {{ $absensi[$row->id_mhs] == 3? 'selected=""':''}}>Izin</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <input type="submit" value="Simpan Perubahan" class="btn btn-primary mt-3 mb-3 col-md-12">
                            </form>
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
        $(function() {
            $("#myTable").DataTable({
                responsive: true,
                paging: false
,
            })
        })
    </script>
@endsection
