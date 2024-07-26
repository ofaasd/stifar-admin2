<script>
    $(document.body).on("submit","#formMahasiswa",function(){
        $(".update-btn").prop('disabled', true);
        $(".update-btn").html('<div class="loader-2"></div> Please Wait');

        const form = $(this).serialize();
        $.ajax({
            url:'{{URL::to('mahasiswa')}}',
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
                })
                $(".update-btn").prop('disabled', false);
                $(".update-btn").html('Update Profile');
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
                $(".update-btn").prop('disabled', false);
                $(".update-btn").html('Update Profile');
            }
        });
    });
    $('#provinsi').change(function(){
        //alert("asdasd");
        var id=$(this).val();
        const url = "{{URL::to('admin/admisi/peserta/daftar_kota')}}";
        $.ajax({
            url : url,
            method : "POST",
            data : {"_token": "{{ csrf_token() }}",id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '<option value="0">--Pilih Kota</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+ data[i].id_wil +'">'+data[i].nm_wil+'</option>';
                }
                $('#kotakab').html(html);

            }
        });
    });
    $('#provinsi_sekolah').change(function(){
        //alert("asdasd");
        var id=$(this).val();
        const url = "{{URL::to('admin/admisi/peserta/daftar_kota')}}";
        $.ajax({
            url : url,
            method : "POST",
            data : {"_token": "{{ csrf_token() }}",id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '<option value="0">--Pilih Kota</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+ data[i].id_wil +'">'+data[i].nm_wil+'</option>';
                }
                $('#kota_sekolah').html(html);

            }
        });
    });
    $('#kotakab').change(function(){
        //alert("asdasd");
        var id=$(this).val();
        const url = "{{URL::to('admin/admisi/peserta/daftar_kota')}}";
        $.ajax({
            url : url,
            method : "POST",
            data : {"_token": "{{ csrf_token() }}",id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '<option value="0">-- Pilih Kecamatan</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+ data[i].id_wil +'">'+data[i].nm_wil+'</option>';
                }
                $('#kecamatan').html(html);

            }
        });
    });
</script>
