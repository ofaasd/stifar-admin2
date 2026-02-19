<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalkarya" id="add-karya">+ Tambah</button>
        <div class="modal fade" id="modalkarya" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddkarya">
                        @csrf
                        <input type="hidden" name="id" id="id_karya">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelkarya">Tambah Riwayat Publikasi</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" name="judul" id="judul_karya" class="form-control" placeholder="Judul Publikasi">
                            </div>
                            <div class="mb-3">
                                <label for="nama_majalah" class="form-label">Nama Jurnal</label>
                                <input type="text" name="nama_majalah" id="nama_majalah_karya" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="volume" class="form-label">Volume</label>
                                <input type="text" name="volume" id="volume_karya" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="nomor" class="form-label">Nomor</label>
                                <input type="text" name="nomor" id="nomor_karya" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select name="bulan" id="bulan_karya" class="form-control">
                                    @foreach($bulan as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" name="tahun" id="tahun_karya" class="form-control" placeholder="ex : 2024">
                            </div>
                            <div class="mb-3">
                                <label for="link_url" class="form-label">Link Publikasi</label>
                                <input type="text" name="link_url" id="link_url_karya" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="dokumen" class="form-label">Dokumen</label>
                                <input type="file" name="dokumen" id="dokumen_karya_karya" class="form-control">
                                <div class="alert alert-warning">Max File upload 10 MB</div>
                                <div id="dokumen_exist_karya">

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
    <div class="col-md-12 mb-4" id="karya-table-loc">
        <table class="display" id="karya-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul</th>
                    <th>Nama Jurnal</th>
                    <th>Volume</th>
                    <th>Nomor</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Link</th>
                    <th>Dokumen</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_karya as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->judul}}</td>
                    <td>{{$row->nama_majalah}}</td>
                    <td>{{$row->volume}}</td>
                    <td>{{$row->nomor}}</td>
                    <td>{{$row->bulan}}</td>
                    <td>{{$row->tahun}}</td>
                    <td><a href="{{$row->link_url}}">{{$row->link_url}}</a></td>
                    <td>
                       @if(!empty($row->dokumen))
                            <a href="{{url('assets/file/karya/' . $row->dokumen)}}" title="dokumen" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif 
                    </td>
                    <td> 
                        <a href="#" title="Edit" class="edit-record-karya" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalkarya"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-karya text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#karya-table").DataTable();

            $(document).on('click', '.edit-record-karya', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelkarya').html('Edit Publikasi');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/karya/').concat(id, '/edit'), function (data) {
                    const suffix = '_karya';
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'id'){
                            $('#id_karya')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/karya/',data[0][key]);
                                $('#dokumen_exist_karya')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_karya')
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
            $('#modalkarya').on('hidden.bs.modal', function () {
                
                document.getElementById('formAddkarya').reset();
                $('#id_karya').val('');
                $('#dokumen_exist_karya').html('');
            });
        });
    </script>
