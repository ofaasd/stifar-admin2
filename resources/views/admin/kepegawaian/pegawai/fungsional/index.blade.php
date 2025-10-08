<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalFungsional" id="add-fungsional">+ Tambah</button>
        <div class="modal fade" id="modalFungsional" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddFungsional">
                        @csrf
                        <input type="hidden" name="id" id="id_fungsional">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelFungsional">Tambah Jabatan Fungsional</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="jabatan_fungsional_sekarang" class="form-label">Jabatan Fungsional</label>
                                <select name="jabatan_fungsional" id="jabatan_fungsional_sekarang" class="form-control select2">
                                    @foreach($list_jabatan_fungsional as $key=>$row)
                                        <option value="{{$key}}">{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="no_sk" class="form-label">No. SK</label>
                                <input type="text" name="no_sk" id="no_sk_fungsional" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_sk" class="form-label">Tanggal SK</label>
                                <input type="date" name="tanggal_sk" id="tgl_sk_fungsional" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="tmt_sk" class="form-label">TMT SK</label>
                                <input type="date" name="tmt_sk" id="tmt_sk_fungsional" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="kum" class="form-label">KUM</label>
                                <input type="text" max="4" name="kum" id="kum_fungsional" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status_fungsional" class="form-control">
                                    <option value="0">Tidak Aktif</option>
                                    <option value="1">Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="dokumen" class="form-label">Dokumen</label>
                                <input type="file" name="dokumen" id="dokumen_fungsional" class="form-control">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="dokumen_exist_fungsional">

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
    <div class="col-md-12 mb-4" id="fungsional-table-loc">
        <table class="display" id="fungsional-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jabatan Fungsional</th>
                    <th>No. SK</th>
                    <th>Tanggal SK </th>
                    <th>TMT SK</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_jabatan_fungsional as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$list_jabatan_fungsional[$row->jabatan_fungsional_sekarang]}}</td>
                    <td>{{$row->no_sk_fungsional}}</td>
                    <td>{{date('d-m-Y',strtotime($row->tgl_sk_fungsional))}}</td>
                    <td>{{date('d-m-Y',strtotime($row->tmt_sk_fungsional))}}</td>
                    <td>{!!($row->status == 1)?"<span class='btn btn-success btn-sm'>Aktif</span>":"<span class='btn btn-danger btn-sm'>Tidak Aktif</span>"!!}</td>
                    <td>
                        @if(!empty($row->dokumen))
                        <a href="/assets/file/fungsional/{{$row->dokumen}}" target="_blank" class="btn btn-info" style="margin-top:20px"><i class="fa fa-file"></i></a>
                        @else
                        <span class="btn btn-secondary">Tidak ada file</span>
                        @endif
                    </td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-fungsional" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalFungsional"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-fungsional text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#fungsional-table").DataTable();

            $(document).on('click', '.edit-record-fungsional', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelFungsional').html('Edit Jabatan fungsional');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/fungsional/').concat(id, '/edit'), function (data) {
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'tgl_sk_fungsional'){
                            $('#' + key)
                            .val(data['tanggal_sk'])
                            .trigger('change');
                        }else if(key == 'tmt_sk_fungsional'){
                            $('#' + key)
                            .val(data['tmt_sk'])
                            .trigger('change');
                        }else if(key == 'id'){
                            $('#id_fungsional')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'kum'){
                            $('#kum_fungsional')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'status'){
                            $('#status_fungsional')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/fungsional/',data[0][key]);
                                $('#dokumen_exist_fungsional')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_fungsional')
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
            $('#modalFungsional').on('hidden.bs.modal', function () {
                $('#formAddFungsional').trigger("reset");
                $('#dokumen_exist_fungsional').html('');
            });
        });
    </script>
