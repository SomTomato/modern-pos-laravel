<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display the product performance report.
     */
    public function productPerformance(Request $request)
    {
        $this->authorize('admin-only');
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $products = Product::with('category')
            ->select(
                'products.id', 'products.name', 'products.sku', 'products.image', 'products.category_id',
                DB::raw('SUM(sale_items.quantity) as total_quantity_sold'),
                DB::raw('SUM(sale_items.quantity * sale_items.price_per_unit) as total_revenue')
            )
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"])
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.image', 'products.category_id')
            ->orderBy('total_revenue', 'desc')
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Reports', 'url' => '#'],
            ['name' => 'Product Performance']
        ];
        
        return view('reports.product_performance', compact('products', 'startDate', 'endDate', 'breadcrumbs'));
    }

    /**
     * Display the End of Day report.
     */
    public function endOfDayReport(Request $request)
    {
        $this->authorize('admin-only');
        $request->validate(['report_date' => 'nullable|date']);
        $reportDate = $request->input('report_date', Carbon::today()->toDateString());

        $sales = Sale::with('user', 'customer')->whereDate('created_at', $reportDate)->orderBy('created_at', 'asc')->get();
        $totalRevenue = $sales->sum('total_amount');
        $totalSales = $sales->count();
        $paymentBreakdown = $sales->groupBy('payment_method')->map(fn($group) => $group->sum('total_amount'));
        
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Reports', 'url' => '#'], ['name' => 'End of Day']];
        
        return view('reports.end_of_day', compact('reportDate', 'sales', 'totalRevenue', 'totalSales', 'paymentBreakdown', 'breadcrumbs'));
    }

    /**
     * THE FIX: New method to show the dedicated print view for the End of Day report.
     */
    public function endOfDayReportPrint(Request $request)
    {
        $this->authorize('admin-only');
        $request->validate(['report_date' => 'nullable|date']);
        $reportDate = $request->input('report_date', Carbon::today()->toDateString());

        $sales = Sale::with('user', 'customer')->whereDate('created_at', $reportDate)->orderBy('created_at', 'asc')->get();
        $totalRevenue = $sales->sum('total_amount');
        $totalSales = $sales->count();
        $paymentBreakdown = $sales->groupBy('payment_method')->map(fn($group) => $group->sum('total_amount'));

        return view('reports.end_of_day_print', compact('reportDate', 'sales', 'totalRevenue', 'totalSales', 'paymentBreakdown'));
    }
}
