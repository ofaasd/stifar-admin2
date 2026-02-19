<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalMengajar" id="add-mengajar">+ Tambah</button>
        <div class="modal fade" id="modalMengajar" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddMengajar">
                        @csrf
                        <input type="hidden" name="id" id="id_mengajar">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelMengajar">Tambah Riwayat Mengajar</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="institusi" class="form-label">Institusi</label>
                                <input type="text" name="institusi" id="institusi_mengajar" class="form-control" placeholder="cth : STIFAR">
                            </div>
                            <div class="mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <input type="text" name="prodi" id="prodi_mengajar" class="form-control" placeholder="cth : Apoteker">
                            </div>
                            <div class="mb-3">
                                <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
                                <input type="text" name="mata_kuliah" id="mata_kuliah_mengajar" class="form-control" placeholder="cth : Matematika Diskrit">
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="kelas_mengajar" class="form-control" placeholder="cth : Pagi / karyawan">
                            </div>
                            <div class="mb-3">
                                <label for="sks" class="form-label">SKS</label>
                                <input type="number" name="sks" id="sks_mengajar" class="form-control" step="any">
                            </div>
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" name="tahun" id="tahun_mengajar" class="form-control" placeholder="ex : 2024">
                            </div>
                            <div class="mb-3">
                                <label for="dokumen" class="form-label">Dokumen</label>
                                <input type="file" name="dokumen" id="dokumen_mengajar_mengajar" class="form-control">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="dokumen_exist_mengajar">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="sk_mengajar" class="form-label">SK Mengajar</label>
                                <input type="file" name="sk_mengajar" id="sk_mengajar_mengajar" class="form-control">
                                <div id="dokumen_exist_sk_mengajar">

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
    <div class="col-md-12 mb-4" id="mengajar-table-loc">
        <table class="display" id="mengajar-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Institusi</th>
                    <th>Program Studi</th>
                    <th>Mata Kuliah</th>
                    <th>Tahun</th>
                    <th>Kelas</th>
                    <th>SKS</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_mengajar as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->institusi}}</td>
                    <td>{{$row->prodi}}</td>
                    <td>{{$row->mata_kuliah}}</td>
                    <td>{{$row->tahun}}</td>
                    <td>{{$row->kelas}}</td>
                    <td>{{$row->sks}}</td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-mengajar" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalMengajar"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-mengajar text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#mengajar-table").DataTable();

            $(document).on('click', '.edit-record-mengajar', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelMengajar').html('Edit Riwayat Mengajar');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/mengajar/').concat(id, '/edit'), function (data) {
                    const suffix = "_mengajar";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'tgl_sk_mengajar'){
                            $('#' + key)
                            .val(data['tanggal_sk'])
                            .trigger('change');
                        }else if(key == 'tmt_sk_mengajar'){
                            $('#' + key)
                            .val(data['tmt_sk'])
                            .trigger('change');
                        }else if(key == 'id'){
                            $('#id_mengajar')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/mengajar/',data[0][key]);
                                $('#dokumen_exist_mengajar')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_mengajar')
                                .html('');
                            }
                        }else if(key == 'sk_mengajar'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/mengajar/',data[0][key]);
                                $('#dokumen_exist_sk_mengajar')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_sk_mengajar')
                                .html('');
                            }
                        }else{
                            $('#' + key + suffix)
                                .val(data[0][key])
                                .trigger('change');
                        }
                    });
                });
            });
            $('#modalMengajar').on('hidden.bs.modal', function () {
                document.getElementById('formAddmengajar').reset();
                $('#id_mengajar').val('');
                $('#dokumen_exist_mengajar').html('');
                $('#dokumen_exist_sk_mengajar').html('');
            });
        });
    </script>
