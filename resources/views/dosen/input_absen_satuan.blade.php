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
            @csrf
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>[{{ $jadwal['kode_matkul'] }}] - {{ $jadwal['nama_matkul'] }}</h5>
                                <h6>{{ $jadwal['hari'] }}, {{ $jadwal['nama_sesi'] }}</h6>
                            </div>
                            <div class="col-sm-6">
                                <div class="profile-title" style="float: right;">
                                    <div class="media">
                                        <div class="photo-profile">
                                            <img class="img-70 rounded-circle" alt="" src="{{ (!empty($mhs->foto_mhs))?asset('assets/images/mahasiswa/' . $mhs->foto_mhs):asset('assets/images/user/7.jpg') }}">
                                        </div>
                                        <div class="media-body" style="margin-left: 10px;">
                                            <h5 class="mb-1">{{$mhs->nama}}</h5>
                                            <p>{{$mhs->nim}}<br>{{$prodi[$mhs->id_program_studi]}}<br>{{$mhs->email}}<br>{{$mhs->hp}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="display" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal Pertemuan</th>
                                        <th>Pengampu</th>
                                        <th>Riwayat Absensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pertemuan as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row['tgl_pertemuan'] }}</td>
                                            <td>{{ $row['nama_lengkap'] }}</td>
                                            <td>
                                                <select name="editAbsensi{{ $row['id'] }}" id="editAbsensi{{ $row['id'] }}" class="form-control" onchange="updateAbsen({{ $row['id'] }}, {{ $row['id_jadwal'] }}, {{ $mhs['id'] }})">
                                                    <option value="" selected disabled> -- Pilih Tipe Absensi --</option>
                                                    <option value="0" {{ $row['type'] == 0? 'selected=""':''}}>Tidak Hadir</option>
                                                    <option value="1" {{ $row['type'] == 1? 'selected=""':''}}>Hadir</option>
                                                    <option value="2" {{ $row['type'] == 2? 'selected=""':''}}>Sakit</option>
                                                    <option value="3" {{ $row['type'] == 3? 'selected=""':''}}>Izin</option>
                                                </select>
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
        function updateAbsen(id_pertemuan, id_jadwal, id_mhs){
            var tipe = $('#editAbsensi'+id_pertemuan).val()
            $.ajax({
                url: baseUrl+'/dosen/simpan-absensi-satuan',
                type: 'post',
                data: {
                    id_jadwal: id_jadwal,
                    id_pertemuan: id_pertemuan,
                    id_mhs:id_mhs,
                    type:tipe
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
