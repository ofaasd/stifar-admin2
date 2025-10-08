<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalkegiatan_luar" id="add-kegiatan_luar">+ Tambah</button>
        <div class="modal fade" id="modalkegiatan_luar" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddkegiatan_luar">
                        @csrf
                        <input type="hidden" name="id" id="id_kegiatan_luar">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelkegiatan_luar">Tambah Riwayat kegiatan luar</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nama_instansi_kegiatan_luar" class="form-label">Nama Instansi</label>
                                <input type="text" name="nama_instansi" id="nama_instansi_kegiatan_luar" class="form-control" placeholder="Nama Instansi Penyelenggara Kegiatan">
                            </div>
                            <div class="mb-3">
                                <label for="sebagai" class="form-label">Sebagai</label>
                                <input type="text" name="sebagai" id="sebagai_kegiatan_luar" class="form-control" placeholder="Cth : Peserta">
                            </div>
                            <div class="mb-3">
                                <label for="durasi" class="form-label">Durasi</label>
                                <input type="text" name="durasi" id="durasi_kegiatan_luar" class="form-control" placeholder="Cth : 5 Jam">
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal_kegiatan_luar" class="form-control" placeholder="">
                            </div>

                            <div class="mb-3">
                                <label for="surat_tugas" class="form-label">Surat Tugas</label>
                                <input type="file" name="surat_tugas" id="surat_tugas_kegiatan_luar" class="form-control" placeholder="">
                                <div id="surat_tugas_exist_kegiatan_luar">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="bukti_kegiatan" class="form-label">Bukti Kegiatan</label>
                                <input type="file" name="bukti_kegiatan" id="bukti_kegiatan_kegiatan_luar" class="form-control" placeholder="">
                                <div id="bukti_kegiatan_exist_kegiatan_luar">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="dokumen_pendukung" class="form-label">Dokumen Pendukung</label>
                                <input type="file" name="dokumen_pendukung" id="dokumen_pendukung_kegiatan_luar" class="form-control" placeholder="">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="dokumen_pendukung_exist_kegiatan_luar">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="link" class="form-label">Link URL</label>
                                <input type="text" name="link" id="link_kegiatan_luar" class="form-control" placeholder="https://xxxx.xxx">
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
    <div class="col-md-12 mb-4" id="kegiatan_luar-table-loc">
        <table class="display" id="kegiatan_luar-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Instansi</th>
                    <th>Sebagai</th>
                    <th>Durasi</th>
                    <th>Tanggal</th>
                    <th>Link</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_kegiatan_luar as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->nama_instansi}}</td>
                    <td>{{$row->sebagai}}</td>
                    <td>{{$row->durasi}}</td>
                    <td>{{$row->tanggal}}</td>
                    <td><a href='{{$row->link}}'>{{$row->link}}</a></td>
                    <td>
                        @if(!empty($row->bukti_kegiatan))
                            <a href="{{url('assets/file/kegiatan_luar/' . $row->bukti_kegiatan)}}" title="Bukti Kegiatan" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif 
                        @if(!empty($row->surat_tugas))
                            <a href="{{url('assets/file/kegiatan_luar/' . $row->surat_tugas)}}" title="surat tugas" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif 
                        @if(!empty($row->dokumen_pendukung))
                            <a href="{{url('assets/file/kegiatan_luar/' . $row->dokumen_pendukung)}}" title="dokumen pendukung" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif 
                    </td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-kegiatan_luar" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalkegiatan_luar"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-kegiatan_luar text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#kegiatan_luar-table").DataTable();

            $(document).on('click', '.edit-record-kegiatan_luar', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelkegiatan_luar').html('Edit Riwayat kegiatan_luar');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/kegiatan_luar/').concat(id, '/edit'), function (data) {
                    const suffix = "_kegiatan_luar";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_kegiatan_luar')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'surat_tugas'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/kegiatan_luar/',data[0][key]);
                                $('#surat_tugas_exist_kegiatan_luar')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#surat_tugas_exist_kegiatan_luar')
                                .html('');
                            }
                        }else if(key == 'bukti_kegiatan'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/kegiatan_luar/',data[0][key]);
                                $('#bukti_kegiatan_exist_kegiatan_luar')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#bukti_kegiatan_exist_kegiatan_luar')
                                .html('');
                            }
                        }else if(key == 'dokumen_pendukung'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/kegiatan_luar/',data[0][key]);
                                $('#dokumen_pendukung_exist_kegiatan_luar')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_pendukung_exist_kegiatan_luar')
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
            $('#modalkegiatan_luar').on('hidden.bs.modal', function () {
                $('#formAddkegiatan_luar').trigger("reset");
            });
        });
    </script>
