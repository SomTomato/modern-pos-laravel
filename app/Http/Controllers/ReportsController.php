<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportsController extends Controller
{
    /**
     * Gathers all necessary data for the End of Day report.
     *
     * @param  \Carbon\Carbon  $date
     * @return array
     */
    private function getEndOfDayReportData(Carbon $date): array
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $sales = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])->with(['user', 'customer'])->get();

        $totalRevenue = $sales->sum('total_amount');
        $totalTransactions = $sales->count();

        $paymentBreakdown = $sales->groupBy('payment_method')->map(function ($group) {
            return $group->sum('total_amount');
        });

        $topSellingProducts = SaleItem::whereIn('sale_id', $sales->pluck('id'))
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select(
                'sale_items.product_id',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(products.price * sale_items.quantity) as total_price')
            )
            ->groupBy('sale_items.product_id')
            ->with('product')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        return [
            'reportDate' => $date->toDateString(),
            'date' => $date,
            'sales' => $sales,
            'totalSales' => $totalTransactions,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'paymentBreakdown' => $paymentBreakdown,
            'topSellingProducts' => $topSellingProducts,
        ];
    }

    public function endOfDayReport(Request $request): View
    {
        $reportDate = $request->input('report_date') ? Carbon::parse($request->input('report_date')) : Carbon::today();
        $reportData = $this->getEndOfDayReportData($reportDate);

        return view('reports.end_of_day', $reportData);
    }

    public function endOfDayReportPrint(Request $request): View
    {
        $reportDate = $request->input('report_date') ? Carbon::parse($request->input('report_date')) : Carbon::today();
        $reportData = $this->getEndOfDayReportData($reportDate);

        return view('reports.end_of_day_print', $reportData);
    }

    /**
     * THE FIX: Corrected the query to select all necessary product and category data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function productPerformance(Request $request): View
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfDay();
        $selectedCategoryId = $request->input('category_id');

        $query = SaleItem::join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select(
                'products.name as product_name',
                'products.sku as product_sku',
                'products.image as product_image',
                'categories.name as category_name',
                DB::raw('SUM(sale_items.quantity) as total_quantity_sold'),
                DB::raw('SUM(products.price * sale_items.quantity) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.image', 'categories.name');

        if ($selectedCategoryId) {
            $query->where('products.category_id', $selectedCategoryId);
        }
            
        $products = $query->orderByDesc('total_quantity_sold')->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('reports.product_performance', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }
}
