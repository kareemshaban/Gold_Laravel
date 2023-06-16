<?php

use Illuminate\Support\Facades\Route;

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


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {

//Route::get('/', function () {
//    return view('welcome');
//}) -> name('front');
    Route::get('/', [App\Http\Controllers\PricingController::class, 'pricing'])->name('front');

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
    Route::post('/storeCategory', [App\Http\Controllers\CategoryController::class, 'store'])->name('storeCategory');
    Route::get('/deleteCategory/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('deleteCategory');
    Route::get('/getCategory/{id}', [App\Http\Controllers\CategoryController::class, 'show'])->name('getCategory');

    Route::get('/karats', [App\Http\Controllers\KaratController::class, 'index'])->name('karats');
    Route::post('/storeKarat', [App\Http\Controllers\KaratController::class, 'store'])->name('storeKarat');
    Route::get('/deleteKarat/{id}', [App\Http\Controllers\KaratController::class, 'destroy'])->name('deleteKarat');
    Route::get('/getKarat/{id}', [App\Http\Controllers\KaratController::class, 'show'])->name('getKarat');

    Route::get('/prices', [App\Http\Controllers\PricingController::class, 'index'])->name('prices');
    Route::get('/updatePrices', [App\Http\Controllers\PricingController::class, 'edit'])->name('updatePrices');
    Route::post('/updatePricesManual', [App\Http\Controllers\PricingController::class, 'update'])->name('updatePricesManual');

    Route::get('/items', [App\Http\Controllers\ItemController::class, 'index'])->name('items');
    Route::post('/storeItem', [App\Http\Controllers\ItemController::class, 'store'])->name('storeItem');
    Route::get('/deleteItem/{id}', [App\Http\Controllers\ItemController::class, 'destroy'])->name('deleteItem');
    Route::get('/getItem/{id}', [App\Http\Controllers\ItemController::class, 'show'])->name('getItem');
    Route::post('/compineItem', [App\Http\Controllers\ItemController::class, 'compineItem'])->name('compineItem');
    Route::get('/getParentItem/{id}', [App\Http\Controllers\ItemController::class, 'getParentItem'])->name('getParentItem');
    Route::get('/print_barcode', [App\Http\Controllers\ItemController::class, 'print_barcode'])->name('print_barcode');
    Route::get('/print_qrcode', [App\Http\Controllers\ItemController::class, 'print_qrcode'])->name('print_qrcode');
    Route::post('print_barcode', [App\Http\Controllers\ItemController::class, 'do_print_barcode'])->name('preview_barcode');
    Route::post('print_qrcode', [App\Http\Controllers\ItemController::class, 'do_print_qr'])->name('preview_qr');

    Route::get('/printBarcode/{id}', [App\Http\Controllers\ItemController::class, 'printBarcode'])->name('printBarcode');
    Route::get('/deleteItemMaterial/{id}', [App\Http\Controllers\ItemController::class, 'deleteItemMaterial'])->name('deleteItemMaterial');


    Route::get('/clients/{type}', [App\Http\Controllers\CompanyController::class, 'index'])->name('clients');
    Route::post('storeCompany', [App\Http\Controllers\CompanyController::class, 'store'])->name('storeCompany');
    Route::get('/deleteCompany/{id}', [App\Http\Controllers\CompanyController::class, 'destroy'])->name('deleteCompany');
    Route::get('/getCompany/{id}', [App\Http\Controllers\CompanyController::class, 'edit'])->name('getCompany');
    Route::get('/clientAccount/{id}', [App\Http\Controllers\CompanyController::class, 'clientAccount'])->name('clientAccount');

    Route::get('/workEntryAll', [App\Http\Controllers\EnterWorkController::class, 'index'])->name('workEntryAll');
    Route::get('/workEntryCreate', [App\Http\Controllers\EnterWorkController::class, 'create'])->name('workEntryCreate');
    Route::post('storeWorkEntry', [App\Http\Controllers\EnterWorkController::class, 'store'])->name('storeWorkEntry');
    Route::get('/get_work_entry_no', [App\Http\Controllers\EnterWorkController::class, 'get_work_entry_no'])->name('get_work_entry_no');
    Route::get('/workEntryPreview/{id}', [App\Http\Controllers\EnterWorkController::class, 'show'])->name('workEntryPreview');
    Route::get('/workEntryDelete/{id}', [App\Http\Controllers\EnterWorkController::class, 'destroy'])->name('workEntryDelete');
    Route::get('/workEnterPrint/{id}', [App\Http\Controllers\EnterWorkController::class, 'print'])->name('workEnterPrint');


    Route::get('/oldEntryAll', [App\Http\Controllers\EnterOldController::class, 'index'])->name('oldEntryAll');
    Route::get('/oldEntryCreate', [App\Http\Controllers\EnterOldController::class, 'create'])->name('oldEntryCreate');
    Route::post('storeOldEntry', [App\Http\Controllers\EnterOldController::class, 'store'])->name('storeOldEntry');
    Route::get('/get_old_entry_no', [App\Http\Controllers\EnterOldController::class, 'get_old_entry_no'])->name('get_old_entry_no');
    Route::get('/oldEntryPreview/{id}', [App\Http\Controllers\EnterOldController::class, 'show'])->name('oldEntryPreview');
    Route::get('/oldEntryDelete/{id}', [App\Http\Controllers\EnterOldController::class, 'destroy'])->name('oldEntryDelete');
    Route::get('/oldEnterPrint/{id}', [App\Http\Controllers\EnterOldController::class, 'print'])->name('oldEnterPrint');


    Route::get('/gold_stock', [App\Http\Controllers\WarehouseController::class, 'gold_stock'])->name('gold_stock');

    Route::get('/oldExitAll', [App\Http\Controllers\ExitOldController::class, 'index'])->name('oldExitAll');
    Route::get('/oldExitCreate', [App\Http\Controllers\ExitOldController::class, 'create'])->name('oldExitCreate');
    Route::post('storeOldExit', [App\Http\Controllers\ExitOldController::class, 'store'])->name('storeOldExit');
    Route::get('/get_old_exit_no', [App\Http\Controllers\ExitOldController::class, 'get_old_exit_no'])->name('get_old_exit_no');
    Route::get('/oldExitPreview/{id}', [App\Http\Controllers\ExitOldController::class, 'show'])->name('oldExitPreview');
    Route::get('/oldExitDelete/{id}', [App\Http\Controllers\ExitOldController::class, 'destroy'])->name('oldExitDelete');
    Route::get('/oldExitPrint/{id}', [App\Http\Controllers\ExitOldController::class, 'print'])->name('oldExitPrint');


    Route::get('/workExitAll', [App\Http\Controllers\ExitWorkController::class, 'index'])->name('workExitAll');
    Route::get('/workExitCreate', [App\Http\Controllers\ExitWorkController::class, 'create'])->name('workExitCreate');
    Route::post('storeWorkExit', [App\Http\Controllers\ExitWorkController::class, 'store'])->name('storeWorkExit');
    Route::get('/get_work_exit_no', [App\Http\Controllers\ExitWorkController::class, 'get_work_exit_no'])->name('get_work_exit_no');
    Route::get('/getProduct/{code}', [App\Http\Controllers\ItemController::class, 'getProduct'])->name('getProduct');
    Route::get('/getKaratPrice/{id}', [App\Http\Controllers\ExitWorkController::class, 'getKaratPrice'])->name('getKaratPrice');
    Route::get('/workExitPreview/{id}', [App\Http\Controllers\ExitWorkController::class, 'show'])->name('workExitPreview');
    Route::get('/workExitPrint/{id}', [App\Http\Controllers\ExitWorkController::class, 'print'])->name('workExitPrint');
    Route::get('/workQrcode/{id}', [App\Http\Controllers\ExitWorkController::class, 'Qrcode'])->name('workQrcode');


    Route::get('/money_entry_list', [App\Http\Controllers\EnterMoneyController::class, 'index'])->name('money_entry_list');
    Route::get('/money_entry_create', [App\Http\Controllers\EnterMoneyController::class, 'create'])->name('money_entry_create');
    Route::post('storeMoneyEnter', [App\Http\Controllers\EnterMoneyController::class, 'store'])->name('storeMoneyEnter');
    Route::get('/getClientExitWorks/{id}', [App\Http\Controllers\EnterMoneyController::class, 'getClientExitWorks'])->name('getClientExitWorks');
    Route::get('/enterMoneyPreview/{id}', [App\Http\Controllers\EnterMoneyController::class, 'show'])->name('enterMoneyPreview');
    Route::get('/enterMoneyDestroy/{id}', [App\Http\Controllers\EnterMoneyController::class, 'destroy'])->name('enterMoneyDestroy');


    Route::get('/money_exit_list', [App\Http\Controllers\ExitMoneyController::class, 'index'])->name('money_exit_list');
    Route::get('/money_exit_create', [App\Http\Controllers\ExitMoneyController::class, 'create'])->name('money_exit_create');
    Route::post('storeMoneyExit', [App\Http\Controllers\ExitMoneyController::class, 'store'])->name('storeMoneyExit');
    Route::get('/exitMoneyPreview/{id}/{type}', [App\Http\Controllers\ExitMoneyController::class, 'show'])->name('exitMoneyPreview');
    Route::get('/exitMoneyDestroy/{id}', [App\Http\Controllers\ExitMoneyController::class, 'destroy'])->name('exitMoneyDestroy');
    Route::get('/getClientSupplier/{type}', [App\Http\Controllers\ExitMoneyController::class, 'getClientSupplier'])->name('getClientSupplier');
    Route::get('/getClientSupplierWorks/{id}/{type}', [App\Http\Controllers\ExitMoneyController::class, 'getClientSupplierWorks'])->name('getClientSupplierWorks');
    Route::get('/getClientDocumentdata/{id}/{type}', [App\Http\Controllers\ExitMoneyController::class, 'getClientDocumentdata'])->name('getClientDocumentdata');


    Route::get('/warehouses', [App\Http\Controllers\StorehouseController::class, 'index'])->name('warehouses');
    Route::post('/storeWarehouse', [App\Http\Controllers\StorehouseController::class, 'store'])->name('storeWarehouse');
    Route::get('/deleteWarehouse/{id}', [App\Http\Controllers\StorehouseController::class, 'destroy'])->name('deleteWarehouse');
    Route::get('/getWarehouse/{id}', [App\Http\Controllers\StorehouseController::class, 'show'])->name('getWarehouse');

    Route::get('/purchases', [App\Http\Controllers\PurchaseController::class, 'index'])->name('purchases');
    Route::get('/createPurchase', [App\Http\Controllers\PurchaseController::class, 'create'])->name('createPurchase');
    Route::post('/storePurchase', [App\Http\Controllers\PurchaseController::class, 'store'])->name('storePurchase');
    Route::get('/get_purchase_no/{id}', [App\Http\Controllers\PurchaseController::class, 'get_purchase_no'])->name('get_purchase_no');
    Route::get('/getItemPro/{code}', [App\Http\Controllers\ItemController::class, 'getItemPro'])->name('getItemPro');
    Route::get('/delete_purchase/{id}', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('delete_purchase');
    Route::get('/preview_purchase/{id}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('preview_purchase');


    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales');
    Route::get('/sales/add', [App\Http\Controllers\SalesController::class, 'create'])->name('add_sale');
    Route::post('/sales/add', [App\Http\Controllers\SalesController::class, 'store'])->name('store_sale');
    Route::get('/getLastSalesBill', [App\Http\Controllers\SalesController::class, 'getLastSalesBill'])->name('getLastSalesBill');
    Route::get('/get_sales_no/{id}', [App\Http\Controllers\SalesController::class, 'getNo'])->name('get_sales_no');
    Route::get('/preview_sales/{id}', [App\Http\Controllers\SalesController::class, 'show'])->name('preview_sales');


    Route::get('/item_list_report', [App\Http\Controllers\ReportController::class, 'item_list_report'])->name('item_list_report');
    Route::post('/item_list_report', [App\Http\Controllers\ReportController::class, 'item_list_report_search'])->name('item_list_report_search');
    Route::get('/sold_items_report', [App\Http\Controllers\ReportController::class, 'sold_items_report'])->name('sold_items_report');
    Route::post('/sold_items_report', [App\Http\Controllers\ReportController::class, 'sold_items_report_search'])->name('sold_items_report_search');
    Route::get('/sales_report', [App\Http\Controllers\ReportController::class, 'sales_report'])->name('sales_report');
    Route::post('/sales_report', [App\Http\Controllers\ReportController::class, 'sales_report_search'])->name('sales_report_search');
    Route::get('/purchase_report', [App\Http\Controllers\ReportController::class, 'purchase_report'])->name('purchase_report');
    Route::post('/purchase_report', [App\Http\Controllers\ReportController::class, 'purchase_report_search'])->name('purchase_report_search');
    Route::get('/vendor_account', [App\Http\Controllers\ReportController::class, 'vendor_account'])->name('vendor_account');
    Route::post('/vendor_account', [App\Http\Controllers\ReportController::class, 'vendor_account_search'])->name('vendor_account_search');
    Route::get('/gold_stock_report', [App\Http\Controllers\ReportController::class, 'gold_stock_report'])->name('gold_stock_report');
    Route::post('/gold_stock_report', [App\Http\Controllers\ReportController::class, 'gold_stock_search'])->name('gold_stock_search');
    Route::get('/daily_all_movements', [App\Http\Controllers\ReportController::class, 'daily_all_movements'])->name('daily_all_movements');
    Route::post('/daily_all_movements', [App\Http\Controllers\ReportController::class, 'daily_all_movements_search'])->name('daily_all_movements_search');


    Route::get('/pos', [App\Http\Controllers\PosController::class, 'pos'])->name('pos');
    Route::post('/store_pos', [App\Http\Controllers\PosController::class, 'store_pos'])->name('store_pos');
    Route::get('/get_sales_pos_no/{type}', [App\Http\Controllers\ExitWorkController::class, 'get_sales_pos_no'])->name('get_sales_pos_no');
    Route::post('/posPayment', [App\Http\Controllers\PosController::class, 'posPayment'])->name('posPayment');
    Route::get('/pos_payment_show/{id}/{type}', [App\Http\Controllers\PosController::class, 'pos_payment_show'])->name('pos_payment_show');
    Route::get('/pos_sales', [App\Http\Controllers\PosController::class, 'pos_sales'])->name('pos_sales');
    Route::get('/pos_purchase', [App\Http\Controllers\PosController::class, 'pos_purchase'])->name('pos_purchase');
    Route::get('/return_work/{id}', [App\Http\Controllers\PosController::class, 'return_work'])->name('return_work');
    Route::post('/return_work_post', [App\Http\Controllers\PosController::class, 'return_work_post'])->name('return_work_post');
    Route::get('/return_sales', [App\Http\Controllers\PosController::class, 'return_sales'])->name('return_sales');


    Route::post('/store_pos_purchase', [App\Http\Controllers\PosController::class, 'store_pos_purchase'])->name('store_pos_purchase');
    Route::get('/get_purchase_pos_no', [App\Http\Controllers\EnterWorkController::class, 'get_purchase_pos_no'])->name('get_purchase_pos_no');

    Route::get('/getItemCode', [\App\Http\Controllers\ItemController::class, 'getItemCode'])->name('getItemCode');


    Route::get('/test_print', [App\Http\Controllers\PosController::class, 'test_print'])->name('test_print');


    Route::get('/accounts', [\App\Http\Controllers\AccountsTreeController::class, 'index'])->name('accounts_list');
    Route::get('/accounts/create', [\App\Http\Controllers\AccountsTreeController::class, 'create'])->name('create_account');
    Route::post('/accounts/create', [\App\Http\Controllers\AccountsTreeController::class, 'store'])->name('store_account');
    Route::get('/accounts/get_level/{parent}', [\App\Http\Controllers\AccountsTreeController::class, 'getLevel'])->name('get_account_level');
    Route::get('/accounts/edit/{id}', [\App\Http\Controllers\AccountsTreeController::class, 'edit'])->name('edit_account');
    Route::post('/accounts/edit/{id}', [\App\Http\Controllers\AccountsTreeController::class, 'update'])->name('update_account');
    Route::get('/accounts/delete/{id}', [\App\Http\Controllers\AccountsTreeController::class, 'destroy'])->name('delete_account');

    Route::get('/account_settings', [\App\Http\Controllers\AccountSettingController::class, 'index'])->name('account_settings_list');
    Route::get('/account_settings/create', [\App\Http\Controllers\AccountSettingController::class, 'create'])->name('create_account_settings');
    Route::post('/account_settings/create', [\App\Http\Controllers\AccountSettingController::class, 'store'])->name('store_account_settings');
    Route::get('/account_settings/edit/{id}', [\App\Http\Controllers\AccountSettingController::class, 'edit'])->name('edit_account_settings');
    Route::post('/account_settings/edit/{id}', [\App\Http\Controllers\AccountSettingController::class, 'update'])->name('update_account_settings');
    Route::get('/account_settings/delete/{id}', [\App\Http\Controllers\AccountSettingController::class, 'destroy'])->name('delete_account_settings');
    Route::get('/accounts/journals', [\App\Http\Controllers\AccountsTreeController::class, 'journals'])->name('journals');
    Route::get('/accounts/journals/preview/{id}', [\App\Http\Controllers\AccountsTreeController::class, 'previewJournal'])->name('preview_journal');

    Route::get('/accounts/manual', [\App\Http\Controllers\JournalController::class, 'create'])->name('manual_journal');
    Route::post('/accounts/manual', [\App\Http\Controllers\JournalController::class, 'store'])->name('store_manual');
    Route::get('/getAccounts/{code}', [App\Http\Controllers\AccountsTreeController::class, 'getAccount'])->name('getAccounts');
    Route::get('/journals/delete/{id}', [\App\Http\Controllers\JournalController::class, 'delete'])->name('delete_journal');


    Route::get('/incoming_list', [\App\Http\Controllers\JournalController::class, 'incoming_list'])->name('incoming_list');
    Route::post('/search_incoming_list', [\App\Http\Controllers\JournalController::class, 'search_incoming_list'])->name('search_incoming_list');


    Route::get('/balance_sheet', [\App\Http\Controllers\JournalController::class, 'balance_sheet'])->name('balance_sheet');
    Route::post('/search_balance_sheet', [\App\Http\Controllers\JournalController::class, 'search_balance_sheet'])->name('search_balance_sheet');


    Route::get('/reports/account_balance', [\App\Http\Controllers\ReportController::class, 'account_balance'])->name('account_balance');
    Route::post('/reports/account_balance', [\App\Http\Controllers\ReportController::class, 'account_balance_search'])->name('search_account_balance');


    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::post('/storeUser', [\App\Http\Controllers\UserController::class, 'store'])->name('storeUser');
    Route::get('/getUser/{id}', [\App\Http\Controllers\UserController::class, 'show'])->name('getUser');
    Route::get('/destroyUser/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroyUser');

    Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles');
    Route::post('/storeRole', [\App\Http\Controllers\RoleController::class, 'store'])->name('storeRole');
    Route::get('/getRole/{id}', [\App\Http\Controllers\RoleController::class, 'show'])->name('getRole');
    Route::get('/destroyRole/{id}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('destroyRole');


    Route::get('/roleViews', [\App\Http\Controllers\RoleViewsController::class, 'index'])->name('roleViews');
    Route::post('/storeRoleView', [\App\Http\Controllers\RoleViewsController::class, 'store'])->name('storeRoleView');

    Route::get('/tax_settings', [\App\Http\Controllers\TaxSettingsController::class, 'index'])->name('tax_settings');
    Route::post('/storeTax', [\App\Http\Controllers\TaxSettingsController::class, 'store'])->name('storeTax');


    Route::get('/fixItems', [\App\Http\Controllers\ItemController::class, 'fixItems'])->name('fixItems');



    Route::get('/gold_convert_doc', [\App\Http\Controllers\GoldConvertController::class, 'index'])->name('gold_convert_doc');
    Route::get('/gold_convert_create', [\App\Http\Controllers\GoldConvertController::class, 'create'])->name('gold_convert_create');
    Route::get('/gold_convert_preview/{id}', [\App\Http\Controllers\GoldConvertController::class, 'show'])->name('gold_convert_preview');
    Route::post('/gold_convert_store', [\App\Http\Controllers\GoldConvertController::class, 'store'])->name('gold_convert_store');
    Route::get('/get_gold_convert_no', [\App\Http\Controllers\GoldConvertController::class, 'get_gold_convert_no'])->name('get_gold_convert_no');

    Route::get('/expenses', [\App\Http\Controllers\ExpensesController::class, 'index'])->name('expenses');
    Route::get('/get_Expense_no', [\App\Http\Controllers\ExpensesController::class, 'get_Expense_no'])->name('get_Expense_no');
    Route::post('/storeExpense', [\App\Http\Controllers\ExpensesController::class, 'store'])->name('storeExpense');
    Route::get('/getExpense/{id}', [\App\Http\Controllers\ExpensesController::class, 'show'])->name('getExpense');




    Route::get('/catches', [\App\Http\Controllers\CatchReciptController::class, 'index'])->name('catches');
    Route::get('/get_Catch_no', [\App\Http\Controllers\CatchReciptController::class, 'get_Catch_no'])->name('get_Catch_no');
    Route::post('/storeCatch', [\App\Http\Controllers\CatchReciptController::class, 'store'])->name('storeCatch');
    Route::get('/getCatch/{id}', [\App\Http\Controllers\CatchReciptController::class, 'show'])->name('getCatch');




    Route::get('/expenses_type/{id}', [\App\Http\Controllers\ExpenseTypeController::class, 'index'])->name('expenses_type');
    Route::post('/storeExpensType', [\App\Http\Controllers\ExpenseTypeController::class, 'store'])->name('storeExpensType');
    Route::get('/expenses_type_destroy/{id}', [\App\Http\Controllers\ExpenseTypeController::class, 'destroy'])->name('expenses_type_destroy');
    Route::get('/getExpenseType/{id}', [\App\Http\Controllers\ExpenseTypeController::class, 'show'])->name('getExpenseType');


    Route::get('/companyInfo', [\App\Http\Controllers\CompanyInfoController::class, 'index'])->name('companyInfo');
    Route::post('/storeCompanyInfo', [\App\Http\Controllers\CompanyInfoController::class, 'store'])->name('storeCompanyInfo');



    Route::get('/testBar', [\App\Http\Controllers\CompanyInfoController::class, 'testBar'])->name('testBar');





});




