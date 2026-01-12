@extends('layouts.master')
@section('title', 'Manajemen Menu')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Setting</li>
    <li class="breadcrumb-item active">Menu Management</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <a href="{{url('admin/menu/create')}}" class="btn btn-primary">+ Tambah {{$title}}</a>
                </div>
                <div class="card-body">
                    <textarea id='my_column' style="display:none">@foreach($indexed as $value){{$value . "\n"}}@endforeach</textarea>
                    <div class="table-responsive">
                        <table class="display" id="menu-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Parent</th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Permission</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
    const baseUrl = "{{ url('/') }}";
    const my_column = $('#my_column').val().split('\n').filter(item => item.trim() !== "");
    let my_data = my_column.map(item => ({ data: item.replace(/ /g, '') }));

    const dt = $("#menu-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: baseUrl + "/admin/menu" },
        columns: my_data.concat([{data: null}]), // Add action column
        columnDefs: [
            { targets: 0, render: () => '' },
            { targets: 1, render: (data, type, full) => full.fake_id },
            { targets: 2, render: (data, type, full) => full.parent_title },
            { 
                targets: -1, 
                render: function(data, type, full) {
                    return `
                        <a href="${baseUrl}/admin/menu/${full.id}/edit" class="btn btn-sm btn-icon text-primary"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record text-danger" data-id="${full.id}"><i class="fa fa-trash"></i></button>
                    `;
                }
            }
        ],
    });
    $(document).on('click', '.delete-record', function () {
        const id = $(this).data('id');
        const deleteUrl = baseUrl + "/admin/menu/" + id;

        swal({
            title: "Apakah Anda yakin?",
            text: "Menu ini akan dihapus permanen. Jika ini adalah parent, sub-menu di dalamnya mungkin akan ikut terhapus!",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    value: null,
                    visible: true,
                    className: "btn btn-light",
                    closeModal: true,
                },
                confirm: {
                    text: "Ya, Hapus!",
                    value: true,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: false
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    url: deleteUrl,
                    data: {
                        "_token": "{{ csrf_token() }}", // Token CSRF untuk keamanan
                    },
                    success: function (response) {
                        // Refresh data di tabel tanpa reload halaman
                        dt.draw();
                        
                        swal({
                            title: "Berhasil!",
                            text: "Menu telah berhasil dihapus.",
                            icon: "success",
                            timer: 2000,
                            buttons: false
                        });
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        swal({
                            title: "Error!",
                            text: "Gagal menghapus data. Silakan coba lagi.",
                            icon: "error",
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection