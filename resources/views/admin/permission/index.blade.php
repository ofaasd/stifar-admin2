@extends('layouts.master')
@section('title', 'Data Permission')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <a href="{{ route('permission.create') }}" class="btn btn-primary">+ Tambah Permission</a>
                </div>
                <div class="card-body">
                    <textarea id='my_column' style="display:none">@foreach($indexed as $value){{$value . "\n"}}@endforeach</textarea>
                    <div class="table-responsive">
                        <table class="display" id="permission-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Nama Permission</th>
                                    <th>Guard</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script>
$(function () {
    const dt = $("#permission-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('permission.index') }}",
        columns: [
            { data: null, defaultContent: '', orderable: false },
            { data: 'fake_id' },
            { data: 'name' },
            { data: 'guard_name' },
            { 
                data: null,
                render: function(data, type, full) {
                    return `
                        <a href="{{ url('admin/permission') }}/${full.id}/edit" class="btn btn-sm text-primary"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm text-danger delete-record" data-id="${full.id}"><i class="fa fa-trash"></i></button>
                    `;
                }
            }
        ]
    });

    // Handle Delete via Ajax
    $(document).on('click', '.delete-record', function() {
        let id = $(this).data('id');
        swal({
            title: "Yakin hapus?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: `{{ url('admin/permission') }}/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        dt.draw();
                        swal("Berhasil dihapus!", { icon: "success" });
                    }
                });
            }
        });
    });
});
</script>
@endsection