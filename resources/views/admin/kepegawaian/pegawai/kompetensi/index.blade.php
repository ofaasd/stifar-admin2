<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalkompetensi" id="add-kompetensi">+ Tambah</button>
        <div class="modal fade" id="modalkompetensi" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddkompetensi">
                        @csrf
                        <input type="hidden" name="id" id="id_kompetensi">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelkompetensi">Tambah Riwayat kompetensi</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="bidang_kompetensi" class="form-label">Bidang</label>
                                <input type="text" name="bidang" id="bidang_kompetensi" class="form-control" placeholder="Bidang Kompetensi">
                            </div>
                            <div class="mb-3">
                                <label for="lembaga" class="form-label">Lembaga</label>
                                <input type="text" name="lembaga" id="lembaga_kompetensi" class="form-control" placeholder="Lembaga Pemberi Komptensi">
                            </div>
                            
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal_kompetensi" class="form-control" placeholder="">
                            </div>
                            
                            <div class="mb-3">
                                <label for="bukti" class="form-label">Bukti Pendukung</label>
                                <input type="file" name="bukti" id="bukti_kompetensi" class="form-control" placeholder="">
                                <div id="dokumen_exist_kompetensi">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="link" class="form-label">Link URL</label>
                                <input type="text" name="link" id="link_kompetensi" class="form-control" placeholder="https://xxxx.xxx">
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
    <div class="col-md-12 mb-4" id="kompetensi-table-loc">
        <table class="display" id="kompetensi-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Bidang</th>
                    <th>Lembaga</th>
                    <th>Tanggal</th>
                    <th>Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_kompetensi as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->bidang}}</td>
                    <td>{{$row->lembaga}}</td>
                    <td>{{$row->tanggal}}</td>
                    <td><a href='{{$row->link}}'>{{$row->link}}</a></td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-kompetensi" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalkompetensi"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-kompetensi text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#kompetensi-table").DataTable();

            $(document).on('click', '.edit-record-kompetensi', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelkompetensi').html('Edit Riwayat kompetensi');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/kompetensi/').concat(id, '/edit'), function (data) {
                    const suffix = "_kompetensi";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_kompetensi')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'bukti'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/kompetensi/',data[0][key]);
                                $('#dokumen_exist_kompetensi')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_kompetensi')
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
            $('#modalkompetensi').on('hidden.bs.modal', function () {
                $('#formAddkompetensi').trigger("reset");
            });
        });
    </script>
