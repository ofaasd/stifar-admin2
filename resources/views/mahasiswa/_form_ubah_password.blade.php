<div class="modal fade" id="ubahPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="javascript:void(0)" id="userForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" value="{{(!empty($user))?$user->id:0}}">
                        <div class="row">
                            @if(!empty($user))
                                <div class="col-md-12 mb-4">
                                    <label class="col-sm-10 col-form-label">Ubah Password :</label>
                                    <div class="col-sm-12">
                                        <p><input type="password" class="form-control" placeholder="Ubah Password" name="password" required="" ></p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">Anda Belum Terdaftar Sebagai User Harap daftarkan dengan mengisi Form Di Bawah ini</div>
                                <input type="hidden" name="nama_lengkap" value="{{$mahasiswa->nama}}">
                                <input type="hidden" name="id_mahasiswa" value="{{$mahasiswa->id}}">
                                <div class="col-md-12 mb-4">
                                    <label class="col-sm-10 col-form-label">Email :</label>
                                    <div class="col-sm-12">
                                        <p><input type="email" class="form-control" placeholder="Email" name="email" required="" ></p>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label class="col-sm-10 col-form-label">Password :</label>
                                    <div class="col-sm-12">
                                        <p><input type="password" class="form-control" placeholder="Harap Menggunakan kombinasi huruf dan angka" name="password" required="" ></p>
                                    </div>
                                </div>
                            @endif
                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update-password" type="button">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
