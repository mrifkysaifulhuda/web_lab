<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\KebutuhanAlatController;
use App\Http\Controllers\KebutuhanBahanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\LaboratoriumController;

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
    return redirect('/alat');
});

Route::group(['middleware' => ['guest']], function() {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login',  [LoginController::class, 'login'])->name('login.perform');

});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/logout', [LogoutController::class, 'perform'])->name('logout.perform');
    Route::get('/alat', [AlatController::class, 'index'])->name("alat");
    Route::get('/alat/create', [AlatController::class, 'create']);
    Route::post('/alat/store', [AlatController::class, 'store'])->name("alat.store");
    Route::put('/alat/update/{id}', [AlatController::class,'update'])->name("alat.update");
    Route::get('/alat/edit/{id}', [AlatController::class, 'edit']);
    Route::get('/alat/destroy/{id}', [AlatController::class, 'destroy']);
    Route::get('/alat/list', [AlatController::class, 'getListOfAlat'])->name('alat.list');
    Route::get('/alat/get_combo',[AlatController::class,'getComboAlat'])->name('alat.get_combo');
    Route::get('/alat/show/{id}',[AlatController::class,'show'])->name('alat.show');
    Route::get('/alat/stok/{id}',[AlatController::class,'stok'])->name('alat.stok');
    Route::post('/alat/store_stok',[AlatController::class,'storeStok'])->name('alat.store_stok');
    Route::get('/alat/list_stok/{id}', [AlatController::class, 'getListOfStok'])->name('alat.list_stok');
    Route::post('alat/destroy_stok',[AlatController::class, 'destroyStok'])->name('alat.destroy_stok');

    Route::get('/bahan', [BahanController::class, 'index'])->name("bahan");
    Route::get('/bahan/create', [BahanController::class, 'create']);
    Route::post('/bahan/store', [BahanController::class, 'store'])->name("bahan.store");
    Route::put('/bahan/update/{id}', [BahanController::class,'update'])->name("bahan.update");
    Route::get('/bahan/edit/{id}', [BahanController::class, 'edit']);
    Route::get('/bahan/destroy/{id}', [BahanController::class, 'destroy']);
    Route::get('/bahan/list', [BahanController::class, 'getListOfBahan'])->name('bahan.list');
    Route::get('/bahan/get_combo',[BahanController::class,'getComboBahan'])->name('bahan.get_combo');
    Route::get('/bahan/show/{id}',[BahanController::class,'show'])->name('bahan.show');
    Route::get('/bahan/stok/{id}',[BahanController::class,'stok'])->name('bahan.stok');
    Route::post('/bahan/store_stok',[BahanController::class,'storeStok'])->name('bahan.store_stok');
    Route::get('/bahan/list_stok/{id}', [BahanController::class, 'getListOfStok'])->name('bahan.list_stok');
    Route::post('bahan/destroy_stok',[BahanController::class, 'destroyStok'])->name('bahan.destroy_stok');

    Route::get('/mdpraktikum', [PraktikumController::class, 'index'])->name("mdpraktikum");
    Route::get('/mdpraktikum/list', [PraktikumController::class, 'getListOfMdPraktikum'])->name('mdpraktikum.list');
    Route::post('/mdpraktikum/store', [PraktikumController::class, 'store'])->name("mdpraktikum.store");
    Route::post('/mdpraktikum/destroy', [PraktikumController::class, 'destroy'])->name("mdpraktikum.destroy");
    Route::get('/mdpraktikum/show/{id}', [PraktikumController::class, 'show'])->name("mdpraktikum.show");
    Route::post('/mdpraktikum/ajukan', [PraktikumController::class, 'ajukan'])->name("mdpraktikum.ajukan");

    Route::get('/kebalat/list/{id}', [KebutuhanAlatController::class, 'getListOfKebAlat'])->name('keb-alat.list');
    Route::post('/kebalat/store', [KebutuhanAlatController::class, 'store'])->name('keb-alat.store');
    Route::post('/kebalat/destroy', [KebutuhanAlatController::class, 'destroy'])->name("keb-alat.destroy");
    Route::post('/kebalat/update_jumlah_ajuan',[KebutuhanAlatController::class, 'update_jumlah_ajuan'])->name("keb-alat.update_jumlah_ajuan");

    Route::get('/kebbahan/list/{id}', [KebutuhanBahanController::class, 'getListOfKebBahan'])->name('keb-bahan.list');
    Route::post('/kebbahan/store', [KebutuhanBahanController::class, 'store'])->name('keb-bahan.store');
    Route::post('/kebbahan/destroy', [KebutuhanBahanController::class, 'destroy'])->name("keb-bahan.destroy");
    Route::post('/kebbahan/update_jumlah_ajuan',[KebutuhanBahanController::class, 'update_jumlah_ajuan'])->name("keb-bahan.update_jumlah_ajuan");

    Route::get('/user', [UserController::class, 'index'])->name("user");
    Route::get('/user/list', [UserController::class, 'getListOfUser'])->name('user.list');
    Route::get('/user/create', [UserController::class, 'create']);
    Route::post('/user/store', [UserController::class, 'store'])->name("user.store");
    Route::get('/user/destroy/{id}', [UserController::class, 'destroy']);
    Route::get('/user/show/{id}',[UserController::class,'show'])->name('user.show');
    Route::get('/user/laboratorium/{id}',[UserController::class,'laboratorium'])->name('user.laboratorium');
    Route::get('/user/list/{id}',[UserController::class,'getListOfUserLab'])->name('user-lab.list');
    Route::post('/user/store_lab',[UserController::class,'storeLab'])->name('user-lab.store_lab');
    Route::post('user/destroy_lab',[UserController::class, 'destroyLab'])->name('user-lab.destroy_lab');
    Route::post('/user/change_lab',[UserController::class,'changeLab'])->name('user-lab.change_lab');

    Route::get('/lab/index/{tahun?}', [LaboratoriumController::class, 'index'])->name("lab");
    Route::get('/lab/list_alat/{tahun}', [LaboratoriumController::class, 'getListOfKebAlatLab'])->name('keb-alat-lab.list');
    Route::post('/lab/update_jumlah_ajuan_alat',[LaboratoriumController::class, 'update_jumlah_ajuan_alat'])->name("keb-alat-lab.update_jumlah_ajuan_alat");
    Route::post('/lab/setujui',[LaboratoriumController::class, 'setujui'])->name("keb-lab.setujui");
    Route::get('/lab/list_bahan/{tahun}', [LaboratoriumController::class, 'getListOfKebBahanLab'])->name('keb-bahan-lab.list');
    Route::post('/lab/update_jumlah_ajuan_bahan',[LaboratoriumController::class, 'update_jumlah_ajuan_bahan'])->name("keb-alat-lab.update_jumlah_ajuan_bahan");
    Route::get('/lab/export/{tahun?}', [LaboratoriumController::class, 'Export'])->name("keb-lab.export");
});