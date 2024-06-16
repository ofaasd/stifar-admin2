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
use App\Http\Controllers\admin\kepegawaian\PegawaiController;
use App\Http\Controllers\admin\WaktuController;
use App\Http\Controllers\admin\FakultasController;
use App\Http\Controllers\admin\RumpunController;
use App\Http\Controllers\admin\TahunAjaranController;
use App\Http\Controllers\admin\SesiController;
use App\Http\Controllers\admin\ProdiController;
use App\Http\Controllers\admin\KurikulumController;
use App\Http\Controllers\admin\KelompokMatkulController;
use App\Http\Controllers\admin\MatkulController;
use App\Http\Controllers\admin\master\PTController;
use App\Http\Controllers\admin\master\AtributPTController;

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
Route::post('/actionRegister', [LoginController::class, 'actionRegister'])->name('actionRegister');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware('auth')->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index'] )->name('dashboard');

    Route::post('admin/admisi/peserta/daftar_kota',[PmbPesertaController::class, 'daftar_kota'] )->name('daftar_kota');
    Route::post('admin/admisi/peserta/get_gelombang',[PmbPesertaController::class, 'get_gelombang'] )->name('get_gelombang');
    Route::post('admin/admisi/peserta/get_jurusan',[PmbPesertaController::class, 'get_jurusan'] )->name('get_jurusan');
    Route::get('admin/admisi/peserta/{id}/edit_gelombang', [PmbPesertaController::class, 'edit_gelombang'])->name('edit_gelombang');
    Route::get('admin/admisi/peserta/{id}/edit_asal_sekolah', [PmbPesertaController::class, 'edit_asal_sekolah'])->name('edit_asal_sekolah');
    Route::get('admin/admisi/peserta/{id}/edit_file_pendukung', [PmbPesertaController::class, 'edit_file_pendukung'])->name('edit_file_pendukung');

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

    Route::get('admin/masterdata/pt/atribut', [PTController::class, 'atribut'])->name('atribut');
    Route::get('admin/masterdata/pt/atribut/detail/{id}', [AtributPTController::class, 'index'])->name('atribut_detail');
    Route::get('admin/masterdata/pt/renstra', [PTController::class, 'renstra'])->name('renstra');

    Route::resource('admin/masterdata/pt', PTController::class)->name('index','pt');
    Route::resource('admin/masterdata/pt/atribut/detail', AtributPTController::class)->name('index','pt');
    Route::resource('admin/masterdata/ruang', RuangController::class)->name('index','ruang');
    Route::resource('admin/masterdata/sekolah', AsalSekolahController::class)->name('index','sekolah');
    Route::resource('admin/masterdata/waktu', WaktuController::class)->name('index','waktu');
    Route::resource('admin/masterdata/fakultas', FakultasController::class)->name('index','fakultas');
    Route::resource('admin/masterdata/rumpun', RumpunController::class)->name('index','rumpun');
    Route::resource('admin/masterdata/ta', TahunAjaranController::class)->name('index','ta');
    Route::resource('admin/masterdata/sesi', SesiController::class)->name('index', 'sesi');
    Route::resource('admin/masterdata/kurikulum', KurikulumController::class)->name('index', 'kurikulum');
    Route::resource('admin/masterdata/program-studi', ProdiController::class)->name('index', 'program-studi');
    Route::resource('admin/masterdata/kelompok-mk', KelompokMatkulController::class)->name('index', 'kelompok-mk');
    Route::resource('admin/masterdata/matakuliah', MatkulController::class)->name('index', 'matakuliah');

    Route::resource('admin/admisi/gelombang', GelombangController::class)->name('index','gelombang');
    Route::resource('admin/admisi/peserta', PmbPesertaController::class)->name('index','peserta');
    Route::resource('admin/admisi/daftar_soal', DaftarSoalController::class)->name('index','daftar_soal');

    Route::resource('admin/kepegawaian/pegawai', PegawaiController::class)->name('index','pegawai');


});



