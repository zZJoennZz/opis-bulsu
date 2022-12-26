<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\PPMPController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\UserController;

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

//ONLY AVAILABLE TO PUBLIC
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'show'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.perform');

    Route::get('forgot-password', [AuthController::class, 'show_forgot_password_form'])->name('forgot-password.show');
    Route::post('forgot-password', [AuthController::class, 'submit_forgot_password'])->name('forgot-password.submit');
    Route::get('reset-password/{token}', [AuthController::class, 'show_reset_password_form'])->name('reset-password.show');
    Route::post('reset-password', [AuthController::class, 'submit_reset_password_form'])->name('reset-password.submit');
});

//AVAILABLE TO EVERYONE WHO ARE LOGGED IN
Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.perform');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notification.show');
    Route::get('/notifications/{notif_id}', [NotificationController::class, 'read'])->name('notification.read');

    Route::get('/ppmp-update-activity-log/{branch_id}', [PPMPController::class, 'ppmp_activity_log'])->name('ppmp-activity-log.show');
    Route::get('/update-ppmp-record/{ppmp_id}', [PPMPController::class, 'get_ppmp_record'])->name('get-ppmp-record.show');
    Route::post('/update-ppmp-record/{ppmp_id}', [PPMPController::class, 'update_ppmp'])->name('update-ppmp-record.perform');

    Route::get('/add-new-item-detail', [ItemDetailController::class, 'new_item_detail'])->name('add-new-item.show');
    Route::post('/add-new-item-detail', [ItemDetailController::class, 'submit_item_detail'])->name('add-new-item.perform');

    Route::get('/view-item-detail/{item_detail_id}', [ItemDetailController::class, 'get_item_detail'])->name('view-item-detail.show');
    Route::post('/view-item-detail/{item_detail_id}', [ItemDetailController::class, 'update_item_detail'])->name('view-item-detail.update');
});

Route::middleware(['procurement.office', 'admin'])->group(function () {
    Route::post('/approve-item-detail/{item_detail_id}', [ItemDetailController::class, 'approve_item_detail'])->name('item-detail-review-approve.perform');
    Route::delete('/delete-item-detail/{item_detail_id}', [ItemDetailController::class, 'delete_item_detail'])->name('item-detail.delete');
});

//AVAILABLE TO END USERS
Route::middleware('end.user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard.show');
    //items
    Route::get('/item-detail/{id}', [ItemDetailController::class, 'show'])->name('item-detail-single.show');
    Route::post('/item-detail/{id}', [ItemDetailController::class, 'store'])->name('item-detail-single.add');

    //cart
    Route::get('/ppmp-cart', [CartController::class, 'get'])->name('ppmp-cart.get');
    Route::post('/ppmp-cart', [CartController::class, 'submit'])->name('ppmp-cart.submit');

    //PPMP
    Route::get('/ppmp-request', [PPMPController::class, 'get'])->name('ppmp-request.get');
});

//AVAILABLE TO BUDGET OFFICE
Route::middleware('budget.office')->group(function () {
    Route::get('/bo-dashboard', [DashboardController::class, 'show'])->name('bo-dashboard.show');

    Route::get('/new-ppmp-request/{branch_id}', [PPMPController::class, 'new_ppmp_request'])->name('bo-new-ppmp-request.show');
    Route::post('/new-ppmp-request', [PPMPController::class, 'approve_ppmp_request'])->name('bo-approve-ppmp-request.perform');

    Route::get('/approved-ppmp-request/{branch_id}', [PPMPController::class, 'approved_ppmp_request'])->name('approved-ppmp-request.show');
    Route::post('/send-back-ppmp-request/{user_id}', [PPMPController::class, 'send_back'])->name('send-bank-ppmp.perform');
});

//AVAILABLE TO PROCUREMENT OFFICE
Route::middleware('procurement.office')->group(function () {
    Route::get('/po-dashboard', [DashboardController::class, 'show'])->name('po-dashboard.show');

    Route::get('/ppmp-approval/{branch_id}', [PPMPController::class, 'ppmp_approval'])->name('po-ppmp-approval.show');
    Route::get('/approved-ppmp/{branch_id}', [PPMPController::class, 'po_approved_ppmp'])->name('po-approved-ppmp.show');
    Route::post('/ppmp-approval', [PPMPController::class, 'po_approve_ppmp'])->name('po-approve-ppmp-approval.perform');

    Route::post('/send-back-ppmp-approval/{user_id}', [PPMPController::class, 'po_send_back'])->name('send-back-ppmp-approval.perform');

    Route::get('/item-categories', [ItemCategoryController::class, 'all'])->name('item-cat.show');
    Route::post('/item-categories', [ItemCategoryController::class, 'add'])->name('item-cat.add');
    Route::get('/item-categories/{category_id}', [ItemCategoryController::class, 'get'])->name('item-cat.single');
    Route::put('/item-categories/{category_id}', [ItemCategoryController::class, 'update'])->name('item-cat.update');
    Route::delete('/item-categories/single/{category_id}', [ItemCategoryController::class, 'delete_single'])->name('item-cat.delete');
    Route::post('/item-categories/batch', [ItemCategoryController::class, 'delete_batch'])->name('item-cat.delete_batch');

    Route::get('/item-details', [ItemDetailController::class, 'all'])->name('item-detail-list.all');
    Route::delete('/item-details/single/{item_detail_id}', [ItemDetailController::class, 'delete'])->name('item-detail-list.delete');
    Route::post('/item-details/batch', [ItemDetailController::class, 'delete_batch'])->name('item-detail-list.delete_batch');

    Route::get('/users', [UserController::class, 'index'])->name('users-list.show');
    Route::get('/users/add', [UserController::class, 'add'])->name('add-new-user.show');
    Route::post('/users/add', [UserController::class, 'save_new'])->name('add-new-user.perform');
    Route::get('/users/{user_id}', [UserController::class, 'show'])->name('view-user.show');
    Route::put('/users', [UserController::class, 'update'])->name('view-user.update');
});

//ADMIN ONLY
Route::middleware('admin')->group(function () {
    Route::get('/admin-dashboard', [DashboardController::class, 'show'])->name('admin-dashboard.show');
});
