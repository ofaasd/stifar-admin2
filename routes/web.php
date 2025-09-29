<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\MkKurikulum;
use App\Http\Controllers\dosen\KrmController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\SesiController;
use App\Http\Controllers\admin\NilaiController;
use App\Http\Controllers\admin\ProdiController;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\admin\WaktuController;
use App\Http\Controllers\dosen\DosenController;
use App\Http\Controllers\admin\AlumniController;
use App\Http\Controllers\admin\JadwalController;
use App\Http\Controllers\admin\MatkulController;
use App\Http\Controllers\admin\RumpunController;
use App\Http\Controllers\admin\krs\KrsController;
use App\Http\Controllers\mahasiswa\KhsController;
use App\Http\Controllers\admin\FakultasController;
use App\Http\Controllers\dosen\PresenceController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\KurikulumController;
use App\Http\Controllers\admin\master\PTController;
use App\Http\Controllers\admin\NilaiLamaController;
use App\Http\Controllers\mahasiswa\UjianController;
use App\Http\Controllers\admin\AsalSekolahController;
use App\Http\Controllers\admin\keuangan\VaController;
use App\Http\Controllers\admin\master\UserController;
use App\Http\Controllers\admin\TahunAjaranController;
use App\Http\Controllers\admin\WorkingHourController;
use App\Http\Controllers\dosen\DosenBerkasController;
use App\Http\Controllers\mahasiswa\ProfileController;
use App\Http\Controllers\admin\admisi\SlideController;
use App\Http\Controllers\admin\master\LantaiController;
use App\Http\Controllers\admin\master\VendorController;
use App\Http\Controllers\mahasiswa\MahasiswaController;
use App\Http\Controllers\pegawai\UserPegawaiController;
use App\Http\Controllers\admin\aset\AsetTanahController;
use App\Http\Controllers\admin\KelompokMatkulController;
use App\Http\Controllers\dosen\skripsi\SidangController;
use App\Http\Controllers\mahasiswa\TagihanMhsController;
use App\Http\Controllers\admin\admisi\PmbJalurController;
use App\Http\Controllers\admin\aset\AsetBarangController;
use App\Http\Controllers\admin\aset\CetakLabelController;
use App\Http\Controllers\dosen\skripsi\SkripsiController;
use App\Http\Controllers\mahasiswa\DaftarNilaiController;
use App\Http\Controllers\admin\admisi\GelombangController;
use App\Http\Controllers\admin\admisi\UserGuestController;
use App\Http\Controllers\admin\akademik\AbsensiController;
use App\Http\Controllers\admin\keuangan\TagihanController;
use App\Http\Controllers\admin\master\AtributPTController;
use App\Http\Controllers\admin\master\RenstraPTController;
use App\Http\Controllers\mahasiswa\KuesionerMhsController;
use App\Http\Controllers\pegawai\RiwayatPegawaiController;
use App\Http\Controllers\admin\admisi\DaftarSoalController;
use App\Http\Controllers\admin\admisi\PengumumanController;
use App\Http\Controllers\admin\admisi\PmbPesertaController;
use App\Http\Controllers\admin\admisi\VerifikasiController;
use App\Http\Controllers\admin\keuangan\KeuanganController;
use App\Http\Controllers\admin\master\JenisRuangController;
use App\Http\Controllers\admin\admisi\GenerateNimController;
use App\Http\Controllers\admin\akademik\KuesionerController;
use App\Http\Controllers\admin\akademik\PerwalianController;
use App\Http\Controllers\admin\aset\AsetKendaraanController;
use App\Http\Controllers\admin\berkas\BerkasDosenController;
use App\Http\Controllers\admin\keuangan\BukaTutupController;
use App\Http\Controllers\admin\mahasiswa\PrestasiController;
use App\Http\Controllers\admin\master\JenisBarangController;
use App\Http\Controllers\dosen\skripsi\PembimbingController;
use App\Http\Controllers\mahasiswa\skripsi\BerkasController;
use App\Http\Controllers\admin\kepegawaian\PegawaiController;
use App\Http\Controllers\admin\master\AsetKategoriController;
use App\Http\Controllers\admin\master\AtributProdiController;
use App\Http\Controllers\admin\master\RenstraProdiController;
use App\Http\Controllers\ArsipAdminDaftarWisudawanController;
use App\Http\Controllers\mahasiswa\LaporPembayaranController;
use App\Http\Controllers\mahasiswa\MahasiswaBerkasController;
use App\Http\Controllers\admin\admisi\PeringkatPmdpController;
use App\Http\Controllers\admin\kepegawaian\JamkerjaController;
use App\Http\Controllers\admin\master\MerkKendaraanController;
use App\Http\Controllers\admin\akademik\NilaiSusulanController;
use App\Http\Controllers\admin\kepegawaian\SuratIzinController;
use App\Http\Controllers\admin\master\JenisKendaaranController;
use App\Http\Controllers\mahasiswa\skripsi\BimbinganController;
use App\Http\Controllers\mahasiswa\skripsi\PengajuanController;
use App\Http\Controllers\admin\akademik\SoalKuesionerController;
use App\Http\Controllers\admin\berkas\BerkasMahasiswaController;
use App\Http\Controllers\admin\keuangan\JenisKeuanganController;
use App\Http\Controllers\admin\master\ProdiAkreditasiController;
use App\Http\Controllers\admin\master\StrukturPegawaiController;
use App\Http\Controllers\admin\admisi\BiayaPendaftaranController;
use App\Http\Controllers\admin\admisi\PmbNilaiTambahanController;
use App\Http\Controllers\admin\akademik\NilaiKuesionerController;
use App\Http\Controllers\admin\aset\AsetGedungBangunanController;
use App\Http\Controllers\admin\keuangan\ProdiBukaTutupController;
use App\Http\Controllers\admin\skripsi\DosenPembimbingController;
use App\Http\Controllers\mahasiswa\skripsi\NilaiSidangController;
use App\Http\Controllers\mahasiswa\skripsi\PembimbingController1;
use App\Http\Controllers\admin\akademik\KhsController as adminKhs;
use App\Http\Controllers\admin\akademik\PengaturanUjianController;
use App\Http\Controllers\admin\kepegawaian\PegawaiKaryaController;
use App\Http\Controllers\admin\keuangan\SettingKeuanganController;
use App\Http\Controllers\admin\master\JabatanStrukturalController;
use App\Http\Controllers\admin\skripsi\ManajemenSkripsiController;
use App\Http\Controllers\admin\kepegawaian\PegawaiBerkasController;
use App\Http\Controllers\mahasiswa\akademik\DaftarWisudaController;
use App\Http\Controllers\mahasiswa\skripsi\BerkasSkripsiController;
use App\Http\Controllers\mahasiswa\skripsi\DaftarSkripsiController;
use App\Http\Controllers\dosen\skripsi\BimbinganMahasiswaController;
use App\Http\Controllers\dosen\skripsi\PengajuanBimbinganController;
use App\Http\Controllers\admin\admisi\VerifikasiPembayaranController;
use App\Http\Controllers\admin\kepegawaian\PegawaiMengajarController;
use App\Http\Controllers\mahasiswa\KrsController as mhsKrsController;
use App\Http\Controllers\mahasiswa\skripsi\PengajuanSidangController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPekerjaanController;
use App\Http\Controllers\admin\keuangan\AdminLaporPembayaranContoller;
use App\Http\Controllers\mahasiswa\skripsi\BimbinganSkripsiController;
use App\Http\Controllers\mahasiswa\skripsi\PengajuanSkripsiController;
use App\Http\Controllers\admin\akademik\wisuda\SettingWisudaController;
use App\Http\Controllers\admin\akademik\yudisium\SettingPisnController;
use App\Http\Controllers\admin\kepegawaian\PegawaiKompetensiController;
use App\Http\Controllers\admin\kepegawaian\PegawaiOrganisasiController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPendidikanController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPenelitianController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPengabdianController;
use App\Http\Controllers\admin\kepegawaian\PegawaiRepositoryController;
use App\Http\Controllers\dosen\akademik\skripsi\DosenPengujiController;
use App\Http\Controllers\admin\akademik\wisuda\CetakWisudawanController;
use App\Http\Controllers\admin\kepegawaian\PegawaiPenghargaanController;
use App\Http\Controllers\admin\akademik\NilaiController as nilaiakademik;
use App\Http\Controllers\admin\akademik\yudisium\CetakYudisiumController;
use App\Http\Controllers\admin\kepegawaian\PegawaiKegiatanLuarController;
use App\Http\Controllers\dosen\akademik\skripsi\DosenBimbinganController;
use App\Http\Controllers\mahasiswa\skripsi\PengajuanPembimbingController;
use App\Http\Controllers\admin\akademik\yudisium\ProsesYudisiumController;
use App\Http\Controllers\admin\akademik\yudisium\SettingYudisiumController;
use App\Http\Controllers\mahasiswa\AbsensiController as mhsAbsensiController;
use App\Http\Controllers\admin\akademik\transkripIjazah\PrintIjazahController;
use App\Http\Controllers\admin\akademik\wisuda\AdminDaftarWisudawanController;
use App\Http\Controllers\admin\akademik\yudisium\PengesahanYudisiumController;
use App\Http\Controllers\admin\kepegawaian\PegawaiJabatanFungsionalController;
use App\Http\Controllers\admin\kepegawaian\PegawaiJabatanStrukturalController;
use App\Http\Controllers\admin\akademik\yudisium\ArsipProsesYudisiumController;
use App\Http\Controllers\mahasiswa\skripsi\SidangController as SidangMahasiswa;
use App\Http\Controllers\admin\akademik\skripsi\AdminPengajuanSkripsiController;
use App\Http\Controllers\admin\akademik\transkripIjazah\PrintTranskripController;
use App\Http\Controllers\admin\akademik\wisuda\AdminDaftarPendaftarWisudaController;
use App\Http\Controllers\admin\admisi\StatistikController as AdmisiStatistikController;

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
Route::get('/login_mhs', [LoginController::class, 'login_mhs'])->name('login_mhs');
Route::get('/login_dsn', [LoginController::class, 'login_dsn'])->name('login_dsn');
Route::post('/actionLogin', [LoginController::class, 'actionLogin'])->name('actionLogin');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::get('/register_mahasiswa', [LoginController::class, 'register_mahasiswa'])->name('register_mahasiswa');
Route::post('/actionRegister', [LoginController::class, 'actionRegister'])->name('actionRegister');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['middleware' => ['auth', 'role:super-admin|admin-prodi',]], function () {
    // route dosen
    Route::get('/dosen/perwalian', [DosenController::class, 'index'])->name('Perwalian');
    Route::get('/dosen/{id}/krs', [DosenController::class, 'detailKRS'])->name('detailKRS');
    Route::post('/dosen/validasi-krs-satuan', [DosenController::class, 'valiKrsSatuan']);
    Route::post('/dosen/validasi-krs', [DosenController::class, 'valiKrs']);
    Route::get('/dosen/krm', [KrmController::class, 'index']);
    Route::get('/dosen/absensi/{id}/input', [KrmController::class, 'daftarMhs']);
    Route::get('/dosen/{id}/set-pertemuan', [KrmController::class, 'setPertemuan']);
    Route::get('/dosen/input/{nim}/absensi/{id_jadwal}', [KrmController::class, 'setAbsensiSatuan']);
    Route::post('/dosen/simpan-absensi-satuan', [KrmController::class, 'saveAbsensiSatuan']);
    Route::post('/dosen/simpan-kontrak', [KrmController::class, 'saveKontrak']);
    Route::get('/dosen/perwalian', [DosenController::class, 'index'] )->name('Perwalian');
    Route::get('/dosen/perwalian/{id}', [PerwalianController::class, 'show'] )->name('Perwalian_detail');
    Route::get('/dosen/{id}/krs', [DosenController::class, 'detailKRS'] )->name('detailKRS');
    Route::post('/dosen/validasi-krs-satuan', [DosenController::class, 'valiKrsSatuan'] );
    Route::post('/dosen/validasi-krs', [DosenController::class, 'valiKrs'] );
    Route::get('/dosen/krm', [KrmController::class, 'index'] );
    Route::get('/dosen/absensi/{id}/input', [KrmController::class, 'daftarMhs'] );
    // Route::get('dosen/nilai/{id}/input', [KrmController::class, 'daftarMhsNilai'] );
    Route::get('/dosen/{id}/set-pertemuan', [KrmController::class, 'setPertemuan'] );
    Route::get('/dosen/input/{nim}/absensi/{id_jadwal}', [KrmController::class, 'setAbsensiSatuan'] );
    Route::post('/dosen/simpan-absensi-satuan', [KrmController::class, 'saveAbsensiSatuan'] );
    Route::post('/dosen/simpan-kontrak', [KrmController::class, 'saveKontrak'] );


    //Route::middleware('auth')->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index'] )->name('dashboard');
    Route::get('/dashboard_akademik',[DashboardController::class, 'akademik'] )->name('dashboard_akademik');

    Route::post('admin/admisi/peserta/daftar_kota',[PmbPesertaController::class, 'daftar_kota'] )->name('daftar_kota');
    Route::post('admin/admisi/peserta/get_gelombang',[PmbPesertaController::class, 'get_gelombang'] )->name('get_gelombang');
    Route::post('admin/admisi/peserta/get_gelombang_ta',[PmbPesertaController::class, 'get_gelombang_ta'] )->name('get_gelombang_ta');
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
    Route::get('admin/admisi/verifikasi/{id}/edit_pembayaran', [VerifikasiController::class, 'edit_pembayaran'])->name('edit_pembayaran');
    Route::post('admin/admisi/verifikasi', [VerifikasiController::class, 'update_verifikasi'])->name('update_verifikasi');
    Route::get('admin/admisi/verifikasi/pembayaran', [VerifikasiController::class, 'pembayaran'])->name('verifikasi_pembayaran');
    Route::get('admin/admisi/verifikasi/pembayaran/{id}/edit', [VerifikasiController::class, 'edit_verifikasi'])->name('edit_verifikasi');
    Route::post('admin/admisi/verifikasi/pembayaran', [VerifikasiController::class, 'update_pembayaran'])->name('update_pembayaran');
    Route::get('admin/admisi/verifikasi/gelombang/{id}', [VerifikasiController::class, 'index'])->name('verifikasi_filter_gelombang');
    Route::get('admin/admisi/verifikasi/pembayaran/gelombang/{id}', [VerifikasiController::class, 'pembayaran'])->name('pembayaran_filter_gelombang');
    Route::get('admin/admisi/verifikasi/{id}/show', [VerifikasiController::class, 'show'])->name('verifikasi_show');
    Route::get('admin/admisi/statistik', [AdmisiStatistikController::class, 'index'])->name('admisi_statistik');

    Route::get('admin/admisi/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('admin/admisi/pengumuman/{id}/peserta', [PengumumanController::class, 'peserta'])->name('pengumuman_peserta');
    Route::get('admin/admisi/pengumuman/{id}/edit_peserta', [PengumumanController::class, 'edit_peserta'])->name('edit_peserta');
    Route::post('admin/admisi/pengumuman/{id}/edit_peserta', [PengumumanController::class, 'simpan_peserta'])->name('simpan_peserta');
    Route::get('admin/admisi/generate_nim/preview', [GenerateNimController::class, 'preview'])->name('preview_nim2');
    Route::get('admin/admisi/generate_nim/regenerate', [GenerateNimController::class, 'regenerate'])->name('regenerate_nim');
    Route::get('admin/admisi/generate_nim/generate', [GenerateNimController::class, 'generate'])->name('generate_nims');
    Route::resource('admin/admisi/verifikasi_pembayaran', VerifikasiPembayaranController::class);
    Route::resource('admin/admisi/generate_nim', GenerateNimController::class);
    Route::get('admin/admisi/generate_nim/{id}/{status}', [GenerateNimController::class, 'show'])->name('show_generate_nim');

    Route::post('admin/admisi/generate_nim/generate_preview', [GenerateNimController::class, 'generate_preview'])->name('preview_nim');
    Route::post('admin/admisi/generate_nim/save_temp', [GenerateNimController::class, 'save_temp'])->name('save_temp');
    Route::delete('admin/admisi/generate_nim/delete_temp/{id}', [GenerateNimController::class, 'delete_temp'])->name('delete_temp');
    //Route::get('admin/admisi/verifikasi_pembayaran', [VerifikasiPembayaranController::class, 'index'])->name('verifikasi');


    Route::get('admin/pegawai/generate_user', [PegawaiController::class, 'generate_user'])->name('generate_user');

    Route::get('admin/masterdata/pt/atribut', [AtributPTController::class, 'index'])->name('atribut');
    Route::get('admin/masterdata/prodi/atribut/{id}', [AtributProdiController::class, 'index'])->name('atribut_prodi');
    //Route::get('admin/masterdata/pt/atribut/detail/{id}', [AtributPTController::class, 'index'])->name('atribut_detail');
    Route::get('admin/masterdata/pt/renstra', [RenstraPTController::class, 'index'])->name('renstra');
    Route::get('admin/masterdata/prodi/renstra/{id}', [RenstraProdiController::class, 'index'])->name('renstra_prodi');
    Route::get('admin/masterdata/prodi/akreditasi/{id}', [ProdiAkreditasiController::class, 'index'])->name('akreditasi_prodi');
    //Route::get('admin/masterdata/pt/renstra/detail/{id}', [RenstraPTController::class, 'index'])->name('renstra_detail');

    Route::get('admin/nilai_lama/{id}/{id_ta}', [NilaiLamaCOntroller::class, 'index'])->name('nilai_lama');

    Route::post('admin/keuangan/bank_data_va/import', [VaController::class, 'import'])->name('import');


    Route::resource('admin/masterdata/pt', PTController::class)->name('index', 'pt');
    Route::resource('admin/masterdata/pt/atribut', AtributPTController::class)->name('index', 'atribut');
    Route::resource('admin/masterdata/pt/renstra', RenstraPTController::class)->name('index', 'renstra');
    Route::resource('admin/masterdata/prodi/atribut', AtributProdiController::class)->name('index', 'atribut_prodi');
    Route::resource('admin/masterdata/prodi/renstra', RenstraProdiController::class)->name('index', 'renstra_prodi');
    Route::resource('admin/masterdata/prodi/akreditasi', ProdiAkreditasiController::class)->name('index', 'akreditasi_prodi');
    Route::resource('admin/masterdata/ruang', RuangController::class)->name('index', 'ruang');
    Route::resource('admin/masterdata/sekolah', AsalSekolahController::class)->name('index', 'sekolah');
    // Route::resource('admin/masterdata/struktur-pegawai', StrukturPegawaiController::class)->name('index', 'struktur-pegawai');



    // Route::get('/mahasiswa/detail/{nim}', [MahasiswaController::class, 'detail']);
    // Route::post('mahasiswa/user_update', [MahasiswaController::class, 'user_update'])->name('user_update');
    // Route::post('mahasiswa/user_update2', [MahasiswaController::class, 'user_update2'])->name('user_update2');
    // Route::post('mahasiswa/foto_update', [MahasiswaController::class, 'foto_update'])->name('foto_update');
    Route::get('mahasiswa/get_mhs', [MahasiswaController::class, 'get_mhs'])->name('get_mhs');

    Route::get('mhs/profile', [ProfileController::class, 'index'])->name('index');
    Route::get('mhs/berkas', [MahasiswaBerkasController::class, 'index']);
    Route::get('mhs/input_krs', [mhsKrsController::class, 'input'])->name('input');

    Route::resource('admin/masterdata/pt', PTController::class)->name('index','pt');
    // Route::resource('admin/masterdata/ruang', RuangController::class)->name('index','ruang');
    Route::resource('admin/masterdata/sekolah', AsalSekolahController::class)->name('index','sekolah');
    Route::resource('admin/masterdata/gelombang', GelombangController::class)->name('index','gelombang');
    Route::resource('admin/masterdata/waktu', WaktuController::class)->name('index','waktu');
    Route::resource('admin/masterdata/fakultas', FakultasController::class)->name('index','fakultas');
    Route::resource('admin/masterdata/rumpun', RumpunController::class)->name('index','rumpun');
    Route::resource('admin/masterdata/ta', TahunAjaranController::class)->name('index','ta');
    Route::resource('admin/masterdata/pt', PTController::class)->name('index', 'pt');
    Route::resource('admin/masterdata/ruang', RuangController::class)->name('index', 'ruang');
    Route::resource('admin/masterdata/sekolah', AsalSekolahController::class)->name('index', 'sekolah');
    Route::resource('admin/masterdata/gelombang', GelombangController::class)->name('index', 'gelombang');
    Route::resource('admin/masterdata/waktu', WaktuController::class)->name('index', 'waktu');
    Route::resource('admin/masterdata/fakultas', FakultasController::class)->name('index', 'fakultas');
    Route::resource('admin/masterdata/rumpun', RumpunController::class)->name('index', 'rumpun');
    Route::resource('admin/masterdata/ta', TahunAjaranController::class)->name('index', 'ta');
    Route::resource('admin/masterdata/sesi', SesiController::class)->name('index', 'sesi');
    Route::resource('admin/masterdata/kurikulum', KurikulumController::class)->name('index', 'kurikulum');
    Route::resource('admin/masterdata/program-studi', ProdiController::class)->name('index', 'program-studi');

    Route::resource('admin/masterdata/kelompok-mk', KelompokMatkulController::class)->name('index', 'kelompok-mk');
    Route::resource('admin/masterdata/matakuliah', MatkulController::class)->name('index', 'matakuliah');
    Route::resource('admin/masterdata/jabatan_struktural', JabatanStrukturalController::class)->name('index', 'jabatan_struktural');
    Route::resource('admin/masterdata/user', UserController::class)->name('index', 'user');

    // route Master Aset
    Route::resource('admin/masterdata/aset/kategori-aset', AsetKategoriController::class)->name('index', 'kategori-aset');
    // Route::resource('admin/masterdata/aset/label', LabelController::class)->name('index', 'label');
    Route::resource('admin/masterdata/aset/lantai', LantaiController::class)->name('index', 'lantai');
    Route::resource('admin/masterdata/aset/jenis-ruang', JenisRuangController::class)->name('index', 'jenis-ruang');
    Route::resource('admin/masterdata/aset/jenis-barang', JenisBarangController::class)->name('index', 'jenis-barang');
    // Route::resource('admin/masterdata/aset/gedung', GedungController::class)->name('index', 'gedung');
    Route::resource('admin/masterdata/aset/jenis-kendaraan', JenisKendaaranController::class)->name('index', 'jenis-kendaraan');
    Route::resource('admin/masterdata/aset/merk-kendaraan', MerkKendaraanController::class)->name('index', 'merk-kendaraan');
    Route::resource('admin/masterdata/aset/vendor', VendorController::class)->name('index', 'vendor');

    // route MahasiswaModel
    Route::resource('/mahasiswa/prestasi', PrestasiController::class)->name('index', 'mahasiswa');
    Route::resource('/mahasiswa', MahasiswaController::class)->name('index', 'mahasiswa');

    // route Berkas Mahasiswa untuk admin
    Route::resource('/admin/berkas/mahasiswa', BerkasMahasiswaController::class)->name('index', 'berkas-mahasiswa');

    // route Berkas Dosen
    Route::resource('/admin/berkas/dosen', BerkasDosenController::class)->name('index', 'berkas-dosen');

    // route Matakuliah
    Route::get('/admin/masterdata/matakuliah', [MatkulController::class, 'index']);
    Route::post('/admin/masterdata/matakuliah/save', [MatkulController::class, 'simpanMK']);
    Route::post('/admin/masterdata/matakuliah/update', [MatkulController::class, 'updateMK']);
    Route::get('admin/masterdata/matakuliah/delete/{id}', [MatkulController::class, 'destroy']);

    // route jadwal
    Route::post('/jadwal/save-koordinator', [JadwalController::class, 'simpanKoor']);
    Route::post('/jadwal/save-anggota', [JadwalController::class, 'simpanAnggota']);
    Route::post('/jadwal/update-anggota', [JadwalController::class, 'updateAnggota']);
    Route::post('/jadwal/pengampu', [JadwalController::class, 'jadwalPengampu']);
    Route::post('/jadwal/tambah-pegampu', [JadwalController::class, 'tambahPengampu']);
    Route::post('/jadwal/daftar-jadwal-harian', [JadwalController::class, 'reqJadwalHarian']);
    Route::post('/jadwal/tambah-pertemuan', [JadwalController::class, 'tambahPertemuan']);


    Route::get('/jadwal/hapus-pertemuan/{id}', [JadwalController::class, 'hapusPertemuan']);
    Route::post('/jadwal/daftar-pertemuan', [JadwalController::class, 'daftarPertemuan']);
    Route::post('/admin/masterdata/jadwal/update', [JadwalController::class, 'updateJadwal']);
    Route::get('/jadwal/hapus-pengampu/{id}', [JadwalController::class, 'hapusPengampu']);
    Route::get('/admin/masterdata/jadwal', [JadwalController::class, 'index']);
    Route::get('/admin/masterdata/jadwal/prodi/{id}', [JadwalController::class, 'jadwal_prodi']);
    Route::get('/admin/masterdata/jadwal-harian', [JadwalController::class, 'daftarJadwalHarian']);
    Route::get('/admin/masterdata/jadwal-harian/prodi/{id}', [JadwalController::class, 'daftarJadwalHarianProdi']);
    Route::get('/admin/masterdata/distribusi-sks', [JadwalController::class, 'daftarDistribusiSks']);
    Route::get('/admin/masterdata/koordinator-mk/{id}', [JadwalController::class, 'koordinatorMK']);
    Route::get('/admin/masterdata/anggota-mk/{id}', [JadwalController::class, 'anggotaMK']);
    Route::get('/admin/masterdata/jadwal/create/{id}', [JadwalController::class, 'daftarJadwal']);
    Route::get('/jadwal/hapus-koor/{id}', [JadwalController::class, 'hapusKoor']);
    Route::get('/jadwal/hapus/{id}', [JadwalController::class, 'hapusJadwal']);
    Route::get('/jadwal/hapus-anggota/{id}', [JadwalController::class, 'hapusAnggota']);
    Route::post('/jadwal/tableAnggota', [JadwalController::class, 'tableAnggota']);
    Route::post('/admin/masterdata/jadwal/create', [JadwalController::class, 'createJadwal']);

    Route::get('/admin/akademik/setting-pertemuan', [JadwalController::class, 'settingPertemuan']);
    Route::get('/admin/akademik/setting-pertemuan/prodi/{id}', [JadwalController::class, 'settingPertemuan']);

    Route::get('/admin/akademik/list-absensi', [AbsensiController::class, 'index']);
    Route::get('/admin/akademik/list-absensi/prodi/{id}', [AbsensiController::class, 'index']);

    Route::get('/admin/akademik/nilai', [nilaiakademik::class, 'index']);
    Route::get('/admin/akademik/nilai/prodi/{id}', [nilaiakademik::class, 'index']);

    Route::get('/admin/akademik/khs', [adminKhs::class, 'index']);
    Route::get('/admin/akademik/get_tbl_khs', [adminKhs::class, 'get_table_khs']);

    Route::get('/admin/akademik/pengaturan-ujian', [PengaturanUjianController::class, 'index']);
    Route::get('/admin/akademik/pengaturan-ujian/prodi/{id}', [PengaturanUjianController::class, 'index']);
    Route::post('/admin/akademik/pengaturan-ujian/setjadwal', [PengaturanUjianController::class, 'setJadwalUjian']);

    Route::get('/admin/akademik/kuesioner', [KuesionerController::class, 'index']);
    Route::get('/admin/akademik/list_soal/{id}', [SoalKuesionerController::class, 'index']);
    Route::get('/admin/akademik/list_jawaban/{id}', [NilaiKuesionerController::class, 'index']);
    Route::post('/admin/akademik/list_jawaban/{id}', [NilaiKuesionerController::class, 'index']);
    Route::post('/admin/akademik/list-soal/simpan_status', [SoalKuesionerController::class, 'simpan_status']);

    Route::get('/admin/akademik/nilai_susulan', [NilaiSusulanController::class, 'index']);

    // Admin akademik
    Route::prefix('/admin/akademik')->group(function () {

        Route::prefix('/skripsi')->group(function () {
            Route::resource('/pengajuan', AdminPengajuanSkripsiController::class)->name('index','pengajuan-skripsi');
            Route::get('/get-data', [AdminPengajuanSkripsiController::class, 'getData'])->name('get-data-skripsi');
            Route::get('/show/{idMasterSkripsi?}', [AdminPengajuanSkripsiController::class, 'show'])->name('show-skripsi');
            Route::post('/store', [AdminPengajuanSkripsiController::class, 'store'])->name('store-pengajuan-skripsi');
        });

        // Transkrip ijazah
        Route::prefix('/transkrip-ijazah')->group(function () {
            // Print ijazah
            Route::resource('/print-ijazah', PrintIjazahController::class)->name('index','print-ijazah');

            // Print ijazah
            Route::resource('/print-transkrip', PrintTranskripController::class)->name('index','print-transkrip');
        });

        // Yudisium
        Route::prefix('/yudisium')->group(function () {
            Route::resource('/setting', SettingYudisiumController::class)->name('index','setting-yudisium');
            Route::resource('/proses', ProsesYudisiumController::class)->name('index','proses-yudisium');
            Route::post('/proses/upload-foto-yudisium', [ProsesYudisiumController::class, 'storeFotoYudisium']);
            Route::resource('/proses-arsip', ArsipProsesYudisiumController::class)->name('index','proses-yudisium');
            
            Route::resource('/setting-pisn', SettingPisnController::class)->name('index','setting-pisn-yudisium');
            Route::post('/cetak-transkrip-nilai', [MahasiswaController::class, 'cetakTranskripNilai']);
            Route::resource('/cetak', CetakYudisiumController::class)->name('index','cetak-yudisium');
            Route::get('/cetak-daftar-yudisium/{idEnkripsi}', [CetakYudisiumController::class, 'show'])->name('show','cetak-daftar-yudisium');
            Route::resource('/pengesahan', PengesahanYudisiumController::class)->name('index','pengesahan-yudisium');
        });

        // Wisuda
        Route::prefix('/wisuda')->group(function (){
            Route::resource('/setting', SettingWisudaController::class)->name('index','setting-wisuda');
            Route::resource('/daftar-pendaftar-wisuda', AdminDaftarPendaftarWisudaController::class)->name('index','daftar-pendaftar-wisuda');
            Route::put('/daftar-pendaftar-wisuda/acc/{id}', [AdminDaftarPendaftarWisudaController::class, 'acc'])->name('index','acc-wisudawan');
            Route::resource('/daftar-wisudawan', AdminDaftarWisudawanController::class)->name('index','daftar-wisudawan');

            Route::resource('/daftar-wisudawan-arsip', ArsipAdminDaftarWisudawanController::class)->name('index','daftar-wisudawan-arsip');
            Route::resource('/cetak', CetakWisudawanController::class)->name('index','cetak-wisudawan');
        });

    });

    // route KRS
    Route::get('/admin/masterdata/krs', [KrsController::class, 'index']);
    Route::post('/admin/masterdata/krs/list-mhs', [KrsController::class, 'listMhs']);
    Route::post('/admin/masterdata/krs/ganti-status-krs', [KrsController::class, 'gantiStatus']);
    Route::get('/admin/masterdata/krs/admin/input/{id}/{ta}', [KrsController::class, 'inputadminKRS']);
    Route::get('/admin/masterdata/krs/admin/hapus/{id}', [KrsController::class, 'hapusadminKRS']);
    Route::get('/admin/masterdata/krs/input/{id}/{mhs}', [KrsController::class, 'tambahadminKRS']);
    Route::post('/admin/masterdata/krs/list-jadwal', [KrsController::class, 'showJadwal']);

    Route::get('/admin/keuangan/generate_mhs', [KeuanganController::class, 'generate_mhs']);
    Route::get('/admin/keuangan/generate_angkatan', [KeuanganController::class, 'generate_angkatan']);
    Route::get('/admin/keuangan/generate_user_mhs', [KeuanganController::class, 'generate_user_mhs']);
    Route::post('/admin/keuangan/bulk_action', [KeuanganController::class, 'bulk_action']);
    Route::get('/admin/keuangan/tagihan_show/{id}', [TagihanController::class, 'index']);
    Route::get('/admin/keuangan/tagihan/publish/{id}', [TagihanController::class, 'publish']);
    Route::get('/admin/keuangan/tagihan/unpublish/{id}', [TagihanController::class, 'unpublish']);

    Route::get('attendance/report', [PresenceController::class,'report'])->name('attendance_report');
    Route::get('attendance/log', [PresenceController::class,'log'])->name('attendance_log');
    Route::get('working/get_table', [WorkingHourController::class,'get_table'])->name('get_table_working');
    // Route::get('/admin/keuangan/buka_tutup_prodi', [BukaTutupController::class, 'index']);


    // route mkKurikulum
    Route::get('/admin/masterdata/matakuliah-kurikulum', [MkKurikulum::class, 'index']);
    Route::get('/admin/masterdata/matakuliah-kurikulum/get_table', [MkKurikulum::class, 'get_table']);
    Route::get('/admin/masterdata/matakuliah-kurikulum/copy_mk/{id}', [MkKurikulum::class, 'copy_mk']);
    Route::post('/admin/masterdata/matakuliah-kurikulum/get', [MkKurikulum::class, 'daftarKur']);
    Route::post('/admin/masterdata/matakuliah-kurikulum/save', [MkKurikulum::class, 'simpandaftarKur']);
    Route::post('/admin/masterdata/matakuliah-kurikulum/update', [MkKurikulum::class, 'updateMK']);
    Route::get('/admin/masterdata/matakuliah-kurikulum/delete/{id}', [MkKurikulum::class, 'destroy']);

    Route::get('admin/akademik/khs/{nim}', [adminKhs::class, 'show']);


    Route::resource('admin/admisi/gelombang', GelombangController::class)->name('index','gelombang');
    Route::resource('admin/admisi/peserta', PmbPesertaController::class)->name('index','peserta');
    Route::resource('admin/admisi/daftar_soal', DaftarSoalController::class)->name('index','daftar_soal');
    Route::resource('admin/admisi/jalur_pendaftaran', PmbJalurController::class)->name('index','jalur_pendaftaran');
    Route::resource('admin/admisi/user_pmb', UserGuestController::class)->name('index','user_pmb');
    Route::resource('admin/admisi/slideshow', SlideController::class)->name('index','slideshow');
    Route::resource('admin/admisi/biaya_pendaftaran', BiayaPendaftaranController::class)->name('index','biaya_pendaftaran');

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
    Route::resource('admin/kepegawaian/penghargaan', PegawaiPenghargaanController::class)->name('index','penghargaan');
    Route::resource('admin/kepegawaian/pegawai', PegawaiController::class)->name('index', 'pegawai');
    Route::resource('admin/kepegawaian/struktural', PegawaiJabatanStrukturalController::class)->name('index', 'struktural');
    Route::resource('admin/kepegawaian/fungsional', PegawaiJabatanFungsionalController::class)->name('index', 'fungsional');
    Route::resource('admin/kepegawaian/mengajar', PegawaiMengajarController::class)->name('index', 'mengajar');
    Route::resource('admin/kepegawaian/penelitian', PegawaiPenelitianController::class)->name('index', 'penelitian');
    Route::resource('admin/kepegawaian/pengabdian', PegawaiPengabdianController::class)->name('index', 'pengabdian');
    Route::resource('admin/kepegawaian/karya', PegawaiKaryaController::class)->name('index', 'karya');
    Route::resource('admin/kepegawaian/organisasi', PegawaiOrganisasiController::class)->name('index', 'organisasi');
    Route::resource('admin/kepegawaian/repository', PegawaiRepositoryController::class)->name('index', 'repository');
    Route::resource('admin/kepegawaian/pekerjaan', PegawaiPekerjaanController::class)->name('index', 'pekerjaan');
    Route::resource('admin/kepegawaian/pendidikan', PegawaiPendidikanController::class)->name('index', 'pendidikan');
    Route::resource('admin/kepegawaian/berkas', PegawaiBerkasController::class)->name('index', 'berkas');
    Route::resource('admin/kepegawaian/jamkerja', JamkerjaController::class)->name('index', 'jamkerja');
    Route::resource('admin/kepegawaian/surat_izin', SuratIzinController::class)->name('index', 'surat_izin');


    Route::resource('admin/akademik/perwalian', PerwalianController::class)->name('index','perwalian_admin');
    Route::resource('admin/akademik/list-soal', SoalKuesionerController::class)->name('index','list-soal');


    Route::resource('admin/keuangan/bank_data_va', VaController::class)->name('index','index_va');
    Route::resource('admin/keuangan/buka_tutup_prodi', ProdiBukaTutupController::class)->name('index', 'buka_tutup_prodi');
    Route::resource('admin/keuangan/jenis_keuangan', JenisKeuanganController::class)->name('index','keuangan');
    Route::resource('admin/keuangan/lapor_bayar', AdminLaporPembayaranContoller::class)->name('index','lapor_bayar');
    Route::resource('admin/keuangan/tagihan', TagihanController::class)->name('index','keuangan');
    Route::resource('admin/keuangan/setting_keuangan', SettingKeuanganController::class)->name('index','setting_keuangan');
    Route::resource('admin/keuangan', KeuanganController::class)->name('index','keuangan');
    Route::resource('working', WorkingHourController::class)->name('index','working_hour');


    // Route Aset
    Route::resource('admin/aset/tanah', AsetTanahController::class)->name('index', 'aset-tanah');
    Route::resource('admin/aset/gedung-bangunan', AsetGedungBangunanController::class)->name('index', 'aset-gedung-bangunan');
    Route::resource('admin/aset/barang', AsetBarangController::class)->name('index', 'aset-barang');
    Route::resource('admin/aset/kendaraan', AsetKendaraanController::class)->name('index', 'aset-barang');
    Route::resource('pegawai', UserPegawaiController::class)->name('index', 'pegawai');
    Route::resource('riwayat', RiwayatPegawaiController::class)->name('index', 'pegawai');

    Route::resource('admin/nilai_lama', NilaiLamaController::class)->name('index', 'nilai_lama');
    Route::resource('admin/role', RoleController::class)->name('index', 'role');


    Route::group(['prefix' => 'admin/skripsi/pembimbing', 'as' => 'admin.pembimbing.', 'controller' => DosenPembimbingController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/list-dosen', 'getListDosen')->name('listDosen');
        Route::get('/get-data', 'getData')->name('getData');
        Route::post('/acc-dosen', 'accDosen')->name('accDosen');
        Route::get('/edit-dosen/{nip}', 'edit')->name('editDosen');
        Route::post('/update-kuota', 'updateKuota')->name('updateKuota');
        Route::get('/getNpp', 'getNppDosen')->name('getNppDosen');
    });
    Route::group(['prefix' => 'admin/skripsi/manajemen', 'as' => 'admin.skripsi.manajemen.', 'controller' => ManajemenSkripsiController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sidang', 'index_sidang')->name('sidang.index');
        Route::get('/bimbingan', 'index_bimbingan')->name('bimbingan.index');
        Route::get('/daftar', 'index_daftar')->name('daftar.index');
        Route::get('/daftar/{id}', 'detail')->name('detail');
        Route::get('/mahasiswa/{id}', 'mahasiswa')->name('mahasiswa');
        Route::post('/modifysks', 'modifySKS')->name('daftar.sks');
        Route::get('/tambahKoor', 'tambahKoor')->name('daftar.koordinator');
        Route::get('/list-mahasiswa/{id}', 'ListMahasiswaByProd')->name('ListMahasiswaByProd');
        Route::get('/get-data/{nip}', 'getAllData')->name('getAllData');
    });

    Route::resource('admin/aset/cetak-label', CetakLabelController::class)->name('index', 'aset-cetak-label');
    Route::post('/admin/aset/cetak-label/cetak', [CetakLabelController::class, 'generatePdf'])->name('aset-cetak-label-cetak');


    Route::resource('admin/nilai_lama', NilaiLamaController::class)->name('index','nilai_lama');


    Route::get('/alumni/get_alumni', [AlumniController::class, 'get_alumni'])->name('get_alumni');
    Route::post('/admin/alumni/cetak-ijazah', [AlumniController::class, 'cetakIjazah']);
});

