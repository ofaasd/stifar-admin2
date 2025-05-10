<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalorganisasi" id="add-organisasi">+ Tambah</button>
        <div class="modal fade" id="modalorganisasi" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddorganisasi">
                        @csrf
                        <input type="hidden" name="id" id="id_organisasi">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelorganisasi">Tambah Riwayat organisasi</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nama_organisasi_organisasi" class="form-label">Nama Organisasi</label>
                                <input type="text" name="nama_organisasi" id="nama_organisasi_organisasi" class="form-control" placeholder="cth : Himpunan Mahasiswa TI">
                            </div>
                            <div class="mb-3">
                                <label for="jabatan_organisasi" class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan_organisasi" class="form-control" placeholder="cth : Ketua">
                            </div>
                            <div class="mb-3">
                                <label for="tahun_organisasi" class="form-label">Periode</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="number" name="tahun" id="tahun_organisasi" class="form-control" placeholder="ex : 2024">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" name="tahun_keluar" id="tahun_keluar_organisasi" class="form-control" placeholder="ex : 2024">
                                        <div class="alert alert-warning"><small>Diisi dengan angka 0 jika masih Aktif</small></div>
                                    </div>
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
    <div class="col-md-12 mb-4" id="organisasi-table-loc">
        <table class="display" id="organisasi-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Organisasi</th>
                    <th>Jabatan</th>
                    <th>Tahun</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_organisasi as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->nama_organisasi}}</td>
                    <td>{{$row->jabatan}}</td>
                    <td>{{$row->tahun}} - {{($row->tahun_keluar == 0)?"Sekarang":$row->tahun_keluar}}</td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-organisasi" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalorganisasi"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-organisasi text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#organisasi-table").DataTable();

            $(document).on('click', '.edit-record-organisasi', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelorganisasi').html('Edit Riwayat organisasi');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/organisasi/').concat(id, '/edit'), function (data) {
                    const suffix = "_organisasi";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_organisasi')
                            .val(data[0][key])
                            .trigger('change');
                        }else{
                            $('#' + key + suffix)
                                .val(data[0][key])
                                .trigger('change');
                        }
                    });
                });
            });
            $('#modalorganisasi').on('hidden.bs.modal', function () {
                $('#formAddorganisasi').trigger("reset");
            });
        });
    </script>
