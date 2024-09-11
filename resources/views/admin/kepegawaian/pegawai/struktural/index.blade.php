<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalStruktural" id="add-struktural">+ Tambah</button>
        <div class="modal fade" id="modalStruktural" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddStruktural">
                        @csrf
                        <input type="hidden" name="id" id="id_struktural">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelStruktural">Tambah Jabatan Struktural</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="no_gel" class="form-label">Unit Kerja</label>
                                <select name="unit_kerja" id="unit_kerja_struktural" class="form-control">
                                    <option value="0">--Pilih Unit Kerja--</option>
                                    @foreach($list_unit as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jabatan_struktural" class="form-label">Jabatan Struktural</label>
                                <select name="jabatan_struktural" id="id_jabatan_struktural" class="form-control">

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="no_sk" class="form-label">No. SK</label>
                                <input type="text" name="no_sk" id="no_sk_struktural" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_sk" class="form-label">Tanggal SK</label>
                                <input type="date" name="tanggal_sk" id="tanggal_sk_struktural" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="tmt_sk" class="form-label">TMT SK</label>
                                <input type="date" name="tmt_sk" id="tmt_sk_struktural" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status_struktural" class="form-control">
                                    <option value="0">Tidak Aktif</option>
                                    <option value="1">Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="dokumen" class="form-label">Dokumen</label>
                                <input type="file" name="dokumen" id="dokumen_struktural" class="form-control">
                                <div id="dokumen_exist">

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-4" id="struktural-table-loc">
        <table class="display" id="struktural-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Unit Kerja</th>
                    <th>Jabatan Struktural</th>
                    <th>No. SK</th>
                    <th>Tanggal SK </th>
                    <th>TMT SK</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_jabatan_stuktural as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$list_unit[$row->unit_kerja]}}</td>
                    <td>{{$list_jabatan_struktural[$row->id_jabatan_struktural]}}</td>
                    <td>{{$row->no_sk_struktural}}</td>
                    <td>{{date('d-m-Y',strtotime($row->tanggal_sk_struktural))}}</td>
                    <td>{{date('d-m-Y',strtotime($row->tmt_sk_struktural))}}</td>
                    <td>{!!($row->status == 1)?"<span class='btn btn-success btn-sm'>Aktif</span>":"<span class='btn btn-danger btn-sm'>Tidak Aktif</span>"!!}</td>
                    <td>
                        <a href="#" title="Edit" id="add_nilai" class="edit-record-struktural" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalStruktural"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-struktural text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    <script>
        //const baseUrl = {!! json_encode(url('/')) !!};
        $(document).ready(function(){
            const baseUrl = {!! json_encode(url('/')) !!};
            $("#struktural-table").DataTable();
            $("#unit_kerja_struktural").change(function(){

                $.ajax({
                    url:'{{URL::to('admin/kepegawaian/struktural/get_jabatan')}}',
                    method:'POST',
                    dataType: "json",
                    data:{id : $("#unit_kerja_struktural").val(),'_token': '{{ csrf_token() }}',},
                    success:function(data){
                        //console.log(data);
                        $("#id_jabatan_struktural").html('');
                        Object.keys(data).forEach(function (item) {
                            $("#id_jabatan_struktural").append(`<option value='${item}'>${data[item]}</option>`);
                        });

                    },
                    error: function error(err) {

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
            $(document).on('click', '.edit-record-struktural', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelStruktural').html('Edit Jabatan Struktural');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/struktural/').concat(id, '/edit'), function (data) {
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'tanggal_sk_struktural'){
                            $('#' + key)
                            .val(data['tanggal_sk'])
                            .trigger('change');
                        }else if(key == 'tmt_sk_struktural'){
                            $('#' + key)
                            .val(data['tmt_sk'])
                            .trigger('change');
                        }else if(key == 'id'){
                            $('#id_struktural')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'unit_kerja'){
                            $('#unit_kerja_struktural')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'status'){
                            $('#status_struktural')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){
                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/fungsional/',data[0][key]);
                                $('#dokumen_exist')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist')
                                .html('');
                            }
                        }else{
                            $('#' + key)
                                .val(data[0][key])
                                .trigger('change');
                        }
                    });
                });
            });
            $('#modalStruktural').on('hidden.bs.modal', function () {
                $('#formAddStruktural').trigger("reset");
                $('#dokumen_exist').html('');
            });
        });
    </script>
