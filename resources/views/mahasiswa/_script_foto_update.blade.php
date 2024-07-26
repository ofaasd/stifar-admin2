<script>
    $(document.body).on("submit","#FotoForm",function(e){
        e.preventDefault();
        $(".update-gambar").prop('disabled', true);
        $(".update-gambar").html('<div class="loader-2"></div> Please Wait');

        const myFormData = new FormData(document.getElementById("FotoForm"));


        $.ajax({
            url:'{{URL::to('mahasiswa/foto_update')}}',
            method:'POST',
            data:myFormData,
            processData: false,
            contentType: false,
            success:function(data){
                status = data.status;
                swal({
                    icon: 'success',
                    title: 'Successfully '.concat(status, '!'),
                    text: ''.concat(status, ' Successfully.'),
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function(){
                    $('#ubahFotoModal').modal('toggle');
                    $(".update-gambar").prop('disabled', false);
                    $(".update-gambar").html('Save Changes');
                    const url = '{{asset('assets/images/mahasiswa/')}}';
                    const photo = data.pegawai.foto_mhs;
                    $(".photo-profile").html(`<img class="img-70 rounded-circle" alt="" src="${url}/${photo}">`);
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
                $(".update-gambar").prop('disabled', false);
                $(".update-gambar").html('Save Changes');
            }
        });
    });
</script>
