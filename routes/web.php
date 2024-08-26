<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\AsalSekolahController;
use App\Http\Controllers\admin\admisi\GelombangController;
use App\Http\Controllers\admin\admisi\PmbPesertaController;
use App\Http\Controllers\admin\admisi\PeringkatPmdpController;
use App\Http\Controllers\admin\admisi\PmbNilaiTambahanController;
use App\Http\Controllers\admin\admisi\DaftarSoalController;
use App\Http\Controllers\admin\admisi\VerifikasiController;
use App\Http\Controllers\admin\admisi\PengumumanController;
use App\Http\Controllers\admin\admisi\PmbJalurController;
use App\Http\Controllers\admin\admisi\UserGuestController;
use App\Http\Controllers\admin\kepegawaian\PegawaiController;
use App\Http\Controllers\admin\kepegawaian\PegawaiJabatanStrukturalController;
use App\Http\Controllers\admin\kepegawaian\PegawaiJabatanFungsionalController;
use App\Http\Controllers\admin\kepegawaian\PegawaiMengajarController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPenelitianController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPengabdianController;
use App\Http\Controllers\admin\kepegawaian\PegawaiKaryaController;
use App\Http\Controllers\admin\kepegawaian\PegawaiOrganisasiController;
use App\Http\Controllers\admin\kepegawaian\PegawaiRepositoryController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPekerjaanController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPendidikanController;
use App\Http\Controllers\admin\kepegawaian\JamkerjaController;
use App\Http\Controllers\admin\kepegawaian\SuratIzinController;
use App\Http\Controllers\admin\kepegawaian\PegawaiBerkasController;
use App\Http\Controllers\pegawai\UserPegawaiController;
use App\Http\Controllers\pegawai\RiwayatPegawaiController;
use App\Http\Controllers\admin\WaktuController;
use App\Http\Controllers\admin\FakultasController;
use App\Http\Controllers\admin\RumpunController;
use App\Http\Controllers\admin\TahunAjaranController;
use App\Http\Controllers\admin\SesiController;
use App\Http\Controllers\admin\krs\KrsController;
use App\Http\Controllers\admin\ProdiController;
use App\Http\Controllers\admin\KurikulumController;
use App\Http\Controllers\admin\KelompokMatkulController;
use App\Http\Controllers\admin\MatkulController;
use App\Http\Controllers\admin\NilaiController;
use App\Http\Controllers\admin\NilaiLamaController;
use App\Http\Controllers\admin\master\PTController;
use App\Http\Controllers\admin\master\AtributPTController;
use App\Http\Controllers\admin\master\RenstraPTController;
use App\Http\Controllers\admin\master\JabatanStrukturalController;
use App\Http\Controllers\admin\master\UserController;
use App\Http\Controllers\admin\master\AtributProdiController;
use App\Http\Controllers\admin\master\RenstraProdiController;
use App\Http\Controllers\admin\master\ProdiAkreditasiController;
use App\Http\Controllers\admin\JadwalController;
use App\Http\Controllers\admin\MkKurikulum;
use App\Http\Controllers\admin\keuangan\KeuanganController;
use App\Http\Controllers\mahasiswa\MahasiswaController;
use App\Http\Controllers\mahasiswa\ProfileController;
use App\Http\Controllers\mahasiswa\KrsController as mhsKrsController;
use App\Http\Controllers\dosen\DosenController;
use App\Http\Controllers\dosen\KrmController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/actionLogin', [LoginController::class, 'actionLogin'])->name('actionLogin');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::get('/register_mahasiswa', [LoginController::class, 'register_mahasiswa'])->name('register_mahasiswa');
Route::post('/actionRegister', [LoginController::class, 'actionRegister'])->name('actionRegister');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['middleware' => ['auth','role:super-admin']], function(){
    // route dosen
    Route::get('/dosen/perwalian', [DosenController::class, 'index'] )->name('Perwalian');
    Route::get('/dosen/{id}/krs', [DosenController::class, 'detailKRS'] )->name('detailKRS');
    Route::post('/dosen/validasi-krs-satuan', [DosenController::class, 'valiKrsSatuan'] );
    Route::post('/dosen/validasi-krs', [DosenController::class, 'valiKrs'] );
    Route::get('/dosen/krm', [KrmController::class, 'index'] );
    Route::get('/dosen/absensi/{id}/input', [KrmController::class, 'daftarMhs'] );
    Route::get('/dosen/nilai/{id}/input', [KrmController::class, 'daftarMhsNilai'] );
    Route::get('/dosen/{id}/set-pertemuan', [KrmController::class, 'setPertemuan'] );
    Route::get('/dosen/input/{nim}/absensi/{id_jadwal}', [KrmController::class, 'setAbsensiSatuan'] );
    Route::post('/dosen/simpan-absensi-satuan', [KrmController::class, 'saveAbsensiSatuan'] );
    Route::post('/dosen/simpan-kontrak', [KrmController::class, 'saveKontrak'] );

//Route::middleware('auth')->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index'] )->name('dashboard');

    Route::post('admin/admisi/peserta/daftar_kota_admin',[PmbPesertaController::class, 'daftar_kota'] )->name('daftar_kota_admin');
    Route::post('admin/admisi/peserta/get_gelombang',[PmbPesertaController::class, 'get_gelombang'] )->name('get_gelombang');
    Route::post('admin/admisi/peserta/get_jurusan',[PmbPesertaController::class, 'get_jurusan'] )->name('get_jurusan');
    Route::get('admin/admisi/peserta/{id}/edit_gelombang', [PmbPesertaController::class, 'edit_gelombang'])->name('edit_gelombang');
    Route::get('admin/admisi/peserta/{id}/edit_asal_sekolah', [PmbPesertaController::class, 'edit_asal_sekolah'])->name('edit_asal_sekolah');
    Route::get('admin/admisi/peserta/{id}/edit_file_pendukung', [PmbPesertaController::class, 'edit_file_pendukung'])->name('edit_file_pendukung');
    Route::get('admin/admisi/peserta/gelombang/{id}', [PmbPesertaController::class, 'index'])->name('peserta_filter_gelombang');

    Route::get('admin/admisi/peringkat', [PeringkatPmdpController::class, 'index'])->name('peringkat');
    Route::get('admin/admisi/peringkat/table', [PeringkatPmdpController::class, 'table'])->name('table_tambahan');
    Route::post('admin/admisi/peringkat/add_nilai_tambahan', [PeringkatPmdpController::class, 'add_nilai_tambahan'])->name('add_nilai_tambahan');

    Route::get('admin/admisi/nilai_tambahan/{id}', [PmbNilaiTambahanController::class, 'index'])->name('nilai_tambahan');
    Route::get('admin/admisi/nilai_tambahan/{id}/table', [PmbNilaiTambahanController::class, 'table'])->name('table_nilai_tambahan');
    Route::get('admin/admisi/nilai_tambahan/{id}/edit', [PmbNilaiTambahanController::class, 'edit'])->name('edit_nilai_tambahan');
    Route::post('admin/admisi/nilai_tambahan/{id}', [PmbNilaiTambahanController::class, 'store'])->name('store_nilai_tambahan');
    Route::delete('admin/admisi/nilai_tambahan/{id}/delete', [PmbNilaiTambahanController::class, 'destroy'])->name('delete_nilai_tambahan');

    Route::get('admin/admisi/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi');
    Route::get('admin/admisi/verifikasi/{id}/edit', [VerifikasiController::class, 'edit_verifikasi'])->name('edit_verifikasi');
    Route::post('admin/admisi/verifikasi', [VerifikasiController::class, 'update_verifikasi'])->name('update_verifikasi');
    Route::get('admin/admisi/verifikasi/pembayaran', [VerifikasiController::class, 'pembayaran'])->name('verifikasi_pembayaran');
    Route::get('admin/admisi/verifikasi/pembayaran/{id}/edit', [VerifikasiController::class, 'edit_verifikasi'])->name('edit_verifikasi');
    Route::post('admin/admisi/verifikasi/pembayaran', [VerifikasiController::class, 'update_pembayaran'])->name('update_pembayaran');

    Route::get('admin/admisi/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('admin/admisi/pengumuman/{id}/peserta', [PengumumanController::class, 'peserta'])->name('pengumuman_peserta');
    Route::get('admin/admisi/pengumuman/{id}/edit_peserta', [PengumumanController::class, 'edit_peserta'])->name('edit_peserta');
    Route::post('admin/admisi/pengumuman/{id}/edit_peserta', [PengumumanController::class, 'simpan_peserta'])->name('simpan_peserta');

    Route::post('admin/kepegawaian/pegawai/get_status', [PegawaiController::class, 'get_status'])->name('get_status');
    Route::post('admin/kepegawaian/pegawai/user_update', [PegawaiController::class, 'user_update'])->name('user_update');
    Route::post('admin/kepegawaian/pegawai/foto_update', [PegawaiController::class, 'foto_update'])->name('foto_update');

    Route::get('admin/masterdata/pt/atribut', [AtributPTController::class, 'index'])->name('atribut');
    Route::get('admin/masterdata/prodi/atribut/{id}', [AtributProdiController::class, 'index'])->name('atribut_prodi');
    //Route::get('admin/masterdata/pt/atribut/detail/{id}', [AtributPTController::class, 'index'])->name('atribut_detail');
    Route::get('admin/masterdata/pt/renstra', [RenstraPTController::class, 'index'])->name('renstra');
    Route::get('admin/masterdata/prodi/renstra/{id}', [RenstraProdiController::class, 'index'])->name('renstra_prodi');
    Route::get('admin/masterdata/prodi/akreditasi/{id}', [ProdiAkreditasiController::class, 'index'])->name('akreditasi_prodi');
    //Route::get('admin/masterdata/pt/renstra/detail/{id}', [RenstraPTController::class, 'index'])->name('renstra_detail');

    Route::get('admin/nilai_lama/{id}/{id_ta}', [NilaiLamaCOntroller::class, 'index'])->name('nilai_lama');


    Route::resource('admin/masterdata/pt', PTController::class)->name('index','pt');
    Route::resource('admin/masterdata/pt/atribut', AtributPTController::class)->name('index','atribut');
    Route::resource('admin/masterdata/pt/renstra', RenstraPTController::class)->name('index','renstra');
    Route::resource('admin/masterdata/prodi/atribut', AtributProdiController::class)->name('index','atribut_prodi');
    Route::resource('admin/masterdata/prodi/renstra', RenstraProdiController::class)->name('index','renstra_prodi');
    Route::resource('admin/masterdata/prodi/akreditasi', ProdiAkreditasiController::class)->name('index','akreditasi_prodi');
    Route::resource('admin/masterdata/ruang', RuangController::class)->name('index','ruang');
    Route::resource('admin/masterdata/sekolah', AsalSekolahController::class)->name('index','sekolah');

    Route::get('admin/kepegawaian/struktural/get_jabatan', [PegawaiJabatanStrukturalController::class, 'get_jabatan'])->name('get_jabatan');

    Route::get('/mahasiswa/detail/{nim}', [MahasiswaController::class, 'detail']);
    Route::post('mahasiswa/user_update', [MahasiswaController::class, 'user_update'])->name('user_update');
    Route::post('mahasiswa/user_update2', [MahasiswaController::class, 'user_update2'])->name('user_update2');
    Route::post('mahasiswa/foto_update', [MahasiswaController::class, 'foto_update'])->name('foto_update');

    Route::get('mhs/profile', [ProfileController::class, 'index'])->name('index');

    Route::get('mhs/input_krs', [mhsKrsController::class, 'input'])->name('input');

    Route::resource('admin/masterdata/pt', PTController::class)->name('index','pt');
    Route::resource('admin/masterdata/ruang', RuangController::class)->name('index','ruang');
    Route::resource('admin/masterdata/sekolah', AsalSekolahController::class)->name('index','sekolah');
    Route::resource('admin/masterdata/gelombang', GelombangController::class)->name('index','gelombang');
    Route::resource('admin/masterdata/waktu', WaktuController::class)->name('index','waktu');
    Route::resource('admin/masterdata/fakultas', FakultasController::class)->name('index','fakultas');
    Route::resource('admin/masterdata/rumpun', RumpunController::class)->name('index','rumpun');
    Route::resource('admin/masterdata/ta', TahunAjaranController::class)->name('index','ta');
    Route::resource('admin/masterdata/sesi', SesiController::class)->name('index', 'sesi');
    Route::resource('admin/masterdata/kurikulum', KurikulumController::class)->name('index', 'kurikulum');
    Route::resource('admin/masterdata/program-studi', ProdiController::class)->name('index', 'program-studi');
    Route::resource('admin/masterdata/kelompok-mk', KelompokMatkulController::class)->name('index', 'kelompok-mk');
    Route::resource('admin/masterdata/matakuliah', MatkulController::class)->name('index', 'matakuliah');
    Route::resource('admin/masterdata/jabatan_struktural', JabatanStrukturalController::class)->name('index', 'jabatan_struktural');
    Route::resource('admin/masterdata/user', UserController::class)->name('index', 'user');

    // route MahasiswaModel
    Route::resource('/mahasiswa', MahasiswaController::class)->name('index', 'mahasiswa');

    // route Matakuliah
    Route::get('/admin/masterdata/matakuliah', [MatkulController::class, 'index']);
    Route::post('/admin/masterdata/matakuliah/save', [MatkulController::class, 'simpanMK']);
    Route::post('/admin/masterdata/matakuliah/update', [MatkulController::class, 'updateMK']);
    Route::get('/admin/masterdata/matakuliah/delete/{id}', [MatkulController::class, 'destroy']);

    // route jadwal
    Route::post('/jadwal/save-koordinator', [JadwalController::class, 'simpanKoor']);
    Route::post('/jadwal/save-anggota', [JadwalController::class, 'simpanAnggota']);
    Route::post('/jadwal/pengampu', [JadwalController::class, 'jadwalPengampu']);
    Route::post('/jadwal/tambah-pegampu', [JadwalController::class, 'tambahPengampu']);
    Route::post('/jadwal/daftar-jadwal-harian', [JadwalController::class, 'reqJadwalHarian']);
    Route::post('/jadwal/tambah-pertemuan', [JadwalController::class, 'tambahPertemuan']);
    Route::get('/jadwal/hapus-pertemuan/{id}', [JadwalController::class, 'hapusPertemuan']);
    Route::post('/jadwal/daftar-pertemuan', [JadwalController::class, 'daftarPertemuan']);
    Route::post('/admin/masterdata/jadwal/update', [JadwalController::class, 'updateJadwal']);
    Route::get('/jadwal/hapus-pengampu/{id}', [JadwalController::class, 'hapusPengampu']);
    Route::get('/admin/masterdata/jadwal', [JadwalController::class, 'index']);
    Route::get('/admin/masterdata/jadwal-harian', [JadwalController::class, 'daftarJadwalHarian']);
    Route::get('/admin/masterdata/koordinator-mk/{id}', [JadwalController::class, 'koordinatorMK']);
    Route::get('/admin/masterdata/anggota-mk/{id}', [JadwalController::class, 'anggotaMK']);
    Route::get('/admin/masterdata/jadwal/create/{id}', [JadwalController::class, 'daftarJadwal']);
    Route::get('/jadwal/hapus-koor/{id}', [JadwalController::class, 'hapusKoor']);
    Route::get('/jadwal/hapus/{id}', [JadwalController::class, 'hapusJadwal']);
    Route::get('/jadwal/hapus-anggota/{id}', [JadwalController::class, 'hapusAnggota']);
    Route::post('/jadwal/tableAnggota', [JadwalController::class, 'tableAnggota']);
    Route::post('/admin/masterdata/jadwal/create', [JadwalController::class, 'createJadwal']);



    // route KRS
    Route::get('/admin/masterdata/krs', [KrsController::class, 'index']);
    Route::post('/admin/masterdata/krs/list-mhs', [KrsController::class, 'listMhs']);
    Route::post('/admin/masterdata/krs/ganti-status-krs', [KrsController::class, 'gantiStatus']);
    Route::get('/admin/masterdata/krs/admin/input/{id}/{ta}', [KrsController::class, 'inputadminKRS']);
    Route::get('/admin/masterdata/krs/admin/hapus/{id}', [KrsController::class, 'hapusadminKRS']);
    Route::get('/admin/masterdata/krs/input/{id}/{mhs}', [KrsController::class, 'tambahadminKRS']);
    Route::post('/admin/masterdata/krs/list-jadwal', [KrsController::class, 'showJadwal']);

    Route::get('/admin/keuangan/generate_mhs', [KeuanganController::class, 'generate_mhs']);
    Route::get('/admin/keuangan/generate_user_mhs', [KeuanganController::class, 'generate_user_mhs']);


    // route mkKurikulum
    Route::get('/admin/masterdata/matakuliah-kurikulum', [MkKurikulum::class, 'index']);
    Route::post('/admin/masterdata/matakuliah-kurikulum/get', [MkKurikulum::class, 'daftarKur']);
    Route::post('/admin/masterdata/matakuliah-kurikulum/save', [MkKurikulum::class, 'simpandaftarKur']);
    Route::post('/admin/masterdata/matakuliah-kurikulum/update', [MkKurikulum::class, 'updateMK']);
    Route::get('/admin/masterdata/matakuliah-kurikulum/delete/{id}', [MkKurikulum::class, 'destroy']);


    Route::resource('admin/admisi/gelombang', GelombangController::class)->name('index','gelombang');
    Route::resource('admin/admisi/peserta', PmbPesertaController::class)->name('index','peserta');
    Route::resource('admin/admisi/daftar_soal', DaftarSoalController::class)->name('index','daftar_soal');
    Route::resource('admin/admisi/jalur_pendaftaran', PmbJalurController::class)->name('index','jalur_pendaftaran');
    Route::resource('admin/admisi/user_pmb', UserGuestController::class)->name('index','user_pmb');

    Route::resource('admin/kepegawaian/pegawai', PegawaiController::class)->name('index','pegawai');
    Route::resource('admin/kepegawaian/struktural', PegawaiJabatanStrukturalController::class)->name('index','struktural');
    Route::resource('admin/kepegawaian/fungsional', PegawaiJabatanFungsionalController::class)->name('index','fungsional');
    Route::resource('admin/kepegawaian/mengajar', PegawaiMengajarController::class)->name('index','mengajar');
    Route::resource('admin/kepegawaian/penelitian', PegawaiPenelitianController::class)->name('index','penelitian');
    Route::resource('admin/kepegawaian/pengabdian', PegawaiPengabdianController::class)->name('index','pengabdian');
    Route::resource('admin/kepegawaian/karya', PegawaiKaryaController::class)->name('index','karya');
    Route::resource('admin/kepegawaian/organisasi', PegawaiOrganisasiController::class)->name('index','organisasi');
    Route::resource('admin/kepegawaian/repository', PegawaiRepositoryController::class)->name('index','repository');
    Route::resource('admin/kepegawaian/pekerjaan', PegawaiPekerjaanController::class)->name('index','pekerjaan');
    Route::resource('admin/kepegawaian/pendidikan', PegawaiPendidikanController::class)->name('index','pendidikan');
    Route::resource('admin/kepegawaian/berkas', PegawaiBerkasController::class)->name('index','berkas');
    Route::resource('admin/kepegawaian/jamkerja', JamkerjaController::class)->name('index','jamkerja');
    Route::resource('admin/kepegawaian/surat_izin', SuratIzinController::class)->name('index','surat_izin');

    Route::resource('admin/keuangan', KeuanganController::class)->name('index','keuangan');

    Route::resource('pegawai', UserPegawaiController::class)->name('index','pegawai');
    Route::resource('riwayat', RiwayatPegawaiController::class)->name('index','pegawai');

    Route::resource('admin/nilai_lama', NilaiLamaController::class)->name('index','nilai_lama');
});

