<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\DashboardController;

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
