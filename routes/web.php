<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit'); // POST untuk login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route untuk dashboard
Route::middleware(['auth'])->group(function () {

    // Super Admin Routes
    Route::get('super_admin/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/super_admin/suratmasuk', [SuperAdminController::class, 'suratmasuk']);


    // Routes untuk menampilkan dan mengelola pengguna
    Route::get('super_admin/user', [SuperAdminController::class, 'showUsers'])->name('user.index'); // Tampilkan daftar user
    Route::get('super_admin/user/create', [SuperAdminController::class, 'create'])->name('user.create'); // Form tambah user
    Route::get('super_admin/user/{id}', [SuperAdminController::class, 'show'])->name('user.show'); // Detail user
    Route::get('super_admin/user/{id}/edit', [SuperAdminController::class, 'edit'])->name('user.edit'); // Edit user
    Route::put('super_admin/user/{id}', [SuperAdminController::class, 'update'])->name('user.update'); // Update user
    Route::delete('super_admin/user/{id}', [SuperAdminController::class, 'destroy'])->name('user.destroy'); // Hapus user
    Route::post('super_admin/user', [SuperAdminController::class, 'store'])->name('user.store');


    Route::get('super_admin/suratmasuk', [SuratMasukController::class, 'index'])->name('suratmasuk.index');
    Route::get('super_admin/suratkeluar', [SuratKeluarController::class, 'index'])->name('suratkeluar.index');




    // Admin dan User Dashboard
    Route::get('admin/dashboard', [AdminController::class, 'index']);
    Route::get('user/dashboard', [UserController::class, 'index']);
});

