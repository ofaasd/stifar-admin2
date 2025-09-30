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
                        <a href="{{ url('/dosen/download-krm') }}" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-download"></i> Unduh KRM</a>
                        <!-- Button trigger modal -->
                        <div class="table-responsive mt-2">
                            <table class="display" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Jadwal</th>
                                        <th>Hari & Waktu</th>
                                        <th>Matakuliah</th>
                                        <th>Ruang</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Status</th>
                                        <th>T/P</th>
                                        <th>Kuota</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwal as $jad)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $jad['kode_jadwal'] }}</td>
                                            <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                            <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                            <td>{{ $jad['nama_ruang'] }}</td>
                                            <td>{{ $jad['kode_ta'] }}</td>
                                            <td>{{ $jad['status'] }}</td>
                                            <td>{{ $jad['tp'] }}</td>
                                            <td>{{$jumlah_input_krs[$jad['id']]}} / {{ $jad['kuota'] }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    {{-- <a href="{{ url('/dosen/absensi/'.$jad['id'].'/input') }}" class="btn btn-info btn-xs"><i class="fa fa-list"></i>Absensi</a> --}}
                                                    <a href="javascript:void(0)" class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#exampleModal{{$jad['id']}}"><i class="fa fa-inbox"></i>RPS & Kontrak Kuliah</a>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModal{{$jad['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel{{$jad['id']}}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ url('/dosen/simpan_rps') }}" enctype="multipart/form-data">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Update RPS & Kontrak Kuliah</h5>
                                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <input type="hidden" name="id_mk" value="{{$jad['id_mk']}}">
                                                                    @if(empty($jad['rps']))
                                                                        <div class="alert alert-danger">File RPS Tidak Ditemukan</div>
                                                                    @else
                                                                        <a href="{{url('/assets/file/rps/' . $jad->rps)}}" class="btn btn-primary" target="_blank">Lihat RPS & Kontrak Kuliah</a>
                                                                    @endif

                                                                    <a href="{{url('/assets/file/rps/STIFAR - template RPS KPT 2024.docx')}}" class="btn btn-primary" target="_blank">Template RPS</a> <a href="{{url('/assets/file/rps/Kontrak perkuliahan Stifar 2024.docx')}}" class="btn btn-primary" target="_blank">Template Kontrak Perkuliahan</a><br /><br />
                                                                    <label for="rps">Upload RPS & Kontrak Kuliah</label>
                                                                    <input type="file" name="rps" class="form-control">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
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
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
        })
    </script>
@endsection
