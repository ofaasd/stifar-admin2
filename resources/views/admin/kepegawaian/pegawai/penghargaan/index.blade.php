
<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpenghargaan" id="add-penghargaan">+ Tambah</button>
        <div class="modal fade" id="modalpenghargaan" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddpenghargaan">
                        @csrf
                        <input type="hidden" name="id" id="id_penghargaan">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelpenghargaan">Tambah Riwayat penghargaan</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nama_penghargaan_penghargaan" class="form-label">Nama penghargaan</label>
                                <input type="text" name="nama_penghargaan" id="nama_penghargaan_penghargaan" class="form-control" placeholder="cth : Dosen Terbaik">
                            </div>
                            <div class="mb-3">
                                <label for="penyelenggara" class="form-label">Penyelenggara</label>
                                <input type="text" name="penyelenggara" id="penyelenggara_penghargaan" class="form-control" placeholder="cth : DIKTI | Yayasan Farmasi">
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal_penghargaan" class="form-control" placeholder="">
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label">File Pendukung</label>
                                <input type="file" name="file" id="file_penghargaan" class="form-control" placeholder="">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="dokumen_exist_penghargaan">

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
    <div class="col-md-12 mb-4" id="penghargaan-table-loc">
        <table class="display" id="penghargaan-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama penghargaan</th>
                    <th>Penyelenggara</th>
                    <th>Tanggal</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_penghargaan as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->nama_penghargaan}}</td>
                    <td>{{$row->penyelenggara}}</td>
                    <td>{{$row->tanggal}}</td>
                    <td>
                       @if(!empty($row->file))
                            <a href="{{url('assets/file/penghargaan/' . $row->file)}}" title="dokumen" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif 
                    </td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-penghargaan" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpenghargaan"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-penghargaan text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#penghargaan-table").DataTable();

            $(document).on('click', '.edit-record-penghargaan', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelpenghargaan').html('Edit Riwayat penghargaan');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/penghargaan/').concat(id, '/edit'), function (data) {
                    const suffix = "_penghargaan";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_penghargaan')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'file'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/penghargaan/',data[0][key]);
                                $('#dokumen_exist_penghargaan')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_penghargaan')
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
            $('#modalpenghargaan').on('hidden.bs.modal', function () {
                $('#formAddpenghargaan').trigger("reset");
            });
        });
    </script>
