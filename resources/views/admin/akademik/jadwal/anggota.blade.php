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
                    <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterJadwal-tab" href="{{ url('/admin/masterdata/jadwal/create/'.$idmk) }}" role="tab" aria-controls="masterJadwal" aria-selected="true">Master Jadwal</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="koorMK-tabs" href="{{ url('/admin/masterdata/koordinator-mk/'.$idmk) }}" role="tab" aria-controls="koorMK" aria-selected="false" tabindex="-1">Koordinator Matakuliah</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="DsnMK-tab" data-bs-toggle="tab" href="#DsnMK" role="tab" aria-controls="DsnMK" aria-selected="false" tabindex="-1">Anggota Matakuliah</a></li>
                            <!-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="Pertemuan-tab" href="{{ url('/admin/masterdata/pertemuan-mk/'.$idmk) }}" role="tab" aria-controls="Pertemuan" aria-selected="false" tabindex="-1">Pertemuan Matakuliah</a></li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="DsnMK" role="tabpanel" aria-labelledby="DsnMK-tab">
                                @csrf
                                <div class="row" style="padding-top: 20px;">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="kode_jadwal" class="form-label">Nama Anggota</label>
                                            <input type="text" value="{{ $idmk }}" name="idmk" id="idmk" hidden="" />
                                            <select name="nama_anggota" id="nama_anggota" class="form-control">
                                                <option value="" selected disabled>Pilih Dosen Anggota</option>
                                                @foreach($pegawai as $dsn)
                                                    <option value="{{ $dsn['id'] }}">{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" onclick="simpanAnggota()"><i class="fa fa-save"></i> Tambahkan</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="display" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>NPP</th>
                                                <th>Nama Dosen</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($anggota as $row)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $row['npp'] }}</td>
                                                    <td>{{ $row['nama_lengkap'] }}, {{ $row['gelar_belakang'] }}</td>
                                                    <td><a href="{{ url('jadwal/hapus-anggota/'.$row['id']) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
        })
        function simpanAnggota(){
            var id_pegawai_bio = $('#nama_anggota').val();
            var idmk = $('#idmk').val();
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/jadwal/save-anggota',
                type: 'post',
                dataType: 'json',
                data: {
                    id_pegawai_bio:id_pegawai_bio,
                    idmk:idmk
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 204){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Simpan Gagal!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Anggota Berhasil Terinputkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/anggota-mk/'+idmk;
                    }
                }
            });
        }
    </script>
@endsection