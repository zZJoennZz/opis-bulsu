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
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BacResoController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InspectionAndAcceptanceController;
use App\Http\Controllers\SupplyEndUserController;
use App\Http\Controllers\SupplyEmployeeController;
use App\Http\Controllers\AbstractOfCanvassController;
use App\Http\Controllers\AllotAndOblSlipController;
use App\Http\Controllers\SupplyPositionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ModeOfProcurementController;

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

//
Route::get('/test', function () {
    return view('test');
});

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
    Route::get('/print-ppmp-update-activity-log/{branch_id}', [PPMPController::class, 'print_activity_log'])->name('ppmp-activity-log.print');
    Route::get('/update-ppmp-record/{ppmp_id}', [PPMPController::class, 'get_ppmp_record'])->name('get-ppmp-record.show');
    Route::post('/update-ppmp-record/{ppmp_id}', [PPMPController::class, 'update_ppmp'])->name('update-ppmp-record.perform');

    Route::put('/update-year', [YearController::class, 'update_year'])->name('update-year.perform');

    Route::delete('/delete-ppmp-record/{ppmp_id}', [CartController::class, 'delete_from_cart'])->name('delete-ppmp.perform');

    Route::get('/account-settings', [UserController::class, 'account_settings'])->name('account-settings.show');
    Route::post('/account-settings', [UserController::class, 'change_user_details'])->name('account-settings.save');
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

    //purchase request list
    Route::get('/purchase-requests-list', [PurchaseRequestController::class, 'pr_list'])->name('pr-list.show');
    Route::get('/purchase-requests-list/{pr_id?}', [PurchaseRequestController::class, 'print'])->name('pr-print.user');
    Route::get('/purchase-request-form', [PurchaseRequestController::class, 'pr_form'])->name('pr-form.show');
    Route::get('/available-items-for-pr', [PurchaseRequestController::class, 'pr_available_items_api'])->name('pr-items.show');
    Route::post('/new-purchase-request', [PurchaseRequestController::class, 'new_submission'])->name('new-pr.perform');
    Route::get('/pr-api/{pr_id?}', [PurchaseRequestController::class, 'pr_single_user'])->name('get-pr-user.single.api');
});

//AVAILABLE TO BUDGET OFFICE
Route::middleware('budget.office')->group(function () {
    Route::get('/bo-dashboard', [DashboardController::class, 'show'])->name('bo-dashboard.show');

    Route::get('/new-ppmp-request/{branch_id}', [PPMPController::class, 'new_ppmp_request'])->name('bo-new-ppmp-request.show');
    Route::put('/new-ppmp-request', [PPMPController::class, 'approve_ppmp_request'])->name('bo-approve-ppmp-request.perform');

    Route::get('/approved-ppmp-request/{branch_id}', [PPMPController::class, 'approved_ppmp_request'])->name('approved-ppmp-request.show');
    Route::post('/send-back-ppmp-request/{user_id}', [PPMPController::class, 'send_back'])->name('send-bank-ppmp.perform');

    Route::get('/due-date', [SettingController::class, 'budget_setting'])->name('due-date.show');
    Route::put('/due-date', [SettingController::class, 'budget_save_setting'])->name('due-date.update');

    Route::post('/notifications/batch', [NotificationController::class, 'acknowledge_batch'])->name('notification.acknowledge_batch');
});

//AVAIL TO PROCUREMENT OFFICE HEAD
Route::middleware('procurement.head')->group(function () {
    Route::get('/ph-dashboard', [DashboardController::class, 'show'])->name('ph-dashboard.show');

    Route::get('/item-details/pending', [ItemDetailController::class, 'pending_items'])->name('pending-item-detail.show');
    Route::get('/item-details/pending/{item_detail_id}', [ItemDetailController::class, 'view_pending_item'])->name('pending-item-detail.single');
    Route::put('/item-details/pending/approve/{item_details_id}', [ItemDetailController::class, 'approve_pending_update'])->name('approve-pending-item.perform');
    Route::delete('/item-details/single/{item_detail_id}', [ItemDetailController::class, 'delete'])->name('item-detail-list.delete');
    Route::post('/item-details/batch', [ItemDetailController::class, 'delete_batch'])->name('item-detail-list.delete_batch');
});

