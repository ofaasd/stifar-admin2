<script>
    $(document.body).on("submit","#FotoForm",function(e){
        e.preventDefault();

        const myFormData = new FormData(document.getElementById("FotoForm"));


        $.ajax({
            url:'{{URL::to('admin/kepegawaian/pegawai/foto_update')}}',
            method:'POST',
            data:myFormData,
            processData: false,
            contentType: false,
            success:function(status){
                swal({
                    icon: 'success',
                    title: 'Successfully '.concat(status, '!'),
                    text: ''.concat(status, ' Successfully.'),
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function(){
                    $('#ubahFotoModal').modal('toggle');
                });
            },
            error: function error(err) {
                $('#ubahFotoModal').modal('toggle');
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
