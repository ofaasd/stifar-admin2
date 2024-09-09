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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 project-list">
                <div class="card">
                   <div class="row">
                      <div class="col-md-12">
                         <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                            <li class="nav-item"><a href="{{URL::to('admin/masterdata/jadwal')}}" class="nav-link {{($id_prodi==0)?"active":""}}" data-id="0" id="top-home-tab" data-bs-toggle="tab" href="#top-home" role="tab" aria-controls="top-home" aria-selected="true"><i data-feather="target"></i>All</a></li>
                            @foreach($prodi as $prod)
                                <li class="nav-item"><a href="{{URL::to('admin/masterdata/jadwal/prodi/' . $prod->id)}}" class="nav-link {{($id_prodi==$prod->id)?"active":""}}" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} </a></li>
                            @endforeach
                         </ul>
                      </div>
                   </div>
                </div>
             </div>
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-header pb-0 card-no-border">

                    </div>
                    <div class="card-body">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="masterJadwal-tab" href="{{ url('/admin/masterdata/jadwal') }}" role="tab" aria-controls="masterJadwal" aria-selected="true">Jadwal Matakuliah</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="jadwalHarian-tab" href="{{ url('/admin/masterdata/jadwal-harian') }}" aria-controls="jadwalHarian" aria-selected="false">Jadwal Harian</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="masterJadwal" role="tabpanel" aria-labelledby="masterJadwal-tab">
                                <div class="table-responsive mt-2">
                                    <table class="display" id="myTable">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Kode Matakuliah</th>
                                                <th>Nama Matakuliah</th>
                                                <th>SKS</th>
                                                <th>Smt.</th>
                                                <th>Status Mata Kuliah</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mk as $value)
                                                @foreach($value as $mk)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $mk['kode_matkul'] }}</td>
                                                        <td>{{ $mk['nama_matkul'] }}</td>
                                                        <td>
                                                            @if(empty($mk['sks_praktek']) && !empty($mk['sks_teori']))
                                                                {{ $mk['sks_teori'] }} T
                                                            @elseif(empty($mk['sks_teori']) && !empty($mk['sks_praktek']))
                                                                {{ $mk['sks_praktek'] }} P
                                                            @elseif(!empty($mk['sks_teori']) && !empty($mk['sks_praktek']))
                                                                {{ $mk['sks_teori'] }} T / {{ $mk['sks_praktek'] }} P
                                                            @else
                                                                T / P
                                                            @endif
                                                        </td>
                                                        <td>{{ $mk['semester'] }}</td>
                                                        <td>{{ $mk['status_mk'] }}</td>
                                                        <td>
                                                            <a href="{{ url('admin/masterdata/jadwal/create/'. $mk['id']) }}" class="btn btn-sm btn-icon edit-record text-primary">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
    </script>
@endsection
