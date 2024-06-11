<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\admin\AsalSekolahController;
use App\Http\Controllers\admin\GelombangController;
use App\Http\Controllers\admin\WaktuController;
use App\Http\Controllers\admin\FakultasController;
use App\Http\Controllers\admin\RumpunController;
use App\Http\Controllers\admin\TahunAjaranController;
use App\Http\Controllers\admin\SesiController;
use App\Http\Controllers\admin\ProdiController;
use App\Http\Controllers\admin\KurikulumController;
use App\Http\Controllers\admin\KelompokMatkulController;
use App\Http\Controllers\admin\MatkulController;
use App\Http\Controllers\admin\JadwalController;
use App\Http\Controllers\admin\MkKurikulum;

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

Route::get('/', function () {
    return redirect()->route('index');
})->name('/');

Route::view('index', 'index')->name('index');
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

// route Matakuliah
Route::get('/admin/masterdata/matakuliah', [MatkulController::class, 'index']);
Route::post('/admin/masterdata/matakuliah/save', [MatkulController::class, 'simpanMK']);
Route::post('/admin/masterdata/matakuliah/update', [MatkulController::class, 'updateMK']);
Route::get('/admin/masterdata/matakuliah/delete/{id}', [MatkulController::class, 'destroy']);


// route jadwal
Route::get('/admin/masterdata/jadwal', [JadwalController::class, 'index']);
Route::get('/admin/masterdata/jadwal/create/{id}', [JadwalController::class, 'daftarJadwal']);
Route::post('/admin/masterdata/jadwal/create', [JadwalController::class, 'createJadwal']);

// route mkKurikulum
Route::get('/admin/masterdata/matakuliah-kurikulum', [MkKurikulum::class, 'index']);
Route::post('/admin/masterdata/matakuliah-kurikulum/get', [MkKurikulum::class, 'daftarKur']);
Route::post('/admin/masterdata/matakuliah-kurikulum/save', [MkKurikulum::class, 'simpandaftarKur']);
Route::post('/admin/masterdata/matakuliah-kurikulum/update', [MkKurikulum::class, 'updateMK']);
Route::get('/admin/masterdata/matakuliah-kurikulum/delete/{id}', [MkKurikulum::class, 'destroy']);
