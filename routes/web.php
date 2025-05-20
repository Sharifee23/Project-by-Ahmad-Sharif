<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\RoleController;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', [ChartController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('products', [HomeController::class, 'productView'])->middleware(['auth', 'verified'])->name('products');
Route::post('/add_product', [HomeController::class, 'addProduct'])->middleware(['auth', 'verified'])->name('add_product');
Route::post('update_product/{id}', [HomeController::class, 'update_product'])->middleware(['auth', 'verified']);
Route::get('/delete_product/{id}', [HomeController::class, 'delete_product'])->middleware(['auth', 'verified']);
Route::post('/store', [HomeController::class, 'store'])->middleware(['auth', 'verified'])->name('store');
Route::get('/product', [HomeController::class, 'product'])->middleware(['auth', 'verified'])->name('product');
Route::get('/getall', [HomeController::class, 'getall'])->middleware(['auth', 'verified'])->name('getall');
//Route::get('/product/{id}/edit', [HomeController::class, 'edit'])->name('edit');
Route::post('/update', [HomeController::class, 'update'])->middleware(['auth', 'verified'])->name('update');
Route::delete('/product/delete', [HomeController::class, 'delete'])->middleware(['auth', 'verified'])->name('delete');
Route::get('/view/{id}', [HomeController::class, 'show_product'])->middleware(['auth', 'verified'])->name('view.item');
Route::get('/getallprecords/{id}', [HomeController::class, 'getAllPRecords'])->middleware(['auth', 'verified'])->name('getallprecords');


Route::post('/add_states', [HomeController::class, 'add_states'])->middleware(['auth', 'verified'])->name('add_states');
Route::get('/states', [HomeController::class, 'states'])->middleware(['auth', 'verified'])->name('states');
Route::get('/getallstates', [HomeController::class, 'getallstates'])->middleware(['auth', 'verified'])->name('getallstates');
Route::post('/update_states', [HomeController::class, 'update_states'])->middleware(['auth', 'verified'])->name('update_states');
Route::delete('/delete_states', [HomeController::class, 'delete_states'])->middleware(['auth', 'verified'])->name('delete_states');

Route::get('/markets', [HomeController::class, 'markets'])->middleware(['auth', 'verified'])->name('markets');
Route::post('/add_markets', [HomeController::class, 'add_markets'])->middleware(['auth', 'verified'])->name('add_markets');
Route::get('/getallmarkets', [HomeController::class, 'getallmarkets'])->middleware(['auth', 'verified'])->name('getallmarkets');
Route::post('/update_markets', [HomeController::class, 'update_markets'])->middleware(['auth', 'verified'])->name('update_markets');
Route::delete('/delete_markets', [HomeController::class, 'delete_markets'])->middleware(['auth', 'verified'])->name('delete_markets');

Route::get('/prices', [HomeController::class, 'prices'])->middleware(['auth', 'verified'])->name('prices');
Route::get('/records/add', [HomeController::class, 'addRecord'])->middleware(['auth', 'verified'])->name('records/add');

Route::post('/add-records', [HomeController::class, 'store_record'])->middleware(['auth', 'verified'])->name('records.store');
Route::post('/addToPrices', [HomeController::class, 'addToPrices'])->middleware(['auth', 'verified'])->name('addToPrices');
Route::get('/getallrecords', [HomeController::class, 'getAllRecords'])->middleware(['auth', 'verified'])->name('getallrecords');
Route::get('/getallpendingrecords', [HomeController::class, 'getAllPendingRecords'])->middleware(['auth', 'verified'])->name('getallpendingrecords');
Route::post('/update_records', [HomeController::class, 'update_records'])->middleware(['auth', 'verified'])->name('updateRecord');
Route::delete('/delete_records', [HomeController::class, 'delete_records'])->middleware(['auth', 'verified'])->name('delete_records');


Route::get('/users', [HomeController::class, 'users'])->middleware(['auth', 'verified'])->name('users');
Route::post('/adduser', [HomeController::class, 'addUser'])->middleware(['auth', 'verified'])->name('add.user');
Route::get('/getAllUsers', [HomeController::class, 'getAllUsers'])->middleware(['auth', 'verified'])->name('getallusers');
Route::get('/editUser/{id}', [HomeController::class, 'editUser'])->middleware(['auth', 'verified'])->name('edit.user');
Route::put('/updateUser/{id}', [HomeController::class, 'updateUser'])->middleware(['auth', 'verified'])->name('update.user');
Route::get('/viewUser/{id}', [HomeController::class, 'viewUser'])->middleware(['auth', 'verified'])->name('view.user');
Route::delete('/deleteUser', [HomeController::class, 'deleteUser'])->middleware(['auth', 'verified'])->name('deleteUser');
Route::get('/activities', [HomeController::class, 'activities'])->middleware(['auth', 'verified'])->name('user.activities');
Route::delete('/deleteUser', [HomeController::class, 'deleteUser'])->middleware(['auth', 'verified'])->name('delete.user');


Route::get('/getActivities', [HomeController::class, 'getActivities'])->middleware(['auth', 'verified'])->name('getactivities');

Route::get('/getUserActivities/{id}', [HomeController::class, 'getUserActivities'])->middleware(['auth', 'verified'])->name('getuseractivities');



Route::get('/chart', [ChartController::class, 'index'])->middleware(['auth', 'verified']);
Route::get('/get-markets', [ChartController::class, 'getMarkets'])->middleware(['auth', 'verified']);
Route::get('/get-chart-data', [ChartController::class, 'getChartData'])->middleware(['auth', 'verified']);
Route::get('/chart/{id}', [ChartController::class, 'show_product'])->middleware(['auth', 'verified']);
Route::get('/get-markets/{id}', [ChartController::class, 'getProdMarkets'])->middleware(['auth', 'verified']);
Route::get('/get-chart-data/{id}', [ChartController::class, 'getProdChart'])->middleware(['auth', 'verified']);

Route::get('/product-prices/{id}', [HomeController::class, 'getProductPrices'])->middleware(['auth', 'verified']);

Route::resource("roles", RoleController::class)->middleware(['auth', 'verified']);






