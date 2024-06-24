<script>
    function refresh_pengabdian() {
        const id = {{$id}};
        $.ajax({
            url:'{{URL::to('admin/kepegawaian/pengabdian')}}',
            method:'GET',
            data:{id : id},
            success:function(data){
                $("#v-pills-pengabdian").html(data);
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
    $(document).on("click","#v-pills-pengabdian-tab",function(){
        refresh_pengabdian();
    });
    $(document).ready(function(){
        const baseUrl = {!! json_encode(url('/')) !!};


        $(document).on('submit', '#formAddpengabdian', function (e){
            const myFormData = new FormData(document.getElementById("formAddpengabdian"));

            e.preventDefault();
            $.ajax({
                data: myFormData,
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/pengabdian'),
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
                    $("#modalpengabdian").modal('hide');
                    refresh_pengabdian();
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
                    $("#modalpengabdian").modal('hide');
                }
            });
        });
        $(document).on('click', '.delete-record-pengabdian', function () {
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
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/pengabdian/').concat(id),
                data:{
                    'id': id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function success() {
                    refresh_pengabdian();
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
