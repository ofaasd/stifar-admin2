<script>
    $(document.body).on("submit","#userForm",function(e){
        e.preventDefault();
        $(".update-password").prop('disabled', true);
        $(".update-password").html('<div class="loader-2"></div> Please Wait');

        const form = $(this).serialize();
        $.ajax({
            url:'{{URL::to('mahasiswa/user_update2')}}',
            method:'POST',
            data:form,
            dataType: "json",
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
            error: function error(xhr) {
                const data = xhr.responseJSON;
                swal({
                title: data.status.toUpperCase(),
                text: data.message,
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
                });
                $(".update-password").prop('disabled', false);
                $(".update-password").html('Save Changes');
            }
        });
    });
</script>
