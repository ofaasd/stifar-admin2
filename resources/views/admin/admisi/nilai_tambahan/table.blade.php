
<table class="display" id="peringkat-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Keterangan</th>
            <th>Nilai Tambahan</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 0; @endphp
        @foreach($nilai as $row)
        <tr>
        <td>{{++$i}}</td>
        <td>{{$row->keterangan}}</td>
        <td>{{$row->nilai}}</td>
        <td><a href="#" title="Edit nilai tambahan" class="btn btn-primary btn-xs add_nilai" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"><i class="fa fa-pencil"></i></a> <a href="#" title="Delete Nilai Tambahan" class="btn btn-danger btn-xs delete-record" data-id="{{$row['id']}}"><i class="fa fa-trash"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
    <script>
        //const baseUrl = {!! json_encode(url('/')) !!};
        $(document).ready(function(){
            $("#peringkat-table").DataTable();
            $(".add_nilai").click(function(){
                //alert($(this).data('id'));
                const id = $(this).data('id');
                if(id > 0){
                    $.ajax({
                        url: ''.concat(baseUrl).concat('/admin/admisi/nilai_tambahan/').concat(id,'/edit'),
                        type: 'GET',
                        success: function success(data) {
                            $("#id").val(id);
                            $("#keterangan").val(data.keterangan);
                            $("#nilai").val(data.nilai);
                        }
                    });
                }else{
                    $("#id").val(id);
                    $("#keterangan").val('');
                    $("#nilai").val('');
                }

            });
            $("#formAdd").submit(function(e){
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: ''.concat(baseUrl).concat('/admin/admisi/nilai_tambahan/{{$id}}'),
                    type: 'POST',
                    success: function success(status) {

                        $("#tambahModal").modal('hide');

                        // sweetalert
                        swal({
                        icon: 'success',
                        title: 'Successfully '.concat(status, '!'),
                        text: ''.concat(status, 'Created Successfully.'),
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                        refresh();
                    },
                    error: function error(err) {
                        $("#tambahModal").modal('hide');
                        swal({
                        title: 'Duplicate Entry!',
                        text: title + ' Not Saved !',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                    }
                });
            });
        });
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
                    url: ''.concat(baseUrl).concat('/admin/admisi/nilai_tambahan/',id).concat('/delete'),
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
    </script>
