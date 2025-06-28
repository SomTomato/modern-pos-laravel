<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'limit' => 'nullable|integer',
        ]);

        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $limit = $request->input('limit', 10);

        // THE FIX: Changed 'sale_date' to 'created_at' to match the database
        $query = Sale::with('user')
                     ->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);

        $totalRevenue = $query->sum('total_amount');

        // THE FIX: Changed orderBy('sale_date') to orderBy('created_at')
        $sales = $query->orderBy('created_at', 'desc')
                       ->paginate($limit)
                       ->withQueryString();
        
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Sales Report']];

        return view('sales_report.index', compact('sales', 'totalRevenue', 'startDate', 'endDate', 'limit', 'breadcrumbs'));
    }
}
