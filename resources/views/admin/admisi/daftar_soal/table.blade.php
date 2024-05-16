<table class="display" id="daftar_soal_table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Soal</th>
            <th>status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 0; @endphp
        @foreach($soal as $row)
        <tr>
        <td>{{++$i}}</td>
        <td>{{substr($row->soal,0,100)}}</td>
        <td>{{$row->is_aktif}}</td>
        <td><a href="{{URL::to('admin/admisi/daftar_soal/'.$row->id .'/edit')}}" title="Edit Daftar Soal" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a> <a href="#" title="Delete Nilai Tambahan" class="btn btn-danger btn-xs delete-record" data-id="{{$row['id']}}"><i class="fa fa-trash"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function(){
        $("#daftar_soal_table").DataTable();
        const baseUrl = {!! json_encode(url('/')) !!};
        $(document).on('click', '.delete-record', function () {
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
                url: ''.concat(baseUrl).concat('/admin/admisi/daftar_soal/',id),
                data:{
                    'id': id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function success() {
                    refresh();
                },
                error: function error(_error) {
                    console.log(_error);
                }
                });

                // success sweetalert
                swal({
                icon: 'success',
                title: 'Deleted!',
                text: 'The Record has been deleted!',
                customClass: {
                    confirmButton: 'btn btn-success'
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

    });
</script>