//AVAILABLE TO PROCUREMENT OFFICE
Route::middleware('procurement.office')->group(function () {
    Route::get('/po-dashboard', [DashboardController::class, 'show'])->name('po-dashboard.show');
    Route::get('/print-unsubmitted-ppmp', [DashboardController::class, 'print_unsub_ppmp'])->name('unsub-ppmp');

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

    Route::get('/add-new-item-detail', [ItemDetailController::class, 'new_item_detail'])->name('add-new-item.show');
    Route::post('/add-new-item-detail', [ItemDetailController::class, 'submit_item_detail'])->name('add-new-item.perform');

    Route::get('/view-item-detail/{item_detail_id}', [ItemDetailController::class, 'get_item_detail'])->name('view-item-detail.show');
    Route::post('/view-item-detail/{item_detail_id}', [ItemDetailController::class, 'update_item_detail'])->name('view-item-detail.update');

    Route::get('/item-details', [ItemDetailController::class, 'all'])->name('item-detail-list.all');

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

    //Purchase request
    Route::get('/purchase-request', [PurchaseRequestController::class, 'pr_admin'])->name('pr-admin.show');
    Route::post('/toggle-purchase-request', [PurchaseRequestController::class, 'toggle_pr_mode'])->name('pr.toggle');
    Route::get('/purchase-request/api/{pr_id?}', [PurchaseRequestController::class, 'pr_single'])->name('pr-single.api');
    Route::get('/purchase-request/{pr_id?}', [PurchaseRequestController::class, 'print'])->name('pr.get');
    Route::get('/purchase-request-quotation/{pr_id?}/{company_id?}', [PurchaseRequestController::class, 'pr_single_quotation'])->name('pr-single-quotation.api');
    Route::post('/purchase-request/{pr_id?}', [PurchaseRequestController::class, 'approve_pr'])->name('pr-approve.api');

    //companies
    Route::get('/company-profiles', [CompanyController::class, 'all'])->name('company.all');
    Route::post('/company-profiles', [CompanyController::class, 'add'])->name('company.add');
    Route::get('/company-profiles/{company_id?}', [CompanyController::class, 'single_api'])->name('company.single.api');
    Route::put('/company-profiles/{company_id?}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/company-profiles/{company_id?}', [CompanyController::class, 'toggleDelete'])->name('company.delete');
    Route::get('/company-profiles/update/{id}/{isChecked}', [CompanyController::class, 'status_change'])->name('status.change');
    Route::get('/company-profiles/bac-reso/{company_id?}', [CompanyController::class, 'get_company_by_bac_reso'])->name('company-bac.get');

    //price quotations
    Route::get('/quotations', [QuotationController::class, 'all'])->name('quotation.all');
    Route::get('/quotations/add', [QuotationController::class, 'add'])->name('quotation.add');
    Route::post('/quotations/add', [QuotationController::class, 'new_request'])->name('quotation.new');
    Route::get('/quotations/single/{quotation_id?}', [QuotationController::class, 'get_single'])->name('quotation.single.api');
    Route::get('/quotations/summary', [QuotationController::class, 'get_summary'])->name('quotation.summary');
    Route::get('/company-quotations/{company_id?}', [QuotationController::class, 'get_company_quotations'])->name('company-quotation.single');
    Route::get('/quotations/comparison/{pr_id?}', [QuotationController::class, 'get_item_for_comparison'])->name('quotation-comparison.single');

    //abstract of canvass pages
    Route::get('/abstract-of-canvass', [AbstractOfCanvassController::class, 'all'])->name('aoc.all');
    Route::get('/abstract-of-canvass/add', [AbstractOfCanvassController::class, 'add'])->name('aoc.add');
    Route::post('/abstract-of-canvass/save', [AbstractOfCanvassController::class, 'save'])->name('aoc.perform');
    Route::get('/abstract-of-canvass/{id?}', [AbstractOfCanvassController::class, 'single'])->name('aoc.single');
    Route::put('/abstract-of-canvass/{id?}', [AbstractOfCanvassController::class, 'complete_aoc'])->name('aoc.complete');
    Route::get('/abstract-of-canvass/print/{id?}', [AbstractOfCanvassController::class, 'print'])->name('aoc.print');
    Route::delete('/abstract-of-canvass/delete/{id?}', [AbstractOfCanvassController::class, 'delete'])->name('aoc.delete');

    //BAC reso
    Route::get('/bac-reso', [BacResoController::class, 'all'])->name('bac-reso.all');
    Route::get('/bac-reso/add', [BacResoController::class, 'add'])->name('bac-reso.add');
    Route::post('/bac-reso/add', [BacResoController::class, 'save'])->name('bac-reso.save');
    Route::get('/bac-reso/{id?}', [BacResoController::class, 'single'])->name('bac-reso.single');
    Route::get('/bac-reso-print-by-item/{id?}', [BacResoController::class, 'print_by_item'])->name('bac-reso.print-by-item');
    Route::get('/bac-reso-print-by-lot/{id?}', [BacResoController::class, 'print_by_lot'])->name('bac-reso.print-by-lot');
    Route::get('/bac-reso/compare/{pr_item_id?}', [BacResoController::class, 'get_quotations_by_pr'])->name('bac-reso.compare');
    Route::post('/bac-reso/item/add', [BacResoController::class, 'add_bac_reso_item'])->name('bac-reso-item.new');
    Route::delete('/bac-reso/{bac_reso_item_id?}', [BacResoController::class, 'remove_bac_reso_item'])->name('bac-reso.delete');
    Route::delete('/bac-reso/delete-by-lot/del', [BacResoController::class, 'remove_items_by_lot'])->name('bac-reso.delete-batch');
    Route::put('/bac-reso/complete', [BacResoController::class, 'complete_bac_reso'])->name('bac-reso.complete');
    Route::get('/bac-reso/single/{bac_reso_id?}/{company_id?}', [BacResoController::class, 'get_single'])->name('bac-reso.by-id');

    //purchase order
    Route::get('/purchase-order', [PurchaseOrderController::class, 'get_all'])->name('po.all');
    Route::get('/purchase-order/add', [PurchaseOrderController::class, 'add_new'])->name('po.add');
    Route::post('/purchase-order/add', [PurchaseOrderController::class, 'generate_po'])->name('po.perform');
    Route::get('/purchase-order/view/{po_id?}', [PurchaseOrderController::class, 'view_po'])->name('po.single');
    Route::delete('/purchase-order/{id?}', [PurchaseOrderController::class, 'delete'])->name('po.delete');

    //inspection and acceptance
    Route::get('/inspection-and-acceptance', [InspectionAndAcceptanceController::class, 'all'])->name('ia.all');
    Route::get('/inspection-and-acceptance/add', [InspectionAndAcceptanceController::class, 'add_new'])->name('ia.add');
    Route::post('/inspection-and-acceptance/add', [InspectionAndAcceptanceController::class, 'post_new'])->name('ia.post');
    Route::get('/inspection-and-acceptance/print/{ia_id?}', [InspectionAndAcceptanceController::class, 'view_single'])->name('ia.single');
    Route::get('/inspection-and-acceptance/view/{id?}', [InspectionAndAcceptanceController::class, 'single'])->name('iaa.single');
    Route::put('/inspection-and-acceptance/update/{id?}', [InspectionAndAcceptanceController::class, 'complete_iaa'])->name('iaa.put');

    //ALOBS
    Route::get('/alobs', [AllotAndOblSlipController::class, 'all'])->name('alobs.all');
    Route::get('/alobs/{id?}', [AllotAndOblSlipController::class, 'view'])->name('alobs.single');
    Route::put('/alobs/{id?}', [AllotAndOblSlipController::class, 'update'])->name('alobs.update');
    Route::get('/alobs/print/{id?}', [AllotAndOblSlipController::class, 'print'])->name('alobs.print');

    //settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'save_changes'])->name('settings.update');

    //ModeofProcurementController
    Route::get('/mode-of-procurement', [ModeOfProcurementController::class, 'all'])->name('mode-procurement.all');
    Route::post('/mode-of-procurement', [ModeOfProcurementController::class, 'add'])->name('mode-procurement.add');
    Route::get('/mode-of-procurement/{modeprocurement_id}', [ModeOfProcurementController::class, 'get'])->name('mode-procurement.single');
    Route::put('/mode-of-procurement/{modeprocurement_id}', [ModeOfProcurementController::class, 'update'])->name('mode-procurement.update');
    Route::delete('/mode-of-procurement/single/{modeprocurement_id}', [ModeOfProcurementController::class, 'delete_single'])->name('mode-procurement.delete');
    Route::post('/mode-of-procurement/batch', [ModeOfProcurementController::class, 'delete_batch'])->name('mode-procurement.delete_batch');
});

