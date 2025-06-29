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
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function() {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS Terminal & AJAX calls
    Route::get('/pos-terminal', [PosController::class, 'index'])->name('pos.terminal');
    Route::get('/ajax/search-customers', [PosController::class, 'searchCustomers'])->name('ajax.searchCustomers');
    Route::post('/ajax/sales', [PosController::class, 'processSale'])->name('ajax.processSale');

    // Invoice
    Route::get('/invoice/{sale}', [InvoiceController::class, 'show'])->name('invoice.show');

    // Sales Report
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales.report');

    // Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/stock-count', [InventoryController::class, 'stockCount'])->name('stock_count');
        Route::get('/stock-count/print', [InventoryController::class, 'printView'])->name('stock_count.print_view');
        Route::get('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('stock_adjustment');
        Route::post('/stock-adjustment/process', [InventoryController::class, 'processStockAdjustment'])->name('process_adjustment');
    });

    // Products & Catalog
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit', 'update']);

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show', 'create', 'edit', 'update']);

    // Users
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function() {
        Route::get('/product-performance', [ReportsController::class, 'productPerformance'])->name('product_performance');
        Route::get('/end-of-day', [ReportsController::class, 'endOfDayReport'])->name('end_of_day');
        Route::get('/end-of-day/print', [ReportsController::class, 'endOfDayReportPrint'])->name('end_of_day.print');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function() {
        Route::get('/store', [SettingsController::class, 'storeSettings'])->name('store');
        Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::post('/financial', [SettingsController::class, 'updateFinancial'])->name('financial.update');
        Route::post('/receipt', [SettingsController::class, 'updateReceipt'])->name('receipt.update');
    });

    // THE FIX: Moved Employees, Suppliers, and Purchase Orders to the correct top-level location.
    Route::resource('employees', EmployeeController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchase_orders', PurchaseOrderController::class);

});

require __DIR__.'/auth.php';
