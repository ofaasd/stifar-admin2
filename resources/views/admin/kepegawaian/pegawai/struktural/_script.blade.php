<script>
    function refresh_struktural() {
        const id = {{$id}};
        $.ajax({
            url:'{{URL::to('admin/kepegawaian/struktural')}}',
            method:'GET',
            data:{id : id},
            success:function(data){
                $("#v-pills-struktural").html(data);
            },
            error: function error(err) {
                offCanvasForm.offcanvas('hide');
                swal({
                title: 'Duplicate Entry!',
                text: 'Data Not Saved !',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
                });
            }
        });
    }
    $(document).on("click","#v-pills-struktural-tab",function(){
        refresh_struktural();
        });
    $(document).ready(function(){
        refresh_struktural();

        const baseUrl = {!! json_encode(url('/')) !!};


        $(document).on('submit', '#formAddStruktural', function (e){
            const myFormData = new FormData(document.getElementById("formAddStruktural"));

            e.preventDefault();
            $.ajax({
                data: myFormData,
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/struktural'),
                type: 'POST',
                processData: false,
                contentType: false,
                success: function success(status) {



                    // sweetalert
                    swal({
                    icon: 'success',
                    title: 'Successfully '.concat(status, '!'),
                    text: ''.concat(status, 'Created Successfully.'),
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                    });
                    $("#modalStruktural").modal('hide');
                    refresh_struktural();
                },
                error: function error(err) {

                    swal({
                    title: 'Error Saving Data',
                    text: 'Data Not Saved !',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                    });
                    $("#modalStruktural").modal('hide');
                }
            });
        });
        $(document).on('click', '.delete-record-struktural', function () {
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
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/struktural/').concat(id),
                data:{
                    'id': id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function success() {
                    refresh_struktural();
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