Route::group(['middleware' => ['auth','role:mhs']], function(){
    Route::get('/mhs/dashboard',[DashboardController::class, 'mhs'] )->name('dashboard_mahasiswa');

    Route::get('/mahasiswa/detail/{nim}', [MahasiswaController::class, 'detail']);
    Route::post('mahasiswa/user_update', [MahasiswaController::class, 'user_update'])->name('user_update');
    Route::post('mahasiswa/user_update2', [MahasiswaController::class, 'user_update2'])->name('user_update2');
    Route::post('mahasiswa/foto_update', [MahasiswaController::class, 'foto_update'])->name('foto_update');
    Route::post('mahasiswa', [MahasiswaController::class, 'store'])->name('input');

    Route::get('mhs/profile', [ProfileController::class, 'index'])->name('index');

    Route::get('mhs/input_krs', [mhsKrsController::class, 'input'])->name('input');
    Route::get('/admin/masterdata/krs/admin/hapus/{id}', [KrsController::class, 'hapusadminKRS']);
    Route::post('/admin/masterdata/krs/list-jadwal', [KrsController::class, 'showJadwal']);

    Route::get('/admin/masterdata/krs/input/{id}/{mhs}', [KrsController::class, 'tambahadminKRS']);

    Route::post('admin/admisi/peserta/daftar_kota',[PmbPesertaController::class, 'daftar_kota'] )->name('daftar_kota');
});
Route::group(['middleware' => ['auth','role:pegawai']], function(){

    Route::get('/dsn/dashboard',[DashboardController::class, 'dosen'] )->name('dashboard_pegawai');

    Route::resource('pegawai', UserPegawaiController::class)->name('index','pegawai');
    Route::resource('riwayat', RiwayatPegawaiController::class)->name('index','pegawai');

    //Route::resource('admin/kepegawaian/pegawai', PegawaiController::class)->name('index','pegawai');
    Route::post('admin/kepegawaian/pegawai', [PegawaiController::class, 'store'])->name('input_pegawai');
    Route::resource('admin/kepegawaian/struktural', PegawaiJabatanStrukturalController::class)->name('index','struktural');
    Route::resource('admin/kepegawaian/fungsional', PegawaiJabatanFungsionalController::class)->name('index','fungsional');
    Route::resource('admin/kepegawaian/mengajar', PegawaiMengajarController::class)->name('index','mengajar');
    Route::resource('admin/kepegawaian/penelitian', PegawaiPenelitianController::class)->name('index','penelitian');
    Route::resource('admin/kepegawaian/pengabdian', PegawaiPengabdianController::class)->name('index','pengabdian');
    Route::resource('admin/kepegawaian/karya', PegawaiKaryaController::class)->name('index','karya');
    Route::resource('admin/kepegawaian/organisasi', PegawaiOrganisasiController::class)->name('index','organisasi');
    Route::resource('admin/kepegawaian/repository', PegawaiRepositoryController::class)->name('index','repository');
    Route::resource('admin/kepegawaian/pekerjaan', PegawaiPekerjaanController::class)->name('index','pekerjaan');
    Route::resource('admin/kepegawaian/pendidikan', PegawaiPendidikanController::class)->name('index','pendidikan');
    Route::resource('admin/kepegawaian/berkas', PegawaiBerkasController::class)->name('index','berkas');
    Route::resource('admin/kepegawaian/jamkerja', JamkerjaController::class)->name('index','jamkerja');
    Route::resource('admin/kepegawaian/surat_izin', SuratIzinController::class)->name('index','surat_izin');
});
