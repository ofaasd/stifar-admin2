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
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="jadwalHarian" role="tabpanel" aria-labelledby="jadwalHarian-tab">
                                <div class="table-responsive mt-2">
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ url('/admin/akademik/list-soal') }}">
                                                @csrf
                                                <input type="hidden" name="id_ta" value="{{$id}}">
                                                <input type="hidden" name="id" value="">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah/Update Soal</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label for="no_soal">No. Soal</label>
                                                            <input class="form-control" type="text" name="no_soal" placeholder="No.">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="soal">Soal</label>
                                                            <input class="form-control" type="text" name="soal">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="category">Kategori</label>
                                                            <select name="category" class="form-control">
                                                                <option value="1">Materi Perkuliahan</option>
                                                                <option value="2">Kompetensi Professional</option>
                                                                <option value="3">Interaksi Dosen dan Mahasiswa</option>
                                                                <option value="4">Kepuasan Sarana Prasarana</option>
                                                                <option value="5">Kepuasan Pelayanan Tenaga Kependidikan</option>
                                                                <option value="6">Kepuasan Pelayanan Pengelola (STIFERA)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="tipe_soal">Tipe Pertanyaan</label>
                                                            <select name="tipe_soal" class="form-control">
                                                                <option value="1">Pilihan Ganda</option>
                                                                <option value="2">Uraian</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalStatusLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ url('/admin/akademik/list-soal/simpan_status') }}">
                                                @csrf
                                                <input type="hidden" name="id_ta" value="{{$id}}">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Aktif/Tidak Aktif Kuesioner</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="status">Status</label>
                                                            <select name="status" class="form-control">
                                                                <option value="0" {{ ($ta->kuesioner == 0)?'selected':""}} >Tidak Aktif</option>
                                                                <option value="1" {{ ($ta->kuesioner == 1)?'selected':""}} >Aktif</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row-inline">
                                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Soal</a>
                                            <a href="#" class="btn {{ ($ta->kuesioner == 0)?'btn-danger':"btn-success"}}" data-bs-toggle="modal" data-bs-target="#exampleModalStatus">Aktif/Tidak Aktif</a>
                                        </div>
                                        <div id="vJadwalHarian" class="mt-2">
                                            {!!(Session::get('success')) ? "<div class='alert alert-success'>" . Session::get('success') . "</div>":""!!}
                                            {!!(Session::get('error')) ? "<div class='alert alert-success'>" . Session::get('error') . "</div>":""!!}
                                            <table class="display" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Soal</th>
                                                        <th>Kategori</th>
                                                        <th>Tipe</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $i = 0;
                                                        $kategori = [
                                                            1=>'Materi Perkuliahan',
                                                            2=>'Kompetensi Profesional',
                                                            3=>'Interaksi Dosen dan Mahasiswa',
                                                            4=>'Kepuasan Sarana Prasarana',
                                                            5=>'Kepuasan Pelayanan Tenaga Kependidikan',
                                                            6=>'Kepuasan Pelayanan Pengelola (STIFERA)',
                                                        ];
                                                        $tipe = [
                                                            1=>'Pilihan Ganda',
                                                            2=>'Uraian',
                                                        ];
                                                    @endphp
                                                    @foreach($kuesioner as $row)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $row->soal }}</td>
                                                            <td>{{ $kategori[$row->category] }}</td>
                                                            <td>{{ $tipe[$row->tipe_soal] }}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{$row->id}}"><i class="fa fa-pencil"></i></a>
                                                                    <a href="#" class="btn btn-danger btn-sm delete-record" data-id="{{$row->id}}"><i class="fa fa-trash"></i></a></td>
                                                                </div>
                                                        </tr>
                                                        <div class="modal fade" id="exampleModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <form method="POST" action="{{ url('/admin/akademik/list-soal') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="id_ta" value="{{$id }}">
                                                                        <input type="hidden" name="id" value="{{$row->id }}">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Update Soal</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-12">
                                                                                <label for="no_soal">No. Soal</label>
                                                                                <input class="form-control" type="text" name="no_soal" placeholder="No." value="{{$row->no_soal}}">
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="soal">Soal</label>
                                                                                <input class="form-control" type="text" name="soal" value="{{$row->soal}}">
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="category">Kategori</label>
                                                                                <select name="category" class="form-control">
                                                                                    <option value="1" {{ ($row->category == 1)?"selected":"" }}>Materi Perkuliahan</option>
                                                                                    <option value="2" {{ ($row->category == 2)?"selected":"" }}>Kompetensi Professional</option>
                                                                                    <option value="3" {{ ($row->category == 3)?"selected":"" }}>Interaksi Dosen dan Mahasiswa</option>
                                                                                    <option value="4" {{ ($row->category == 4)?"selected":"" }}>Kepuasan Sarana Prasarana</option>
                                                                                    <option value="5" {{ ($row->category == 5)?"selected":"" }}>Kepuasan Pelayanan Tenaga Kependidikan</option>
                                                                                    <option value="6" {{ ($row->category == 6)?"selected":"" }}>Kepuasan Pelayanan Pengelola (STIFERA)</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="tipe_soal">Tipe Pertanyaan</label>
                                                                                <select name="tipe_soal" class="form-control">
                                                                                    <option value="1" {{ ($row->tipe_soal == 1)?"selected":"" }}>Pilihan Ganda</option>
                                                                                    <option value="2" {{ ($row->tipe_soal == 2)?"selected":"" }}>Uraian</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
            $("#myTable1").DataTable({
                responsive: true
            })
            $(document).on('click', '.delete-record', function () {
                const url = "{{url('/admin/akademik/list-soal/')}}"
                const id = $(this).data('id');
                // sweetalert for confirmation of delete
                swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
                }).then(function (result) {
                if (result) {

                    // delete the data
                    $.ajax({
                    type: 'DELETE',
                    url: ''.concat(url).concat("/",id),
                    data:{
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function success() {
                        swal({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The Record has been deleted!',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then(function(){
                            location.reload();
                        });
                    },
                    error: function error(_error) {
                        console.log(_error);
                    }
                    });

                } else {
                    swal({
                    title: 'Cancelled',
                    text: 'The record is not deleted!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                    });
                }
                });
            });
        })
    </script>
@endsection
