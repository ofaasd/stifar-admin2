<script>
    $(document.body).on("submit","#userForm",function(e){
        e.preventDefault();
        const form = $(this).serialize();
        $.ajax({
            url:'{{URL::to('admin/kepegawaian/pegawai/user_update')}}',
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
