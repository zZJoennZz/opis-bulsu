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
use App\Http\Controllers\YearController;
use App\Http\Controllers\ItemPurposeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SourceofFundsController;
use App\Http\Controllers\ConsolidateController;

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

    Route::put('/update-year', [YearController::class, 'update_year'])->name('update-year.perform');

    Route::delete('/delete-ppmp-record/{ppmp_id}', [CartController::class, 'delete_from_cart'])->name('delete-ppmp.perform');

   
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

    Route::post('/notifications/batch', [NotificationController::class, 'acknowledge_batch'])->name('notification.acknowledge_batch');
});

//AVAILABLE TO PROCUREMENT OFFICE
Route::middleware('procurement.office')->group(function () {
    Route::get('/po-dashboard', [DashboardController::class, 'show'])->name('po-dashboard.show');

    Route::get('/consolidate', [ConsolidateController::class, 'index'])->name('consolidated.show');
    Route::post('/consolidate', [ConsolidateController::class, 'consolidate'])->name('consolidate.perform');
    Route::post('/consolidate/reset', [ConsolidateController::class, 'reset_consolidation'])->name('consolidate.reset');

    Route::get('/previous-ppmp/{branch_id}', [PPMPController::class, 'previous_ppmp'])->name('previous-ppmp.show');
    Route::get('/previous-ppmp/{branch_id}/{year}', [PPMPController::class, 'previous_ppmp_open'])->name('previous-ppmp-single.show');

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
    Route::get('/item-details/pending', [ItemDetailController::class, 'pending_items'])->name('pending-item-detail.show');
    Route::get('/item-details/pending/{item_detail_id}', [ItemDetailController::class, 'view_pending_item'])->name('pending-item-detail.single');
    Route::put('/item-details/pending/approve/{item_details_id}', [ItemDetailController::class, 'approve_pending_update'])->name('approve-pending-item.perform');

    Route::get('/item-purpose', [ItemPurposeController::class, 'all'])->name('item-purpose.all');
    Route::post('/item-purpose', [ItemPurposeController::class, 'add'])->name('item-purpose.add');
    Route::get('/item-purpose/{purpose_id}', [ItemPurposeController::class, 'get'])->name('item-purpose.single');
    Route::put('/item-purpose/{purpose_id}', [ItemPurposeController::class, 'update'])->name('item-purpose.update');
    Route::delete('/item-purpose/single/{purpose_id}', [ItemPurposeController::class, 'delete_single'])->name('item-purpose.delete');
    Route::post('/item-purpose/batch', [ItemPurposeController::class, 'delete_batch'])->name('item-purpose.delete_batch');

    //position
    Route::get('/position', [PositionController::class, 'all'])->name('positions.all');
    Route::post('/position', [PositionController::class, 'add'])->name('position.add');
    Route::get('/position/{position_id}', [PositionController::class, 'get'])->name('position.single');
    Route::put('/position/{position_id}', [PositionController::class, 'update'])->name('position.update');
    Route::delete('/position/single/{purpose_id}', [PositionController::class, 'delete_single'])->name('position.delete');
    Route::post('/position/batch', [PositionController::class, 'delete_batch'])->name('position.delete_batch');

    //manage unit
    Route::get('/unit', [UnitController::class, 'all'])->name('units.all');
    Route::post('/unit', [UnitController::class, 'add'])->name('unit.add');
    Route::get('/unit/{unit_id}', [UnitController::class, 'get'])->name('unit.single');
    Route::put('/unit/{unit_id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('/unit/single/{unit_id}', [UnitController::class, 'delete_single'])->name('unit.delete');
    Route::post('/unit/batch', [UnitController::class, 'delete_batch'])->name('unit.delete_batch');

    // source of funds
    Route::get('/source-of-fund', [SourceofFundsController::class, 'all'])->name('source-of-fund.all');
    Route::post('/source-of-fund', [SourceofFundsController::class, 'add'])->name('source-of-fund.add');
    Route::get('/source-of-fund/{source_of_fund_id}', [SourceofFundsController::class, 'get'])->name('source-of-fund.single');
    Route::put('/source-of-fund/{source_of_fund_id}', [SourceofFundsController::class, 'update'])->name('source-of-fund.update');
    Route::delete('/source-of-fund/single/{source_of_fund_id}', [SourceofFundsController::class, 'delete_single'])->name('source-of-fund.delete');
    Route::post('/source-of-fund/batch', [SourceofFundsController::class, 'delete_batch'])->name('source-of-fund.delete_batch');

    // Branch
    Route::get('/branch', [BranchController::class, 'all'])->name('branch.all');
    Route::post('/branch', [BranchController::class, 'add'])->name('branch.add');
    Route::get('/branch/{branch_id}', [BranchController::class, 'get'])->name('branch.single');
    Route::put('/branch/{branch_id}', [BranchController::class, 'update'])->name('branch.update');
    Route::delete('/branch/single/{branch_id}', [BranchController::class, 'delete_single'])->name('branch.delete');
    Route::post('/branch/batch', [BranchController::class, 'delete_batch'])->name('branch.delete_batch');


    Route::get('/users', [UserController::class, 'index'])->name('users-list.show');
    Route::get('/users/add', [UserController::class, 'add'])->name('add-new-user.show');
    Route::post('/users/add', [UserController::class, 'save_new'])->name('add-new-user.perform');
    Route::get('/users/{user_id}', [UserController::class, 'show'])->name('view-user.show');
    Route::put('/users', [UserController::class, 'update'])->name('view-user.update');

    // Manage user approval
    Route::get('/users/update-status/{id}/{st}', [UserController::class, 'status_manage'])->name('status.manage');

    

    Route::post('/approve-item-detail/{item_detail_id}', [ItemDetailController::class, 'approve_item_detail'])->name('item-detail-review-approve.perform');
    Route::delete('/delete-item-detail/{item_detail_id}', [ItemDetailController::class, 'delete_item_detail'])->name('item-detail.delete');
});

//ADMIN ONLY
Route::middleware('admin')->group(function () {
    Route::get('/admin-dashboard', [DashboardController::class, 'show'])->name('admin-dashboard.show');
});