Route::middleware('supply.office')->group(function () {
    Route::get('/so-dashboard', [DashboardController::class, 'show'])->name('so-dashboard.show');

    //api
    Route::get('/purchase-order/api/{iar_id?}', [PurchaseOrderController::class, 'get_by_iar'])->name('po_by_iar.get');
    Route::get('/bac_reso_item/{id?}', [BacResoController::class, 'get_single_by_id'])->name('bac_reso_item.get');

    //manage end users
    Route::get('/manage-end-user', [SupplyEndUserController::class, 'all'])->name('supply-end-user.all');
    Route::post('/manage-end-user', [SupplyEndUserController::class, 'post_add'])->name('supply-end-user.post_add');
    Route::get('/manage-end-user/{enduser_id}', [SupplyEndUserController::class, 'get'])->name('enduser.single');
    Route::put('/manage-end-user/{enduser_id}', [SupplyEndUserController::class, 'update']);
    Route::delete('/manage-end-user/single/{enduser_id}', [SupplyEndUserController::class, 'delete_single']);
    Route::post('/manage-end-user/batch', [SupplyEndUserController::class, 'delete_batch'])->name('enduser.delete_batch');

    //manage supply employee
    Route::get('/manage-supply-employee', [SupplyEmployeeController::class, 'all'])->name('supply-employee.all');
    Route::post('/manage-supply-employee', [SupplyEmployeeController::class, 'post_add'])->name('supply-employee.post_add');
    Route::get('/manage-supply-employee/{enduser_id}', [SupplyEmployeeController::class, 'get'])->name('supplyemployee.single');
    Route::put('/manage-supply-employee/{enduser_id}', [SupplyEmployeeController::class, 'update']);
    Route::delete('/manage-supply-employee/single/{enduser_id}', [SupplyEmployeeController::class, 'delete_single']);
    Route::post('/manage-supply-employee/batch', [SupplyEmployeeController::class, 'delete_batch'])->name('supplyemployee.delete_batch');

    //manage supply position
    Route::get('/manage-supply-position', [SupplyPositionController::class, 'all'])->name('supply-position.all');
    Route::post('/manage-supply-position', [SupplyPositionController::class, 'post_add'])->name('supply-position.post_add');
    Route::get('/manage-supply-position/{position_id}', [SupplyPositionController::class, 'get'])->name('supplyposition.single');
    Route::put('/manage-supply-position/{position_id}', [SupplyPositionController::class, 'update']);
    Route::delete('/manage-supply-position/single/{position_id}', [SupplyPositionController::class, 'delete_single']);
    Route::post('/manage-supply-position/batch', [SupplyPositionController::class, 'delete_batch'])->name('supplyposition.delete_batch');

    Route::get('/purchase-order/{id?}/{type?}', [PurchaseOrderController::class, 'get_by_id'])->name('po.get-single');

    Route::get('/inventory-custodian-slip-l', [TransactionController::class, 'add_ics_l'])->name('icsl.add');
    Route::get('/inventory-custodian-slip-h', [TransactionController::class, 'add_ics_h'])->name('icsh.add');
    Route::get('/inventory-custodian-slip', [TransactionController::class, 'add'])->name('ics.add');
    Route::post('/inventory-custodian-slip/{type?}', [TransactionController::class, 'save_ics'])->name('ics.save');
});

//ADMIN ONLY
Route::middleware('admin')->group(function () {
    Route::get('/admin-dashboard', [DashboardController::class, 'show'])->name('admin-dashboard.show');
});
