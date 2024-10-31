
<div class="modal fade" id="ubahFotoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="javascript:void(0)" id="FotoForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Foto mahasiswa</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" value="{{(!empty($mahasiswa))?$mahasiswa->id:0}}">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="col-sm-10 col-form-label">Ubah Foto :</label>
                                <div class="col-sm-12">
                                    <p><input type="file" class="form-control" placeholder="Ubah Gambar" name="foto" required="" ></p>
                                    <div class="alert alert-warning"><small>* Hanya Ekstensi jpg dan png yang diijinkan | max file 2 MB</small></div>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update-gambar" type="button">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
