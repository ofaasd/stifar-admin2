<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\DashboardController;
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
});
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
