@extends('layouts.master')
@section('title', 'Gedung')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>Log Presensi</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Presence</li>
    <li class="breadcrumb-item active">Log</li>
@endsection
@section('content')

<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <div class="card-title">
                        Filter
                    </div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form method="GET" action="">
                                <div class="mb-2">
                                    <label for="date_start">Date Start</label>
                                    <input type="date" name="date_start" id="date_start" class="form-control" value="{{$date_start}}">
                                </div>
                                <div class="mb-2">
                                    <label for="date_end">Date End</label>
                                    <input type="date" name="date_end" id="date_end" class="form-control" value="{{$date_end}}">
                                </div>
                                <div class="mb-2">
                                    <input type="submit" value="Show" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        {{$title}}
                    </div>
                </div>
                <div class="card-body" style="overflow-x:scroll">
                    <table class="table datatables">
                        <thead>
                            <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>In</th>
                            <th>Late</th>
                            <th>Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list_absensi as $row)
                            <tr>
                                <td>{{$row->name}}</td>
                                <td>{{date('d-m-Y', strtotime($row->day))}}</td>
                                <td>
                                {{!empty($row->start) ? date('H:i:s', $row->start) : ''}}
                                <a href="https://maps.google.com?q={{$row->lat_start}},{{$row->long_start}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-map-marker-alt"></i></a>
                                <a href="javascript:void(0)" class="btn btn-success btn-xs" data-toggle="modal" data-target="#imageModal{{$row->id}}"><i class="fa fa-image"></i></a>
                                </td>
                                <td {{!empty($row->start_late) ? "class=bg-danger":"class=bg-success"}}>{{!empty($row->start_late) ? date('H:i:s', $row->start_late) : '00:00:00'}}</td>
                                <td>
                                {{!empty($row->end) ? date('H:i:s', $row->end) : '00:00:00'}}
                                <a href="https://maps.google.com?q={{$row->lat_end}},{{$row->long_end}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-map-marker-alt"></i></a>
                                <a href="javascript:void(0)" class="btn btn-success btn-xs" data-toggle="modal" data-target="#imageModalOut{{$row->id}}"><i class="fa fa-image"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="imageModal{{$row->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-body text-center">
                                    <img src="{{asset('img/upload/absensi')}}/{{$row->image_start}}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="imageModalOut{{$row->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-body text-center">
                                    <img src="{{asset('img/upload/absensi')}}/{{$row->image_end}}" alt="">
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
</section>

@endsection
@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
@endsection