Route::group(['middleware' => ['auth', 'role:mhs|super-admin']], function () {
    Route::get('/mhs/dashboard', [DashboardController::class, 'mhs'])->name('dashboard_mahasiswa');

    Route::get('/mahasiswa/detail/{nim}', [MahasiswaController::class, 'detail']);
    Route::get('/mahasiswa/ktm/{nim}', [MahasiswaController::class, 'cetakKtm']);
    Route::post('mahasiswa/user_update', [MahasiswaController::class, 'user_update'])->name('user_update');
    Route::post('mahasiswa/user_update2', [MahasiswaController::class, 'user_update2'])->name('user_update2');
    Route::post('mahasiswa/foto_update', [MahasiswaController::class, 'foto_update'])->name('foto_update');
    Route::post('mahasiswa/berkas_update', [MahasiswaController::class, 'berkas_update'])->name('berkas_update');
    Route::get('mhs/absensi', [mhsAbsensiController::class, 'index'])->name('index_absensi');
    Route::post('mahasiswa_new', [MahasiswaController::class, 'store'])->name('input');
    Route::get('/mahasiswa/cetak-transkrip', [MahasiswaController::class, 'cetakTranskrip']);
    Route::get('/mhs/tagihan', [TagihanMhsController::class, 'index']);
    Route::get('/mhs/lapor_bayar', [LaporPembayaranController::class, 'create']);
    Route::post('/mhs/lapor_bayar', [LaporPembayaranController::class, 'store']);


    Route::get('mhs/profile', [ProfileController::class, 'index'])->name('index');
    // Route::get('mhs/heregistrasi', [ProfileController::class, 'heregistrasi'])->name('index_heregistrasi');

    Route::get('mhs/berkas', [MahasiswaBerkasController::class, 'index'])->name('mhs-berkas');
    Route::post('mhs/berkas', [MahasiswaBerkasController::class, 'store'])->name('store-mhs-berkas');

    Route::get('mhs/input_krs', [mhsKrsController::class, 'input'])->name('input');
    Route::get('mhs/riwayat_krs', [mhsKrsController::class, 'riwayat'])->name('riwayat_krs');
    Route::get('/admin/masterdata/krs/admin/hapus/{id}', [KrsController::class, 'hapusadminKRS']);
    Route::post('/admin/masterdata/krs/list-jadwal', [KrsController::class, 'showJadwal']);
    Route::get('/admin/masterdata/krs/admin/download/{id}', [KrsController::class, 'downloadkrs']);
    Route::get('/admin/masterdata/krs/input/{id}/{mhs}', [KrsController::class, 'tambahadminKRS']);

    Route::get('/admin/masterdata/krs/admin/download/{id}', [KrsController::class, 'downloadkrs']);
    Route::get('mhs/ujian', [UjianController::class, 'index'])->name('index_ujian');
    Route::get('mhs/ujian/cetak_uts', [UjianController::class, 'cetak_uts'])->name('cetak_uts');
    Route::get('mhs/ujian/cetak_uas', [UjianController::class, 'cetak_uas'])->name('cetak_uas');

    Route::get('mhs/khs/{id}', [KhsController::class, 'index'])->name('index_khs');
    Route::get('mhs/khs', [KhsController::class, 'index'])->name('index_khs');
    Route::get('mhs/khs_riwayat', [KhsController::class, 'riwayat'])->name('khs_riwayat');
    Route::get('mhs/daftar_nilai', [DaftarNilaiController::class, 'index'])->name('daftar_nilai');
    Route::get('mhs/kuesioner_mhs', [KuesionerMhsController::class, 'index'])->name('index_kuesioner');
    Route::post('mhs/kuesioner_mhs', [KuesionerMhsController::class, 'store'])->name('save_kuesioner');
    Route::get('mhs/cetak_khs', [KhsController::class, 'cetak_khs'])->name('cetak_khs');
    Route::get('mhs/cetak_khs/{nim}', [KhsController::class, 'cetak_khs'])->name('cetak_khs');

    // daftar wisuda
    Route::group(['prefix' => 'mhs/akademik', 'as' => 'mhs.akademik.'], function () {

        Route::group(['prefix' => 'daftar-wisuda', 'as' => 'daftar-wisuda.', 'controller' => DaftarWisudaController::class], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::patch('/update/{id}', 'update')->name('update');
            Route::post('/delete/{id}', 'delete')->name('delete');
            Route::post('/upload-bukti-bayar', 'uploadBuktiBayar')->name('upload-bukti-bayar');
        });

    });

    Route::prefix('akademik')->name('akademik.')->group(function () {

        Route::prefix('skripsi')->name('skripsi.')->group(function () {

            Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {

                Route::prefix('nilai-sidang')->name('nilai-sidang.')->group(function () {
                    Route::get('/', [NilaiSidangController::class, 'index'])->name('index');
                    Route::get('/show/{id}', [NilaiSidangController::class, 'show'])->name('show');
                });

            });
            
        });

    });

    Route::get('/mhs/absensi/history/{id_jadwal}', [mhsAbsensiController::class, 'setAbsensiSatuan'] );
    Route::get('/mhs/absensi/save/{id_jadwal}', [mhsAbsensiController::class, 'saveAbsensi'] );
    Route::group(['prefix' => 'mahasiswa/pengajuan', 'as' => 'mhs.pengajuan.', 'controller' => PengajuanController::class], function () {
        Route::get('/', 'index')->name('index');
    });
    Route::group(['prefix' => 'mahasiswa/skripsi/pengajuan/pembimbing', 'as' => 'mhs.pengajuan.pembimbing.', 'controller' => PengajuanPembimbingController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
    });
    Route::group(['prefix' => 'mahasiswa/skripsi/pengajuan/judul', 'as' => 'mhs.pengajuan.judul.', 'controller' => PengajuanSkripsiController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
    });
    Route::group(['prefix' => 'mahasiswa/skripsi/pengajuan/sidang', 'as' => 'mhs.pengajuan.sidang.', 'controller' => PengajuanSidangController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
    });
    Route::group(['prefix' => 'mahasiswa/skripsi/pembimbing', 'as' => 'mhs.pembimbing.', 'controller' => PembimbingController1::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/list-dosen', 'getDaftarPembimbing')->name('getDaftarPembimbing');
        // Route::get('/get-data', 'getData')->name('getData');
        Route::post('/pengajuan', 'pengajuan')->name('pengajuan');
        // Route::get('/edit-dosen/{nip}', 'edit')->name('editDosen');
        // Route::post('/update-kuota', 'updateKuota')->name('updateKuota');
        // Route::get('/getNpp', 'getNppDosen')->name('getNppDosen');
    });
    Route::group(['prefix' => 'mahasiswa/skripsi/bimbingan', 'as' => 'mhs.bimbingan.', 'controller' => BimbinganController::class], function () {
        Route::get('/', 'index')->name('index');
        // Route::get('/list-dosen', 'getDaftarPembimbing')->name('getDaftarPembimbing');
        // Route::get('/get-data', 'getData')->name('getData');
        Route::post('/upload', 'UploadBimbingan')->name('UploadBimbingan');
        Route::get('/getModalLogbook/{id}', 'getModalLogbook')->name('getModalLogbook');
        // Route::post('/update-kuota', 'updateKuota')->name('updateKuota');
        // Route::get('/getNpp', 'getNppDosen')->name('getNppDosen');
    });
    Route::group(['prefix' => 'mahasiswa/skripsi/daftar', 'as' => 'mhs.skripsi.daftar.', 'controller' => PengajuanController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/save', 'saveDaftar')->name('saveDaftar');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}/{isEdit?}', 'show')->name('show');
        Route::post('/melengkapi', 'melengkapiJudul')->name('melengkapiJudul');
        // Route::get('/list-dosen', 'getDaftarPembimbing')->name('getDaftarPembimbing');
        Route::get('/get-data', 'getData')->name('getData');
        // Route::post('/pengajuan', 'pengajuan')->name('pengajuan');
        // Route::get('/edit-dosen/{nip}', 'edit')->name('editDosen');
        // Route::post('/update-kuota', 'updateKuota')->name('updateKuota');
        Route::get('/get-data-pengajuan/{id}', 'getDataPengajuanJudul')->name('getDataPengajuanJudul');
        Route::put('/{id?}', 'update')->name('update');
    });
    Route::group(['prefix' => 'mahasiswa/skripsi/berkas', 'as' => 'mhs.skripsi.berkas.', 'controller' => BerkasController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/LogBook', 'BerkasLogbook')->name('BerkasLogbook');
        Route::get('/BerkasBimbingan', 'BerkasBimbingan')->name('BerkasBimbingan');
        Route::post('/upload', 'UploadBerkas')->name('UploadBerkas');
    });
    Route::group(['prefix' => 'skripsi/bimbingan', 'as' => 'mhs.skripsi.bimbingan.', 'controller' => BimbinganSkripsiController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/detail/{id}', 'detail')->name('detail');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('delete');
        Route::get('/logbook/{nimEnkripsi}', 'logBook')->name('download-logbook');
        // Route::get('/BerkasBimbingan', 'BerkasBimbingan')->name('BerkasBimbingan');
        // Route::post('/upload', 'UploadBerkas')->name('UploadBerkas');
    });
    Route::group(['prefix' => 'skripsi/berkas', 'as' => 'mhs.skripsi.berkas.', 'controller' => BerkasSkripsiController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        // Route::get('/detail/{id}', 'detail')->name('detail');
        // Route::get('/LogBook', 'BerkasLogbook')->name('BerkasLogbook');
        // Route::get('/BerkasBimbingan', 'BerkasBimbingan')->name('BerkasBimbingan');
        // Route::post('/upload', 'UploadBerkas')->name('UploadBerkas');
    });
    Route::group(['prefix' => 'skripsi/sidang', 'as' => 'mhs.skripsi.sidang.', 'controller' => SidangMahasiswa::class], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        // Route::get('/detail/{id}', 'detail')->name('detail');
        // Route::get('/LogBook', 'BerkasLogbook')->name('BerkasLogbook');
        // Route::get('/BerkasBimbingan', 'BerkasBimbingan')->name('BerkasBimbingan');
        // Route::post('/upload', 'UploadBerkas')->name('UploadBerkas');
    });

    //Route::post('admin/admisi/peserta/daftar_kota',[PmbPesertaController::class, 'daftar_kota'] )->name('daftar_kota');
});
Route::group(['middleware' => ['auth', 'role:pegawai|super-admin']], function () {

    Route::prefix('skripsi')->as('koor.skripsi.')->controller(SkripsiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update-sks', 'updateSks')->name('update-sks');
        Route::post('/berkas', 'storeBerkas')->name('berkas.store');
        Route::put('/berkas/{id}', 'updateBerkas')->name('berkas.update');
        Route::delete('/berkas/{id}', 'destroyBerkas')->name('berkas.destroy');
    });
    Route::prefix('pembimbing')->name('pembimbing.')->group(function () {
        Route::get('/', [PembimbingController::class, 'index'])->name('index');
        Route::get('/data', [PembimbingController::class, 'getPembimbingData'])->name('data');
        Route::post('/store', [PembimbingController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PembimbingController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PembimbingController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [PembimbingController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('sidang')->name('sidang.')->group(function () {
        Route::get('/', [SidangController::class, 'index'])->name('index');
        Route::post('/store', [SidangController::class, 'store'])->name('store');

        Route::get('/get-data-peserta/{idGelombang?}', [SidangController::class, 'getDataPeserta'])->name('get-data-peserta');
        Route::put('/update/{gelombang}', [SidangController::class, 'update'])->name('update');
        Route::delete('/delete/{gelombang}', [SidangController::class, 'destroy'])->name('delete');

        Route::get('/get-detail/{idSidang}', [SidangController::class, 'getDetail'])->name('get-detail');
        Route::put('/update-jadwal/{idSidang}', [SidangController::class, 'updateJadwal'])->name('update-jadwal');
        Route::put('/update-status-jadwal/{idSidang}', [SidangController::class, 'updateStatusJadwal'])->name('update-status-jadwal');
    });

    Route::prefix('akademik')->name('akademik.')->group(function () {

        Route::prefix('skripsi')->name('skripsi.')->group(function () {

            Route::prefix('dosen')->name('dosen.')->group(function () {

                Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
                    Route::get('/', [DosenBimbinganController::class, 'index'])->name('index');
                    Route::get('/get-data', [DosenBimbinganController::class, 'getData'])->name('get-data');
                    Route::get('/show/{id}', [DosenBimbinganController::class, 'show'])->name('show');
                    Route::put('/update/{id}', [DosenBimbinganController::class, 'update'])->name('update');
                    Route::put('/update-status/{id}', [DosenBimbinganController::class, 'updateStatus'])->name('update-status');
                });

                Route::prefix('penguji')->name('penguji.')->group(function () {
                    Route::get('/', [DosenPengujiController::class, 'index'])->name('index');
                    Route::get('/get-data', [DosenPengujiController::class, 'getData'])->name('get-data');
                    Route::get('/show/{id}', [DosenPengujiController::class, 'show'])->name('show');
                    Route::put('/update-nilai/{id}', [DosenPengujiController::class, 'updateNilai'])->name('update-nilai');
                    Route::put('/update-status/{id}', [DosenPengujiController::class, 'updateStatus'])->name('update-status');
                });

            });
            
        });

    });

    Route::get('admin/kepegawaian/struktural/get_jabatan', [PegawaiJabatanStrukturalController::class, 'get_jabatan'])->name('get_jabatan');
    Route::post('admin/kepegawaian/struktural/get_jabatan', [PegawaiJabatanStrukturalController::class, 'get_jabatan'])->name('get_jabatan');

    Route::get('/dsn/dashboard', [DashboardController::class, 'dosen'])->name('dashboard_pegawai');
    Route::get('/admin/input-batch/{id}', [KrmController::class, 'inputAbsenBatch'])->name('dashboard_pegawai');
    Route::post('/dosen/tampil-pertemuan-absensi', [KrmController::class, 'pertemuanAbsensi'])->name('dashboard_pegawai');
    Route::post('/dosen/simpan-capaian', [KrmController::class, 'simpanCapaian'])->name('dashboard_pegawai');

    Route::get('dosen/berkas', [DosenBerkasController::class, 'index'])->name('dosen-berkas');
    Route::post('dosen/berkas', [DosenBerkasController::class, 'store'])->name('store-dosen-berkas');

    Route::resource('pegawai', UserPegawaiController::class)->name('index', 'pegawai');
    Route::resource('riwayat', RiwayatPegawaiController::class)->name('index', 'pegawai');
    Route::get('pegawai',[UserPegawaiController::class, 'index'] );
    Route::resource('pegawai', UserPegawaiController::class)->name('index','pegawai');
    Route::resource('riwayat', RiwayatPegawaiController::class)->name('index','riwayat');

    Route::get('dosen/perwalian', [DosenController::class, 'index'])->name('Perwalian');
    Route::get('dosen/{id}/krs', [DosenController::class, 'detailKRS'])->name('detailKRS');
    Route::post('dosen/validasi-krs-satuan', [DosenController::class, 'valiKrsSatuan']);
    Route::post('dosen/validasi-krs', [DosenController::class, 'valiKrs']);
    Route::get('dosen/krm', [KrmController::class, 'index']);
    Route::get('dosen/absensi/{id}/input', [KrmController::class, 'daftarMhs']);
    // Route::get('dosen/nilai/{id}/input', [KrmController::class, 'daftarMhsNilai']);
    Route::get('dosen/{id}/set-pertemuan', [KrmController::class, 'setPertemuan']);
    Route::get('dosen/input/{nim}/absensi/{id_jadwal}', [KrmController::class, 'setAbsensiSatuan']);
    Route::post('dosen/simpan-absensi-satuan', [KrmController::class, 'saveAbsensiSatuan']);
    Route::post('dosen/simpan-kontrak', [KrmController::class, 'saveKontrak']);
    Route::post('dosen/simpan-nilai', [KrmController::class, 'saveNilai']);

    Route::group(['prefix' => 'dsn/skripsi/pengajuan', 'as' => 'dosen.pengajuan.', 'controller' => PengajuanBimbinganController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/list-mhs', 'getDataMahasiswa')->name('getDataMahasiswa');
        Route::get('/detail/{nim}', 'getDetailMhs')->name('getDetailMhs');
        Route::get('/acc/{nim}', 'accPengajuan')->name('acc');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });
    Route::group(['prefix' => 'dsn/skripsi/bimbingan', 'as' => 'dosen.bimbingan.', 'controller' => BimbinganMahasiswaController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getData', 'getDataMhs')->name('getData');
        Route::get('/detail/{id}', 'detail')->name('detail');
        Route::get('/getModalLogbook/{id}', 'getModalLogbook')->name('getModalLogbook');
        Route::post('/upload', 'upload')->name('upload');
        // Route::get('/delete/{id}', 'delete')->name('delete');
    });

    //Route::resource('admin/kepegawaian/pegawai', PegawaiController::class)->name('index','pegawai');
    Route::post('admin/kepegawaian/pegawai', [PegawaiController::class, 'store'])->name('input_pegawai');
    Route::resource('admin/kepegawaian/struktural', PegawaiJabatanStrukturalController::class)->name('index', 'struktural');
    Route::resource('admin/kepegawaian/fungsional', PegawaiJabatanFungsionalController::class)->name('index', 'fungsional');
    Route::resource('admin/kepegawaian/mengajar', PegawaiMengajarController::class)->name('index', 'mengajar');
    Route::resource('admin/kepegawaian/penelitian', PegawaiPenelitianController::class)->name('index', 'penelitian');
    Route::resource('admin/kepegawaian/pengabdian', PegawaiPengabdianController::class)->name('index', 'pengabdian');
    Route::resource('admin/kepegawaian/karya', PegawaiKaryaController::class)->name('index', 'karya');
    Route::resource('admin/kepegawaian/organisasi', PegawaiOrganisasiController::class)->name('index', 'organisasi');
    Route::resource('admin/kepegawaian/repository', PegawaiRepositoryController::class)->name('index', 'repository');
    Route::resource('admin/kepegawaian/pekerjaan', PegawaiPekerjaanController::class)->name('index', 'pekerjaan');
    Route::resource('admin/kepegawaian/pendidikan', PegawaiPendidikanController::class)->name('index', 'pendidikan');
    Route::resource('admin/kepegawaian/berkas', PegawaiBerkasController::class)->name('index', 'berkas');
    Route::resource('admin/kepegawaian/jamkerja', JamkerjaController::class)->name('index', 'jamkerja');
    Route::resource('admin/kepegawaian/surat_izin', SuratIzinController::class)->name('index', 'surat_izin');
    Route::get('dosen/perwalian', [DosenController::class, 'index'] )->name('Perwalian');
    Route::get('dosen/{id}/krs', [DosenController::class, 'detailKRS'] )->name('detailKRS');
    Route::post('dosen/validasi-krs-satuan', [DosenController::class, 'valiKrsSatuan'] );
    Route::post('dosen/validasi-krs', [DosenController::class, 'valiKrs'] );
    Route::get('dosen/krm', [KrmController::class, 'index'] );
    Route::get('dosen/krm_riwayat', [KrmController::class, 'krm_riwayat'] );
    Route::post('dosen/simpan_rps', [KrmController::class, 'simpanRps'] );
    Route::get('dosen/input_nilai', [KrmController::class, 'input_nilai'] );
    Route::get('dosen/absensi/{id}/input', [KrmController::class, 'daftarMhs'] );
    // Route::get('dosen/nilai/{id}/input', [KrmController::class, 'daftarMhsNilai'] );
    Route::get('dosen/{id}/set-pertemuan', [KrmController::class, 'setPertemuan'] );
    Route::get('dosen/input/{nim}/absensi/{id_jadwal}', [KrmController::class, 'setAbsensiSatuan'] );
    Route::post('dosen/simpan-absensi-satuan', [KrmController::class, 'saveAbsensiSatuan'] );
    Route::post('dosen/simpan-kontrak', [KrmController::class, 'saveKontrak'] );
    Route::post('dosen/simpan-nilai', [KrmController::class, 'saveNilai'] );
    Route::post('dosen/simpan-nilai-all', [KrmController::class, 'saveNilaiBatch'] );
    Route::post('dosen/publish-nilai', [KrmController::class, 'publishNilai'] );
    Route::post('dosen/validasi-nilai', [KrmController::class, 'validasiNilai'] );
    Route::get('/dosen/absensi/{id}/{id_pertemuan}/input_new', [KrmController::class, 'daftarMhsNew'] );
    Route::get('/dosen/absensi/{id}/{id_pertemuan}/buka_tutup_absen', [KrmController::class, 'bukaTutupAbsen'] );

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
    Route::resource('admin/kepegawaian/penghargaan', PegawaiPenghargaanController::class)->name('index','penghargaan');
    Route::resource('admin/kepegawaian/kompetensi', PegawaiKompetensiController::class)->name('index','kompetensi');
    Route::resource('admin/kepegawaian/kegiatan_luar', PegawaiKegiatanLuarController::class)->name('index','kegiatan_luar');
    Route::resource('/dosen/attendance', PresenceController::class)->name('index','attendance');

    Route::get('/mahasiswa/detail/{nim}', [MahasiswaController::class, 'detail']);

    Route::get('dosen/setting-pertemuan', [KrmController::class, 'settingPertemuan']);
    
    
    Route::post('/jadwal/get-pertemuan', [JadwalController::class, 'getPertemuan']);
    Route::post('/jadwal/tambah-pertemuan2', [JadwalController::class, 'tambahPertemuan2']);

    Route::post('admin/kepegawaian/pegawai/get_status', [PegawaiController::class, 'get_status'])->name('get_status');
    Route::post('admin/kepegawaian/pegawai/user_update', [PegawaiController::class, 'user_update'])->name('user_update');
    Route::post('admin/kepegawaian/pegawai/foto_update', [PegawaiController::class, 'foto_update'])->name('foto_update');

    Route::post('/dosen/absensi/save_absensi_new', [KrmController::class, 'saveAbsensiNew'] );
});
Route::group(['middleware' => ['auth', 'role:mhs|pegawai|super-admin']], function () {
    Route::post('admin/admisi/peserta/daftar_kota', [PmbPesertaController::class, 'daftar_kota'])->name('daftar_kota_pegawai');
});
Route::group(['middleware' => ['auth', 'role:mhs|pegawai|super-admin']], function () {
    Route::get('dosen/cetak_absensi/{id}', [KrmController::class, 'cetakPertemuan']);
});

Route::group(['middleware' => ['auth', 'role:admin-prodi|pegawai|super-admin']], function () {
    Route::get('dosen/nilai/{id}/input', [KrmController::class, 'daftarMhsNilai']);
});
