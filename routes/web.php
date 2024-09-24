<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\IndeksController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\TemplateSuratController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit'); // POST untuk login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route untuk dashboard
Route::middleware(['auth'])->group(function () {
    // Super Admin Routes
    Route::get('super_admin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    // Routes untuk menampilkan dan mengelola pengguna
    Route::get('super_admin/user', [SuperAdminController::class, 'showUsers'])->name('users.index'); // Tampilkan daftar user
    Route::get('super_admin/user/create', [SuperAdminController::class, 'create'])->name('users.create'); // Form tambah user
    Route::post('super_admin/user', [SuperAdminController::class, 'store'])->name('users.store'); // Simpan user
    Route::get('super_admin/user/{id}', [SuperAdminController::class, 'show'])->name('users.show'); // Detail user
    Route::get('super_admin/user/{id}/edit', [SuperAdminController::class, 'edit'])->name('users.edit'); // Edit user
    Route::put('super_admin/user/{id}', [SuperAdminController::class, 'update'])->name('users.update'); // Update user
    Route::delete('super_admin/user/{id}', [SuperAdminController::class, 'destroy'])->name('users.destroy'); // Hapus user
    Route::post('/user/store', [SuperAdminController::class, 'store'])->name('users.store');
    Route::resource('user', SuperAdminController::class);

    Route::get('super_admin/suratmasuk', [SuratMasukController::class, 'index'])->name('suratmasuk.index');
    Route::get('super_admin/suratkeluar', [SuratKeluarController::class, 'index'])->name('suratkeluar.index');
    // Route untuk menampilkan daftar indeks
    Route::get('super_admin/indeks', [IndeksController::class, 'index'])->name('indeks.index');

    // Route untuk menampilkan form tambah indeks
    Route::get('super_admin/indeks/create', [IndeksController::class, 'create'])->name('indeks.create');

    // Route untuk menyimpan indeks baru
    Route::post('super_admin/indeks', [IndeksController::class, 'store'])->name('indeks.store');

    // Route untuk menampilkan form edit indeks
    Route::get('/super_admin/indeks/{id}/edit', [IndeksController::class, 'edit'])->name('indeks.edit');
    Route::put('/super_admin/indeks/{id}', [IndeksController::class, 'update'])->name('indeks.update');

    // Route untuk menghapus indeks
    Route::delete('super_admin/indeks/{id}', [IndeksController::class, 'destroy'])->name('indeks.destroy');


    Route::get('/super_admin/buatsurat', [SuratController::class, 'create'])->name('super_admin.buatsurat');
    Route::post('/super_admin/store', [SuratController::class, 'store'])->name('super_admin.store');


    Route::resource('super_admin/template', TemplateSuratController::class);
    Route::get('/super_admin/template', [TemplateSuratController::class, 'index'])->name('template.index');
    Route::get('/super_admin/template/create', [TemplateSuratController::class, 'create'])->name('template.create');
    Route::post('/super_admin/template', [TemplateSuratController::class, 'store'])->name('template.store');
    Route::get('/super_admin/template/{id}/edit', [TemplateSuratController::class, 'edit'])->name('template.edit');
    Route::put('/super_admin/template/{id}', [TemplateSuratController::class, 'update'])->name('template.updtae');
    Route::delete('/super_admin/template/{id}', [TemplateSuratController::class, 'destroy'])->name('template.destroy');

    // Admin dan User Dashboard
    Route::get('admin/dashboard', [AdminController::class, 'index']);
    Route::get('user/dashboard', [UserController::class, 'index']);

    //menyimpan surat masuk
    Route::post('super_admin/suratmasuk', [SuratMasukController::class, 'store'])->name('suratmasuk.store');
    Route::get('/surat/{id}', [SuratMasukController::class, 'show'])->name('surat.show');
    Route::get('/suratmasuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('suratmasuk.edit');

    // Route to handle the update process
    Route::put('/suratmasuk/{id}', [SuratMasukController::class, 'update'])->name('suratmasuk.update');
    Route::delete('/super_admin/suratmasuk/{id}', [SuratMasukController::class, 'destroy'])->name('suratmasuk.destroy');
    Route::resource('suratmasuk', SuratMasukController::class);

    Route::post('super_admin/suratkeluar', [SuratKeluarController::class, 'store'])->name('suratkeluar.store');
    Route::get('/surat/{id}', [SuratKeluarController::class, 'show'])->name('surat.show');
    Route::get('/suratkeluar/{id}/edit', [SuratKeluarController::class, 'edit'])->name('suratkeluar.edit');
    Route::put('/suratkeluar/{id}', [SuratKeluarController::class, 'update'])->name('suratkeluar.update');
    Route::delete('/super_admin/suratkeluar/{id}', [SuratKeluarController::class, 'destroy'])->name('suratkeluar.destroy');
    Route::resource('suratkeluar', SuratKeluarController::class);
});


