<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalrepository" id="add-repository">+ Tambah</button>
        <div class="modal fade" id="modalrepository" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddrepository">
                        @csrf
                        <input type="hidden" name="id" id="id_repository">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelrepository">Tambah Riwayat Repository</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nama_file" class="form-label">Nama File</label>
                                <input type="text" name="nama_file" id="nama_file_repository" class="form-control" placeholder="cth : STIFAR">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal_repository" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="dokumen" class="form-label">File</label>
                                <input type="file" name="dokumen" id="dokumen_repository_repository" class="form-control">
                                <div id="dokumen_exist_repository">

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
    <div class="col-md-12 mb-4" id="repository-table-loc">
        <table class="display" id="repository-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama File</th>
                    <th>Tanggal</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_repository as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->nama_file}}</td>
                    <td>{{date('d-m-Y',strtotime($row->tanggal))}}</td>
                    <td>
                       @if(!empty($row->dokumen))
                            <a href="{{url('assets/file/repository/' . $row->dokumen)}}" title="dokumen" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif 
                    </td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-repository" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalrepository"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-repository text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#repository-table").DataTable();

            $(document).on('click', '.edit-record-repository', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelrepository').html('Edit Riwayat repository');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/repository/').concat(id, '/edit'), function (data) {
                    const suffix = "_repository";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_repository')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/repository/',data[0][key]);
                                $('#dokumen_exist_repository')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_repository')
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
            $('#modalrepository').on('hidden.bs.modal', function () {
                $('#formAddrepository').trigger("reset");
                $('#dokumen_exist_repository').html('');
            });
        });
    </script>
