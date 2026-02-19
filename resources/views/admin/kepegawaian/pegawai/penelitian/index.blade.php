<div class="row">
    <div class="col-md-12 mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpenelitian" id="add-penelitian">+ Tambah</button>
        <div class="modal fade" id="modalpenelitian" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAddpenelitian">
                        @csrf
                        <input type="hidden" name="id" id="id_penelitian">
                        <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelpenelitian">Tambah Riwayat Penelitian</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor" class="form-label">Nomor SK Penelitian (optional)</label>
                                        <input type="text" name="nomor" id="nomor_penelitian" class="form-control" placeholder="cth : 102938">
                                    </div>
                                    <div class="mb-3">
                                        <label for="judul" class="form-label">Judul Penelitian</label>
                                        <input type="text" name="judul" id="judul_penelitian" class="form-control" placeholder="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fakultas" class="form-label">Fakultas</label>
                                        <input type="text" name="fakultas" id="fakultas_penelitian" class="form-control" placeholder="cth : Ilmu Komputer">
                                    </div>
                                    <div class="mb-3">
                                        <label for="jenis_penelitian" class="form-label">Jenis Penelitian</label>
                                        <input type="text" name="jenis_penelitian" id="jenis_penelitian_penelitian" class="form-control" placeholder="cth : Penelitian Kuantitatif">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <input type="number" name="tahun" id="tahun_penelitian" class="form-control" placeholder="ex : 2024">
                                    </div>

                                    <div class="mb-3">
                                        <label for="sumber_dana" class="form-label">Sumber Dana</label>
                                        <input type="text" name="sumber_dana" id="sumber_dana_penelitian" class="form-control" placeholder="cth : Dikti">
                                    </div>
                                    <div class="mb-3">
                                        <label for="dokumen_penelitian" class="form-label">Dokumen Publikasi</label>
                                        <input type="file" name="dokumen" id="dokumen_penelitian_penelitian" class="form-control">
                                        <div class="alert alert-warning">Max File upload 10 MB</div>
                                        <div id="dokumen_exist_penelitian">

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="proposal_penelitian" class="form-label">Proposal</label>
                                        <input type="file" name="proposal" id="proposal_penelitian_penelitian" class="form-control">
                                        <div class="alert alert-warning">Max File upload 10 MB</div>
                                        <div id="proposal_exist_penelitian">

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lap_kemajuan_penelitian" class="form-label">Laporan Kemajuan</label>
                                        <input type="file" name="lap_kemajuan" id="lap_kemajuan_penelitian_penelitian" class="form-control">
                                        <div class="alert alert-warning">Max File upload 10 MB</div>
                                        <div id="lap_kemajuan_exist_penelitian">

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lap_keuangan_penelitian" class="form-label">Laporan keuangan</label>
                                        <input type="file" name="lap_keuangan" id="lap_keuangan_penelitian_penelitian" class="form-control">
                                        <div class="alert alert-warning">Max File upload 10 MB</div>
                                        <div id="lap_keuangan_exist_penelitian">

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lap_akhir_penelitian" class="form-label">Laporan Akhir</label>
                                        <input type="file" name="lap_akhir" id="lap_akhir_penelitian_penelitian" class="form-control">
                                        <div class="alert alert-warning">Max File upload 10 MB</div>
                                        <div id="lap_akhir_exist_penelitian">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="dana" class="form-label">Dana (Rp.)</label>
                                        <input type="number" name="dana" id="dana_penelitian" class="form-control" placeholder="Cth : Rp. 10.000.000">
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_surat" class="form-label">No. Surat Perjanjian</label>
                                        <input type="text" name="no_surat" id="no_surat_penelitian" class="form-control" placeholder="Cth : 1123123">
                                    </div>
                                    <div class="mb-3">
                                        <label for="penyelenggara" class="form-label">Penyelenggara</label>
                                        <input type="text" name="penyelenggara" id="penyelenggara_penelitian" class="form-control" placeholder="Cth : Dikti">
                                    </div>
                                    <div class="mb-3">
                                        <label for="Ketua" class="form-label">Ketua</label>
                                        <input type="text" name="ketua" id="ketua_penelitian" class="form-control" placeholder="Cth : Nama Dosen">
                                    </div>
                                    <div class="mb-3">
                                        <label for="anggota" class="form-label">Anggota</label>
                                        <textarea type="text" name="anggota" id="anggota_penelitian" class="form-control" placeholder="Cth : Nama Dosen" rows=10></textarea>
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
    <div class="col-md-12 mb-4" id="penelitian-table-loc">
        <table class="display" id="penelitian-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nomor - Judul</th>
                    <th>jenis</th>
                    <th>Tahun</th>
                    <th>Sumber Dana</th>
                    <th>Jumlah</th>
                    <th>Penyelenggara</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai_penelitian as $row)
                <tr>
                    <td>{{++$fake_id}}</td>
                    <td>{{$row->nomor}} - {{$row->judul}}</td>
                    <td>{{$row->jenis_penelitian}}</td>
                    <td>{{$row->tahun}}</td>
                    <td>{{$row->sumber_dana}}</td>
                    <td>Rp. {{number_format($row->dana,0,",",".")}}</td>
                    <td>{{$row->penyelenggara}}</td>
                    <td>
                        @if(!empty($row->dokumen))
                            <a href="{{url('assets/file/penelitian/' . $row->dokumen)}}" title="dokumen" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif
                        @if(!empty($row->proposal))
                            <a href="{{url('assets/file/penelitian/' . $row->proposal)}}" title="proposal" target="_blank" class="text-primary"><i class="fa fa-file"></i></a>
                        @endif
                        
                    </td>   
                    <td>
                        <a href="#" title="Edit" class="edit-record-penelitian" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalpenelitian"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-sm btn-icon delete-record-penelitian text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
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
            $("#penelitian-table").DataTable();

            $(document).on('click', '.edit-record-penelitian', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelpenelitian').html('Edit Riwayat penelitian');

                // get data
                $.get(''.concat(baseUrl).concat('/admin/kepegawaian/penelitian/').concat(id, '/edit'), function (data) {
                    const suffix = "_penelitian";
                    Object.keys(data[0]).forEach(key => {
                        //console.log(key);
                        if(key == 'tgl_sk_penelitian'){
                            $('#' + key)
                            .val(data['tanggal_sk'])
                            .trigger('change');
                        }else if(key == 'tmt_sk_penelitian'){
                            $('#' + key)
                            .val(data['tmt_sk'])
                            .trigger('change');
                        }else if(key == 'id'){
                            $('#id_penelitian')
                            .val(data[0][key])
                            .trigger('change');
                        }else if(key == 'dokumen'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/penelitian/',data[0][key]);
                                $('#dokumen_exist_penelitian')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#dokumen_exist_penelitian')
                                .html('');
                            }
                        }else if(key == 'proposal'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/penelitian/',data[0][key]);
                                $('#proposal_exist_penelitian')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#proposal_exist_penelitian')
                                .html('');
                            }
                        }else if(key == 'lap_kemajuan'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/penelitian/',data[0][key]);
                                $('#lap_kemajuan_exist_penelitian')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#lap_kemajuan_exist_penelitian')
                                .html('');
                            }
                        }else if(key == 'lap_keuangan'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/penelitian/',data[0][key]);
                                $('#lap_keuangan_exist_penelitian')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#lap_keuangan_exist_penelitian')
                                .html('');
                            }
                        }else if(key == 'lap_akhir'){

                            if(data[0][key]){
                                const url = baseUrl.concat('/assets/file/penelitian/',data[0][key]);
                                $('#lap_akhir_exist_penelitian')
                                .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                            }else{
                                $('#lap_akhir_exist_penelitian')
                                .html('');
                            }
                        }else{
                            $('#' + key +suffix)
                                .val(data[0][key])
                                .trigger('change');
                        }
                    });
                });
            });
            $('#modalpenelitian').on('hidden.bs.modal', function () {
               document.getElementById('formAddpenelitian').reset();
                $('#id_penelitian').val('');
                $('#dokumen_exist_penelitian').html('');
                $('#proposal_exist_penelitian').html('');
                $('#lap_kemajuan_exist_penelitian').html('');
                $('#lap_keuangan_exist_penelitian').html('');
                $('#lap_akhir_exist_penelitian').html('');
            });
        });
    </script>
