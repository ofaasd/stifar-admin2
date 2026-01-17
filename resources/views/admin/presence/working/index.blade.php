@extends('layouts.master')
@section('title', 'Gedung')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>Jam Kerja Dosen</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Absensi</li>
    <li class="breadcrumb-item active">Jam Kerja</li>
@endsection
@section('content')

<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <div class="card col-md-12">
            <div class="card-header">
                <!-- <a href="javascript:void(0)" class="btn btn-primary btn-create" data-bs-toggle="modal" data-bs-target="#modal-add">+ Add Working Hour</a> -->
                <div class="modal fade" id="modal-add" aria-hidden="true" style="display: none;">
                    <form action="javascript:void(0)" method="post" id="formWorking">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div id="overlay-place">

                                </div>
                                <div class="modal-header">
                                    <h4 class="modal-title">Add New Working Hour</h4>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning">Change to 00:00 / 12:00 to empty the hour field</div>
                                    @csrf
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group">
                                        <label for="name">User</label>
                                        <select name="user_id" class="form-control" id="user_id">
                                        @foreach($user as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <table class="table table-stripped">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Start Hour</th>
                                                <th>End Hour</th>
                                            </tr>
                                        </thead>
                                        @for($i = 1; $i<=7; $i++)
                                        <tr>
                                            <input type="hidden" name="day[]" value="{{$i}}">
                                            <td>{{$array_day[$i]}}</td>
                                            <td><input type="time" name="working_start[]" id="working_start{{$i}}"></td>
                                            <td><input type="time" name="working_end[]" id="working_end{{$i}}"></td>
                                        </tr>
                                        @endfor
                                    </table>

                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-save">Save changes</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </form>
                    <!-- /.modal-dialog -->
                </div>
            </div>
            <div class="card-body">
                <div id="my-table">

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
    <script src="{{asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
    <script>
        function refresh_table(){
            $("#my-table").html(`<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>`);
            const url_table = "{!! url('working/get_table') !!}";
            $.get(url_table, function (data){
                $("#my-table").html(data);
            });
        }
        $(function () {
            refresh_table();
            $('#example2').DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $("#formWorking").submit(function(e){
                e.preventDefault();
                const data = $(this).serialize();
                const url = "{!! url('working') !!}";
                $("#overlay-place").html(`<div class="overlay">
                        <i class="fas fa-2x fa-sync fa-spin"></i>
                    </div>`);
                $.ajax({
                    url : url,
                    method : "POST",
                    data :data,
                    success : function(data){
                        swal({
                            icon: 'success',
                            title: 'Successfully',
                            text: 'Saved Successfully.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        $("#modal-add").modal("hide");
                        $(this).trigger("reset");
                        $("#overlay-place").html('');
                        refresh_table();
                    }
                });
            });
            $(".btn-create").click(function(){
                $('#formWorking').trigger("reset");
                $("#id").val('');
                $(".password-field").show();
            });
        });

        </script>
@endsection