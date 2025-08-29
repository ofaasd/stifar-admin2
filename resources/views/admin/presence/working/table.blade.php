<table class="table" id="example2">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($working as $row)
            <tr>
                <td>{{++$no}}</td>
                <td>{{$row->name}}</td>
                <td>{{$row->email}}</td>
                <td>
                    <div class="btn-group">
                        <a href="javascript:void(0)"class="btn btn-primary btn-sm btn-edit" data-bs-id="{{$row->id}}" data-bs-toggle="modal" data-bs-target="#modal-add"><i class="fas fa-pencil"></i></a>
                        <a href="javascript:void(0)"class="btn btn-danger btn-sm delete-record" data-id="{{$row->id}}"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
$('#example2').DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
$(".btn-edit").click(function(){
    $('#formWorking').trigger("reset");
    $("#overlay-place").html(`<div class="overlay">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>`);
    $(".password-field").hide();
    const id = $(this).data('id');
    const url_table = "{!! url('working') !!}/" + id +'/edit';
    $.get(url_table, function (data){
        console.log(data);

        Object.keys(data).forEach(key => {
            //console.log(key);
            if(data[key][0]){
                Object.keys(data[key][0]).forEach(key2 => {

                    if(key2 == "working_start" && data[key]['working_start'] != "00:00"){
                        //alert(data[key]['working_start']);
                        if(data[key]['working_start']){
                         $('#' + key2 + key)
                            .val(data[key]['working_start'])
                            .trigger('change');
                        }
                    }else if(key2 == "working_end" && data[key]['working_start'] != "00:00"){
                        //alert(data[key]['working_end']);
                        if(data[key]['working_end']){
                            $('#' + key2 + key)
                                .val(data[key]['working_end'])
                                .trigger('change');
                        }
                    }else{

                        $('#' + key2 + key)
                            .val(data[key][0][key2])
                            .trigger('change');
                    }
                });
                $("#user_id").val(id).trigger('change');
            }else{
                $("#user_id").val(id).trigger('change');
            }

        });
        $("#overlay-place").html('');
    });
});

$(document).on('click', '.delete-record', function () {
    const id = $(this).data('id');
    // sweetalert for confirmation of delete
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    }).then(function (result) {
    if (result.isConfirmed) {
        // delete the data
        $.ajax({
        type: 'DELETE',
        url: "{!! url('user') !!}/" + id ,
        data:{
            'id': id,
            '_token': '{{ csrf_token() }}',
        },
        success: function success() {
            // success sweetalert
            Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'The Record has been deleted!',
            customClass: {
                confirmButton: 'btn btn-success'
            }
            });
            refresh_table();
        },
        error: function error(_error) {
            console.log(_error);
            Swal.fire({
                title: 'Error',
                text: 'The record is not deleted!',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
            });
        }
        });


    } else {
        Swal.fire({
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
