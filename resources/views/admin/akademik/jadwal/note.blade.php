<div class="col-md-12 d-flex flex-column align-items-center justify-content-center">
    <div class="col-11">
        <div class="card p-3 mb-2 bg-white">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-row align-items-center">
                    <div class="c-details text-center text-dark">
                        <h6 class="mb-2 bg-danger rounded p-1">MOHON LAKUKAN PENGECEKAN <span class="bg-warning">DISTRIBUSI SKS</span> PADA SAAT PLOTING DOSEN</h6>
                        <p class="mb-0 lh-1 px-4">Untuk memantau pemetaan total sks pengampu & sebaran matakuliah untuk pelaporan PDDIKTI & pantauan dosen penghitung rasio - dosen program studi</p>
                        <p class="mb-0 lh-1 p-2"><span class="text-danger">*</span>Semua perubahan di kontrol jadwal ini, Berpengaruh pada input KRS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-9   ">
        <div class="card py-2 mb-2 bg-white">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <p class="font-weight-bold text-dark mb-0">Jumlah Mahasiswa</p>
                <div class="d-flex flex-wrap justify-content-center">
                    @foreach ($angkatan as $tahun => $total)
                        <p class="badge badge-info text-white m-1">
                            Thn {{ $tahun }} : {{ $total }}
                        </p>
                    @endforeach
                    <p class="badge badge-info text-white m-1">
                        Total : {{ $totalMahasiswa }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> 