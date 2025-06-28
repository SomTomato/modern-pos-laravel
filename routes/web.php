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
use App\Http\Controllers\SettingsController; // Add this use statement at the top!
use App\Http\Controllers\ReportsController; // Add this use statement at the top!
use App\Http\Controllers\EmployeeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// THE FIX: Changed the root route to redirect to the login page.
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
    Route::get('/stock-count', [InventoryController::class, 'stockCount'])->name('inventory.stock_count');
    Route::get('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('inventory.stock_adjustment');
    Route::post('/stock-adjustment/process', [InventoryController::class, 'processStockAdjustment'])->name('inventory.process_adjustment');

    // Products
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::resource('products', ProductController::class);

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show', 'create', 'edit', 'update']);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit', 'update']);

    // Users
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    // --- Settings Route ---
    Route::get('/settings/store', [SettingsController::class, 'storeSettings'])->name('settings.store');
    // --- Reports ---
    Route::get('/reports/product-performance', [ReportsController::class, 'productPerformance'])->name('reports.product_performance');
    Route::get('/reports/end-of-day', [ReportsController::class, 'endOfDayReport'])->name('reports.end_of_day');
    // Add this new route for printing the stock count
    Route::get('/stock-count/print', [InventoryController::class, 'stockCountPrint'])->name('inventory.stock_count.print');
    Route::get('/reports/end-of-day/print', [ReportsController::class, 'endOfDayReportPrint'])->name('reports.end_of_day.print');
    Route::get('/settings/store', [SettingsController::class, 'storeSettings'])->name('settings.store');
    // THE FIX: Add these three separate routes for updating
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
    Route::post('/settings/financial', [SettingsController::class, 'updateFinancial'])->name('settings.financial.update');
    Route::post('/settings/receipt', [SettingsController::class, 'updateReceipt'])->name('settings.receipt.update');
    // Add this line inside the auth middleware group
    Route::resource('employees', EmployeeController::class)->except(['show', 'destroy', 'edit', 'update']);
    });

require __DIR__.'/auth.php';
