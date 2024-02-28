<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('guest')->group(function(){
	Route::get('/login',[AuthController::class, 'showLoginForm'])->name('login');
	Route::post('/login',[AuthController::class, 'authenticate'])->name('login.process');
	Route::get('/register',[AuthController::class, 'register_index'])->name('register');
	Route::post('/register',[AuthController::class, 'register'])->name('register.process');

});


Route::middleware('auth')->group(function(){
	Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
    Route::name('home.')->prefix('home')->group(function () {
		Route::get('/', 'DashboardController@index')->name('index');
		Route::get('/get', 'DashboardController@getData')->name('GetData');
	});
	Route::get('buku/data', 'BukuController@getData')->name('buku.getData');
	Route::get('buku/detail', 'BukuController@detail')->name('buku.detail');

    Route::name('buku.')->prefix('buku')->middleware('can:manage buku')->group(function () {
		Route::get('/', 'BukuController@index')->name('index');
		Route::get('/tables', 'BukuController@tables')->name('tables');
		Route::post('/', 'BukuController@store')->name('store');
		Route::put('/', 'BukuController@update')->name('update');
		Route::delete('/', 'BukuController@delete')->name('delete');
	});

    Route::name('kategori.')->prefix('kategori')->middleware('can:manage kategori')->group(function () {
		Route::get('/', 'KategoriController@index')->name('index');
		Route::get('/tables', 'KategoriController@tables')->name('tables');
		Route::post('/', 'KategoriController@store')->name('store');
		Route::put('/', 'KategoriController@update')->name('update');
		Route::delete('/', 'KategoriController@delete')->name('delete');
		Route::get('/{bukuId}', 'KategoriController@getkategori')->name('getkategori');
		Route::post('/{bukuId}', 'KategoriController@updateKategori')->name('updateKategori');
	});

    Route::name('peminjaman.')->prefix('peminjaman')->group(function () {
		Route::get('/', 'PeminjamanController@index')->name('index');
		Route::get('/tables', 'PeminjamanController@tables')->name('tables');
		Route::post('/', 'PeminjamanController@store')->name('store');
		Route::put('/', 'PeminjamanController@update')->name('update');
		Route::delete('/', 'PeminjamanController@delete')->name('delete');
	});

    Route::name('ulasan.')->prefix('ulasan')->middleware('can:manage ulasan')->group(function () {
		Route::get('/', 'UlasanController@index')->name('index');
		Route::get('/tables', 'UlasanController@tables')->name('tables');
		Route::post('/', 'UlasanController@store')->name('store');
		Route::put('/', 'UlasanController@update')->name('update');
		Route::delete('/', 'UlasanController@delete')->name('delete');
		Route::get('/check', 'UlasanController@checkIfExist')->name('check');
		Route::get('/rate', 'UlasanController@getRate')->name('rate');
	});

    Route::name('koleksi.')->prefix('koleksi')->middleware('can:manage koleksi')->group(function () {
		Route::get('/', 'KoleksiController@index')->name('index');
		Route::get('/tables', 'KoleksiController@tables')->name('tables');
		Route::post('/', 'KoleksiController@store')->name('store');
		Route::put('/', 'KoleksiController@update')->name('update');
		Route::delete('/', 'KoleksiController@delete')->name('delete');
		Route::get('/check', 'KoleksiController@checkIfExist')->name('check');
	});

	Route::name('role-pengguna.')->prefix('role-pengguna')->middleware('can:manage permissions')->group(function () {
		Route::get('/', 'RoleController@index')->name('index');
		Route::get('/permissions/{roleId}', 'RoleController@getRolePermissions')->name('getRolePermissions');
		Route::post('/permissions/{roleId}', 'RoleController@updatePerms')->name('updatePerms');
		Route::get('/tables', 'RoleController@tables')->name('tables');
		Route::post('/', 'RoleController@store')->name('store');
		Route::put('/', 'RoleController@update')->name('update');
		Route::delete('/', 'RoleController@destroy')->name('delete');
	});
	Route::name('admin-user.')->prefix('admin-user')->middleware('can:manage user')->group(function () {
		Route::get('/', 'AdminUserController@index')->name('index');
		Route::get('/tables', 'AdminUserController@tables')->name('tables');
		Route::post('/', 'AdminUserController@store')->name('store');
		Route::patch('/', 'AdminUserController@changePassword')->name('change-password');
		Route::put('/', 'AdminUserController@update')->name('update');
		Route::delete('/', 'AdminUserController@delete')->name('delete');
	});
});
