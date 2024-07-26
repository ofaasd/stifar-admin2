<script>
    $(document.body).on("submit","#userForm",function(e){
        e.preventDefault();
        $(".update-password").prop('disabled', true);
        $(".update-password").html('<div class="loader-2"></div> Please Wait');

        const form = $(this).serialize();
        $.ajax({
            url:'{{URL::to('mahasiswa/user_update')}}',
            method:'POST',
            data:form,
            success:function(status){
                swal({
                    icon: 'success',
                    title: 'Successfully '.concat(status, '!'),
                    text: ''.concat(status, ' Successfully.'),
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function(){
                    $('#ubahPasswordModal').modal('toggle');
                    $(".update-password").prop('disabled', false);
                    $(".update-password").html('Save Changes');
                    location.reload();
                });
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
    });
</script>
