<div class="row">
    <div class="col-md-12 mb-4">
        @if($jumlah == 0)
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalberkas" id="add-berkas">+ Tambah</button>
        @endif
        <div class="modal fade" id="modalberkas" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddberkas">
                        @csrf
                        <input type="hidden" name="id" id="id_berkas">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelberkas">Tambah Riwayat Berkas</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="ktp" class="form-label">KTP</label>
                                <input type="file" name="ktp" id="ktp_berkas" class="form-control">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="ktp_exist">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="kk" class="form-label">KK</label>
                                <input type="file" name="kk" id="kk_berkas" class="form-control">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="kk_exist">

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
    <div class="col-md-12 mb-4" id="berkas-table-loc">
        <table class="display" id="berkas-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>KTP</th>
                    <th>KK</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($berkas as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td><a href="{{URL::to('assets/file/berkas/dosen/ktp/') . "/" . $row->ktp}}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a></td>
                    <td><a href="{{URL::to('assets/file/berkas/dosen/kk/') . "/" . $row->kk}}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a></td>
                    <td>
                        <a href="#" title="Edit" class="edit-record-berkas" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalberkas"><i class="fa fa-pencil"></i></a>
                        {{-- <button class="btn btn-sm btn-icon delete-record-berkas text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button> --}}
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
            $("#berkas-table").DataTable();

            $(document).on('click', '.edit-record-berkas', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelberkas').html('Edit Riwayat Berkas');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/berkas/').concat(id, '/edit'), function (data) {
                    const suffix = "_berkas";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_berkas')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'ktp'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/berkas/',data[0][key]);
                                $('#ktp_exist')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#ktp_exist')
                                .html('');
                            }
                        }else if(key == 'kk'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/berkas/',data[0][key]);
                                $('#kk_exist')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#kk_exist')
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
            $('#modalberkas').on('hidden.bs.modal', function () {
                document.getElementById('formAddberkas').reset();
                $('#id_berkas').val('');
                $('#ktp_exist').html('');
                $('#kk_exist').html('');
            });
        });
    </script>
