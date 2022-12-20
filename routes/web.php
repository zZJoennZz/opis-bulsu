<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemDetailController;

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

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'show'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.perform');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.perform');

    //items
    Route::get('/item-detail/{id}', [ItemDetailController::class, 'show'])->name('item-detail-single.show');
    Route::post('/item-detail/{id}', [ItemDetailController::class, 'store'])->name('item-detail-single.add');
});


Route::get('/test1', [AuthController::class, 'add_user'])->name('test.add');
