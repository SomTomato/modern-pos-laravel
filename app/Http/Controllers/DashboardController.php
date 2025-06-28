<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // THE FIX: Changed all instances of 'sale_date' back to 'created_at'
        $total_products = Product::count();
        $sales_today_count = Sale::whereDate('created_at', today())->count();
        $revenue_today = Sale::whereDate('created_at', today())->sum('total_amount');

        // Chart Data
        $sales_data = Sale::select(
                DB::raw('DATE(created_at) as sale_day'),
                DB::raw('SUM(total_amount) as daily_total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('sale_day')
            ->orderBy('sale_day')
            ->pluck('daily_total', 'sale_day')
            ->all();

        $chart_labels = [];
        $chart_values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chart_labels[] = Carbon::parse($date)->format('D, M j');
            $chart_values[] = $sales_data[$date] ?? 0;
        }
        
        $breadcrumbs = [['name' => 'Dashboard']];

        return view('dashboard', compact(
            'total_products',
            'sales_today_count',
            'revenue_today',
            'chart_labels',
            'chart_values',
            'breadcrumbs'
        ));
    }
}
