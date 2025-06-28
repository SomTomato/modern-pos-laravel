<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\EmployeeController;

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

//-------------------------------------------------------------------------
// GUEST ROUTES
//-------------------------------------------------------------------------

// Redirect the root URL to the login page for convenience.
Route::get('/', function () {
    return redirect()->route('login');
});


//-------------------------------------------------------------------------
// AUTHENTICATED ROUTES
//-------------------------------------------------------------------------

Route::middleware(['auth'])->group(function() {

    //--- Dashboard ---//
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //--- Point of Sale (POS) ---//
    Route::get('/pos-terminal', [PosController::class, 'index'])->name('pos.terminal');
    Route::get('/ajax/search-customers', [PosController::class, 'searchCustomers'])->name('ajax.searchCustomers');
    Route::post('/ajax/sales', [PosController::class, 'processSale'])->name('ajax.processSale');

    //--- Products & Categories ---//
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit', 'update']);

    //--- Inventory Management ---//
    // Grouping inventory routes under a common prefix for better organization.
    Route::prefix('inventory')->name('inventory.')->group(function() {
        Route::get('/stock-count', [InventoryController::class, 'stockCount'])->name('stock_count');
        Route::get('/stock-count/print', [InventoryController::class, 'printView'])->name('stock_count.print_view'); // The new print view route
        Route::get('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('stock_adjustment');
        Route::post('/stock-adjustment/process', [InventoryController::class, 'processStockAdjustment'])->name('process_adjustment');
    });

    //--- Sales, Invoices & Reports ---//
    Route::get('/invoice/{sale}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales.report');
    Route::get('/reports/product-performance', [ReportsController::class, 'productPerformance'])->name('reports.product_performance');
    Route::get('/reports/end-of-day', [ReportsController::class, 'endOfDayReport'])->name('reports.end_of_day');
    Route::get('/reports/end-of-day/print', [ReportsController::class, 'endOfDayReportPrint'])->name('reports.end_of_day.print');

    //--- Customers & Employees ---//
    Route::resource('customers', CustomerController::class)->except(['show', 'create', 'edit', 'update']);
    Route::resource('employees', EmployeeController::class)->except(['show', 'destroy', 'edit', 'update']);

    //--- Users & Settings ---//
    Route::resource('users', UserController::class)->except(['show', 'destroy']);

    // Grouping settings routes under a common prefix.
    Route::prefix('settings')->name('settings.')->group(function() {
        Route::get('/store', [SettingsController::class, 'storeSettings'])->name('store');
        Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::post('/financial', [SettingsController::class, 'updateFinancial'])->name('financial.update');
        Route::post('/receipt', [SettingsController::class, 'updateReceipt'])->name('receipt.update');
    });

});


//--------------------------------------------------------------------------
// AUTHENTICATION ROUTES
//--------------------------------------------------------------------------
// Includes login, registration, password reset, etc.
require __DIR__.'/auth.php';
