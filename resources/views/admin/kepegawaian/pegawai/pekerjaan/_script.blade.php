<script>
    function refresh_pekerjaan() {
        const id = {{$id}};
        $.ajax({
            url:'{{URL::to('admin/kepegawaian/pekerjaan')}}',
            method:'GET',
            data:{id : id},
            success:function(data){
                $("#v-pills-pekerjaan").html(data);
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
    $(document).on("click","#v-pills-pekerjaan-tab",function(){
        refresh_pekerjaan();
    });
    $(document).ready(function(){
        const baseUrl = {!! json_encode(url('/')) !!};


        $(document).on('submit', '#formAddpekerjaan', function (e){
            const myFormData = new FormData(document.getElementById("formAddpekerjaan"));

            e.preventDefault();
            $.ajax({
                data: myFormData,
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/pekerjaan'),
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
                    $("#modalpekerjaan").modal('hide');
                    refresh_pekerjaan();
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
                    $("#modalpekerjaan").modal('hide');
                }
            });
        });
        $(document).on('click', '.delete-record-pekerjaan', function () {
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
                url: ''.concat(baseUrl).concat('/admin/kepegawaian/pekerjaan/').concat(id),
                data:{
                    'id': id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function success() {
                    refresh_pekerjaan();
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
