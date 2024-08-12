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
                        <div class="col-md-12">
                            @if($mahasiswa->update_password == 0)
                            <div class="alert alert-danger dark">Segera update password anda demi keamanan akun</div>
                            @endif
                        </div>
                        @csrf
                        <input type="hidden" name="id" value="{{(!empty($user))?$user->id:0}}">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="col-md-12 mb-4">
                                    <label class="col-sm-10 col-form-label">Password Lama :</label>
                                    <div class="col-sm-12">
                                        <p><input type="password" class="form-control" placeholder="Default Password Lama NIM+stifar | cth:123123stifar" name="password_lama" required="" ></p>
                                    </div>
                                    <label class="col-sm-10 col-form-label">Password Baru :</label>
                                    <div class="col-sm-12">
                                        <p><input type="password" class="form-control" placeholder="Harap Menggunakan kombinasi huruf dan angka" name="password_baru" required="" ></p>
                                    </div>
                                    <label class="col-sm-10 col-form-label">Konfirmasi Password Baru :</label>
                                    <div class="col-sm-12">
                                        <p><input type="password" class="form-control" placeholder="Tulis kembali password anda" name="password_baru_confirm" required="" ></p>
                                    </div>
                                </div>
                            </div>
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
