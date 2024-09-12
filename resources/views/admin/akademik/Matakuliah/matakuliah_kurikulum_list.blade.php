<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<div class="mt-2">
    <a href="#" class="btn btn-primary btn-sm btn-icon edit-record" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahbaru">
                                <i class="fa fa-plus"></i> Tambah Baru
    </a>
    <div class="modal fade" id="tambahbaru" aria-labelledby="tambahbaru" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-toggle-wrapper">
                        <h5 style="text-align: center">Tambah Matakuliah</h5>
                        @csrf
                        <div class="form-group mt-2">
                            <input type="text" name="id_kur" id="id_kur" value="{{ $id_kur }}" required="" readonly="" hidden/>
                        </div>
                        <div class="form-group mt-2">
                            <label for="kelompok">Mata Kuliah :</label>
                            <select name="mk" id="mk" class="form-control js-example-basic-single" required="">
                                <option value="" selected disabled>Pilih Mata Kuliah</option>
                                @foreach($matkul as $matkul)
                                <option value="{{ $matkul['id'] }}">{{ $matkul['kode_matkul'] }} - {{ $matkul['nama_matkul'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2"></div>
                        <button type="button" onclick="simpanData()" id="btnSimpan" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Tambah Matakuliah</button>
                        <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto btn-sm" type="button" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive mt-2" id="table_location">

</div>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script>

    $(function(){
        refresh_table();

    });
    function refresh_table(){
        const baseUrl = {!! json_encode(url('/')) !!};
        $("#table_location").html(`<div class="loader-box">
                            <div class="loader-2"></div>
                        </div>`);
        $.ajax({
            url: baseUrl+'/admin/masterdata/matakuliah-kurikulum/get_table',
            type: 'get',
            data: {
                id_kur: $('#kurikulum').val(),
            },
            success: function(res){
                $("#table_location").html(res);
            }
        })
    }
    function simpanData(){
        $("#btnSimpan").attr("disabled",true);
        const baseUrl = {!! json_encode(url('/')) !!};
        $.ajax({
            url: baseUrl+'/admin/masterdata/matakuliah-kurikulum/save',
            type: 'post',
            dataType: 'json',
            data: {
                id_kur: $('#id_kur').val(),
                id_mk: $('#mk').val(),
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){
                if (res.kode == 200) {
                    swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Matakuliah Berhasil ditambahkan! silahkan refresh halaman ini.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }else{
                    swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Matakuliah gagal ditambahkan atau sudah terdaftar!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }
                $("#btnSimpan").attr("disabled",false);
                $("#tambahbaru").modal('hide');
                refresh_table();
            }
        })
    }
    function updateData(id){
        $("#btnUpdate"+id).attr("disabled",true);
        const baseUrl = {!! json_encode(url('/')) !!};
        $.ajax({
            url: baseUrl+'/admin/masterdata/matakuliah-kurikulum/update',
            type: 'post',
            dataType: 'json',
            data: {
                kode_matkul: $('#kode_matkul_'+id).val(),
                nama_matkul: $('#nama_matkul_'+id).val(),
                nama_inggris: $('#nama_inggris_'+id).val(),
                tp: $('#tp_'+id).val(),
                semester: $('#semester_'+id).val(),
                sks_teori: $('#sks_teori_'+id).val(),
                sks_praktek: $('#sks_praktek_'+id).val(),
                status_mk: $('#status_mk_'+id).val(),
                status: $('#status_'+id).val(),
                id: id,
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){
                if (res.kode == 200) {
                    swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Matakuliah Berhasil diubah! silahkan refresh halaman ini.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }else{
                    swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Matakuliah gagal diubah!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }
                $("#btnUpdate"+id).attr("disabled",false);
                $("#edit_"+id).modal('hide');
                refresh_table();

            }
        })
    }
</script>
