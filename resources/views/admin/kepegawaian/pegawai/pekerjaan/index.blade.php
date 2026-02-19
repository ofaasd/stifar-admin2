<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpekerjaan" id="add-pekerjaan">+ Tambah</button>
        <div class="modal fade" id="modalpekerjaan" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddpekerjaan">
                        @csrf
                        <input type="hidden" name="id" id="id_pekerjaan">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelpekerjaan">Tambah Riwayat pekerjaan</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="posisi_pekerjaan" class="form-label">Jabatan</label>
                                <input type="text" name="posisi" id="posisi_pekerjaan" class="form-control" placeholder="cth : Ketua">
                            </div>
                            <div class="mb-3">
                                <label for="perusahaan_pekerjaan" class="form-label">Perusahaan</label>
                                <input type="text" name="perusahaan" id="perusahaan_pekerjaan" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="tahun_masuk_pekerjaan" class="form-label">Tahun Masuk</label>
                                <input type="number" name="tahun_masuk" id="tahun_masuk_pekerjaan" class="form-control" placeholder="ex : 2024">
                            </div>
                            <div class="mb-3">
                                <label for="tahun_keluar_pekerjaan" class="form-label">Tahun Keluar</label>
                                <input type="number" name="tahun_keluar" id="tahun_keluar_pekerjaan" class="form-control" placeholder="ex : 2024">
                                <div class="alert alert-warning"><small>Diisi dengan angka 0 jika masih Aktif</small></div>
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
    <div class="col-md-12 mb-4" id="pekerjaan-table-loc">
        <table class="display" id="pekerjaan-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Jabatan</th>
                    <th>Perusahaan</th>
                    <th>Tahun</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_pekerjaan as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->posisi}}</td>
                    <td>{{$row->perusahaan}}</td>
                    <td>{{$row->tahun_masuk}} - {{($row->tahun_keluar == 0)?"Sekarang":$row->tahun_keluar}}</td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-pekerjaan" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpekerjaan"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-pekerjaan text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#pekerjaan-table").DataTable();

            $(document).on('click', '.edit-record-pekerjaan', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelpekerjaan').html('Edit Riwayat pekerjaan');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/pekerjaan/').concat(id, '/edit'), function (data) {
                    const suffix = "_pekerjaan";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_pekerjaan')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/pekerjaan/',data[0][key]);
                                $('#dokumen_exist_pekerjaan')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_pekerjaan')
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
            $('#modalpekerjaan').on('hidden.bs.modal', function () {
                document.getElementById('formAddpekerjaan').reset();
                $('#id_pekerjaan').val('');
                $('#dokumen_exist_pekerjaan').html('');
            });
        });
    </script>
