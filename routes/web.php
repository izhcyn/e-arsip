<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\IndeksController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\TemplateSuratController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit'); // POST untuk login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route untuk dashboard
Route::middleware(['auth'])->group(function () {

    Route::resource('user', SuperAdminController::class)->except(['show']);

    // Super Admin Routes
    Route::get('super_admin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    // Routes untuk menampilkan dan mengelola pengguna
    Route::get('super_admin/user', [SuperAdminController::class, 'showUsers'])->name('users.index'); // Tampilkan daftar user
    Route::get('/user/create', [SuperAdminController::class, 'create'])->name('users.create'); // Form tambah user
    Route::post('/user', [SuperAdminController::class, 'store'])->name('users.store'); // Simpan user
    Route::get('/user/{id}', [SuperAdminController::class, 'showUsers'])->name('users.show'); // Detail user
    Route::get('/user/{id}/edit', [SuperAdminController::class, 'edit'])->name('users.edit'); // Edit user
    Route::put('/user/{id}', [SuperAdminController::class, 'update'])->name('users.update'); // Update user
    Route::delete('/user/{id}', [SuperAdminController::class, 'destroy'])->name('users.destroy'); // Hapus user
    Route::post('/user/store', [SuperAdminController::class, 'store'])->name('users.store');
    Route::resource('user', SuperAdminController::class);
    Route::get('/suratmasuk', [SuratMasukController::class, 'index'])->name('suratmasuk.index');
    Route::get('/suratkeluar', [SuratKeluarController::class, 'index'])->name('suratkeluar.index');
    Route::get('/indeks', [IndeksController::class, 'index'])->name('indeks.index');
    Route::get('/indeks/create', [IndeksController::class, 'create'])->name('indeks.create');
    Route::post('/indeks', [IndeksController::class, 'store'])->name('indeks.store');
    Route::get('/indeks/{id}/edit', [IndeksController::class, 'edit'])->name('indeks.edit');
    Route::put('/indeks/{id}', [IndeksController::class, 'update'])->name('indeks.update');
    // Route untuk menghapus indeks
    Route::delete('/indeks/{id}', [IndeksController::class, 'destroy'])->name('indeks.destroy');
    Route::get('/suratkeluar/{id}/download', [SuratController::class, 'downloadPdf'])->name('suratkeluar.download');
    Route::get('/buatsurat', [SuratController::class, 'create'])->name('buatsurat.index');
    Route::get('/get-last-number/{indeks}', [SuratController::class, 'getLastNumber']);

    Route::post('/super_admin/store', [SuratController::class, 'store'])->name('super_admin.store');
    Route::get('/balas_surat/{id}', [SuratController::class, 'balasSurat'])->name('balas_surat');
    Route::post('/suratmasuk/balas/{id}', [SuratController::class, 'storeBalasSurat'])->name('suratmasuk.balas');
    // Route untuk menampilkan form balasan surat
    Route::get('/balas-surat/{id}', [SuratController::class, 'balasSurat'])->name('suratmasuk.balas');
    // Route untuk menyimpan balasan surat
    Route::post('/balas-surat/{id}', [SuratController::class, 'storeBalasSurat'])->name('suratmasuk.balas.store');
    Route::resource('/template', TemplateSuratController::class);
    Route::get('/template', [TemplateSuratController::class, 'index'])->name('template.index');
    Route::get('/template/create', [TemplateSuratController::class, 'create'])->name('template.create');
    Route::post('/template', [TemplateSuratController::class, 'store'])->name('template.store');
    Route::get('/template/{id}/edit', [TemplateSuratController::class, 'edit'])->name('template.edit');
    Route::put('/template/{id}', [TemplateSuratController::class, 'update'])->name('template.updtae');
    Route::delete('/template/{id}', [TemplateSuratController::class, 'destroy'])->name('template.destroy');
    //menyimpan surat masuk
    Route::post('/super_admin/suratmasuk', [SuratMasukController::class, 'store'])->name('suratmasuk.store');
    Route::get('/surat/{id}', [SuratMasukController::class, 'show'])->name('surat.show');
    Route::get('/suratmasuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('suratmasuk.edit');
    // Route to handle the update process
    Route::put('/suratmasuk/{id}', [SuratMasukController::class, 'update'])->name('suratmasuk.update');
    Route::delete('/suratmasuk/{id}', [SuratMasukController::class, 'destroy'])->name('suratmasuk.destroy');
    Route::resource('suratmasuk', SuratMasukController::class);
    Route::post('/suratkeluar', [SuratKeluarController::class, 'store'])->name('suratkeluar.store');
    Route::get('/surat/{id}', [SuratKeluarController::class, 'show'])->name('surat.show');
    Route::get('/suratkeluar/{id}/edit', [SuratKeluarController::class, 'edit'])->name('suratkeluar.edit');
    Route::put('/suratkeluar/{id}', [SuratKeluarController::class, 'update'])->name('suratkeluar.update');
    Route::delete('/suratkeluar/{id}', [SuratKeluarController::class, 'destroy'])->name('suratkeluar.destroy');
    Route::resource('suratkeluar', SuratKeluarController::class);
    Route::post('/surat/store', [SuratController::class, 'store'])->name('surat.store');
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/surat/draft/{id}', [SuratController::class, 'loadDraftById'])->name('draft.loadById');
    Route::get('/surat-keluar/{id}', [SuratKeluarController::class, 'showDraft']);
    Route::get('/drafts', [SuratController::class, 'showDrafts'])->name('drafts.index');
    Route::get('/surat/draft/{id}', [SuratController::class, 'loadDraftById'])->name('draft.loadById');
    Route::get('/draft/load/{id}', [SuratController::class, 'loadDraftById'])->name('draft.loadById');
    Route::delete('/draft/delete/{id}', [SuratController::class, 'deleteDraft'])->name('draft.delete');

    // Save draft logic remains the same
    Route::post('/draft/save', [SuratController::class, 'saveDraft'])->name('draft.save');

    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/buatsurat', [SuratController::class, 'create'])->name('buatsurat.index');
    Route::get('/suratkeluar', [SuratKeluarController::class, 'index'])->name('suratkeluar.index');
    Route::get('/suratmasuk', [SuratMasukController::class, 'index'])->name('suratmasuk.index');
    Route::get('/indeks', [IndeksController::class, 'index'])->name('indeks.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/template', [TemplateSuratController::class, 'index'])->name('template.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');


    Route::get('users/dashboard', [UsersController::class, 'index'])->name('users.dashboard');

});
