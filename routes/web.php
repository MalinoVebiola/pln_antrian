<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin2Controller;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\CheckUserRole;

Route::get('/', [AntrianController::class, 'index'])->name('home');

#Register
Route::get('/register', [PenggunaController::class, 'showForm'])->name('pengguna.register');
Route::post('/register', [PenggunaController::class, 'store'])->name('pengguna.store');

#login

Route::get('/login', [AkunController::class, 'showForm'])->name('login');
Route::post('/login', [AkunController::class, 'login'])->name('login.submit');
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'check.role:1'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/antrian', [AntrianController::class, 'show'])->name('admin.antrian');
    Route::get('/admin/pengguna', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/antrian/getAntrianData', [AntrianController::class, 'getAntrianData']);
    Route::post('/antrian/update/{id_antrian}', [AntrianController::class, 'update']);

    Route::get('/antrian', [AntrianController::class, 'index']);
    Route::get('/antrian/getAntrianData', [AntrianController::class, 'getAntrianData']);
    Route::post('/antrian/update/{id}', [AntrianController::class, 'update']);
    Route::post('/antrian/create', [AntrianController::class, 'create']);

});

Route::middleware(['auth', 'check.role:1,2,3,4,5'])->group(function () {
    Route::resource('laporan', LaporanController::class);
    Route::get('/laporan/get-data/{noKtp}', [LaporanController::class, 'getDataByKTP'])->name('laporan.getDataByKTP');
    Route::post('/laporan/store', [LaporanController::class, 'store'])->name('laporan.store');
});

Route::middleware(['auth', 'check.role:2,3,4,5'])->group(function () {
    Route::get('/admin2', [Admin2Controller::class, 'index'])->name('admin2.index');
    Route::get('/admin2/laporan', [Admin2Controller::class, 'laporan'])->name('admin2.laporan');
    Route::post('/laporan/update-status/{id}', [LaporanController::class, 'updateStatus'])->name('laporan.updateStatus');
    Route::post('/laporan/toggle-visibility/{id}', [LaporanController::class, 'toggleVisibility'])->name('laporan.toggleVisibility');

});

Route::middleware(['auth', 'check.role:6'])->group(function () {
    Route::post('/antrian/ambil', [AntrianController::class, 'ambilNomor'])->name('antrian.ambil');
    Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::get('/antrian/laporan', [PenggunaController::class, 'laporan'])->name('antrian.laporan');
    Route::get('/antrian/cetak/{id}', [AntrianController::class, 'cetak'])->name('antrian.cetak');
});






