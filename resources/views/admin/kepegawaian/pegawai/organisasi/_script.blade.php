<script>
    function refresh_organisasi() {
        const id = {{$id}};
        $.ajax({
            url:'{{URL::to('admin/kepegawaian/organisasi')}}',
            method:'GET',
            data:{id : id},
            success:function(data){
                $("#v-pills-organisasi").html(data);
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
    $(document).on("click","#v-pills-organisasi-tab",function(){
        refresh_organisasi();
    });
    $(document).ready(function(){
        const baseUrl = {!! json_encode(url('/')) !!};


        $(document).on('submit', '#formAddorganisasi', function (e){
            const myFormData = new FormData(document.getElementById("formAddorganisasi"));

            e.preventDefault();
            $.ajax({
                data: myFormData,
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/organisasi'),
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
                    $("#modalorganisasi").modal('hide');
                    refresh_organisasi();
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
                    $("#modalorganisasi").modal('hide');
                }
            });
        });
        $(document).on('click', '.delete-record-organisasi', function () {
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
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/organisasi/').concat(id),
                data:{
                    'id': id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function success() {
                    refresh_organisasi();
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
