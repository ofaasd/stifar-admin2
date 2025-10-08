<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpengabdian" id="add-pengabdian">+ Tambah</button>
        <div class="modal fade" id="modalpengabdian" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddpengabdian">
                        @csrf
                        <input type="hidden" name="id" id="id_pengabdian">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelpengabdian">Tambah Riwayat Pengabdian</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nama_tempat" class="form-label">Nama Tempat</label>
                                <input type="text" name="nama_tempat" id="nama_tempat_pengabdian" class="form-control" placeholder="cth : STIFAR">
                            </div>
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" name="tahun" id="tahun_pengabdian" class="form-control" placeholder="ex : 2024">
                            </div>
                            <div class="mb-3">
                                <label for="dokumen" class="form-label">Dokumen</label>
                                <input type="file" name="dokumen" id="dokumen_pengabdian_pengabdian" class="form-control">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="dokumen_exist_pengabdian">

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
    <div class="col-md-12 mb-4" id="pengabdian-table-loc">
        <table class="display" id="pengabdian-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Tempat</th>
                    <th>Tahun</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_pengabdian as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->nama_tempat}}</td>
                    <td>{{$row->tahun}}</td>
                    <td>
                       @if(!empty($row->dokumen))
                            <a href="{{url('assets/file/pengabdian/' . $row->dokumen)}}" title="dokumen" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif 
                    </td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-pengabdian" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpengabdian"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-pengabdian text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#pengabdian-table").DataTable();

            $(document).on('click', '.edit-record-pengabdian', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelpengabdian').html('Edit Riwayat pengabdian');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/pengabdian/').concat(id, '/edit'), function (data) {
                    const suffix = "_pengabdian";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_pengabdian')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/pengabdian/',data[0][key]);
                                $('#dokumen_exist_pengabdian')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_pengabdian')
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
            $('#modalpengabdian').on('hidden.bs.modal', function () {
                $('#formAddpengabdian').trigger("reset");
                $('#dokumen_exist_pengabdian').html('');
            });
        });
    </script>
