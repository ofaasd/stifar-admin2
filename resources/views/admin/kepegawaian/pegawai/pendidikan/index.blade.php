<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpendidikan" id="add-pendidikan">+ Tambah</button>
        <div class="modal fade" id="modalpendidikan" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddpendidikan">
                        @csrf
                        <input type="hidden" name="id" id="id_pendidikan">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelpendidikan">Tambah Riwayat pendidikan</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="jenjang_pendidikan" class="form-label">Jenjang</label>
                                <select class="form-control" name="jenjang" id="jenjang_pendidikan">
                                    <option value="D-I">D-I</option>
                                    <option value="D-II">D-II</option>
                                    <option value="D-III">D-III</option>
                                    <option value="D-IV">D-IV</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                    <option value="Profesi">Profesi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jenjang_profesi" class="form-label">Jenjang Profesi</label>
                                <input type="text" name="jenjang_profesi" id="jenjang_profesi_pendidikan" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="universitas" class="form-label">Sekolah / Universitas</label>
                                <input type="text" name="universitas" id="universitas_pendidikan" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" name="jurusan" id="jurusan_pendidikan" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="tempat" class="form-label">Alamat Sekolah/Universitas</label>
                                <input type="text" name="tempat" id="tempat_pendidikan" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="no_ijazah" class="form-label">No. Ijazah</label>
                                <input type="text" name="no_ijazah" id="no_ijazah_pendidikan" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_ijazah" class="form-label">Tanggal Ijazah</label>
                                <input type="date" name="tanggal_ijazah" id="tanggal_ijazah_pendidikan" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="tahun_pendidikan" class="form-label">Tahun Lulus</label>
                                <input type="number" name="tahun" id="tahun_pendidikan" class="form-control" placeholder="ex : 2024">
                            </div>
                            <div class="mb-3">
                                <label for="dokumen" class="form-label">File</label>
                                <input type="file" name="dokumen" id="dokumen_pendidikan_pendidikan" class="form-control">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="dokumen_exist_pendidikan">

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
    <div class="col-md-12 mb-4" id="pendidikan-table-loc">
        <table class="display" id="pendidikan-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Jurusan</th>
                    <th>Sekolah</th>
                    <th>No. Ijazah</th>
                    <th>Tanggal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_pendidikan as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->jurusan}}</td>
                    <td>{{$row->universitas}}</td>
                    <td>{{$row->no_ijazah}}</td>
                    <td>{{date('d-m-Y',strtotime($row->tanggal_ijazah))}}</td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-pendidikan" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpendidikan"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-pendidikan text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#pendidikan-table").DataTable();

            $(document).on('click', '.edit-record-pendidikan', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelpendidikan').html('Edit Riwayat pendidikan');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/pendidikan/').concat(id, '/edit'), function (data) {
                    const suffix = "_pendidikan";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_pendidikan')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/pendidikan/',data[0][key]);
                                $('#dokumen_exist_pendidikan')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_pendidikan')
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
            $('#modalpendidikan').on('hidden.bs.modal', function () {
                $('#formAddpendidikan').trigger("reset");
                $('#dokumen_exist_pendidikan').html('');
            });
        });
    </script>
