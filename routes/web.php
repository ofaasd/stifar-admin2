<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\admin\AsalSekolahController;
use App\Http\Controllers\admin\GelombangController;

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
