@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header no-print" style="display: flex; justify-content: space-between; align-items: center;">
        <h1><i class="fa-solid fa-sun"></i> End of Day Report</h1>
        {{-- THE FIX: Removed target="_blank" to keep the navigation within the same tab. --}}
        <a href="{{ route('reports.end_of_day.print', ['report_date' => $reportDate]) }}" class="btn btn-primary" id="print-report-link">
            <i class="fa-solid fa-print"></i> Print Report
        </a>
    </div>

    <div class="card-body">
        <div class="no-print" style="padding-bottom: 20px; border-bottom: 1px solid var(--border-color, #dee2e6); margin-bottom: 20px;">
            <form method="get" action="{{ route('reports.end_of_day') }}" class="filter-form">
                <div class="form-group">
                    <label for="report_date">Select Report Date</label>
                    <input type="date" name="report_date" id="report_date" class="form-control" value="{{ $reportDate }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <h2>End of Day Summary</h2>
            <p style="font-size: 1.2em; margin: 0;">Date: <strong>{{ \Carbon\Carbon::parse($reportDate)->format('F j, Y') }}</strong></p>
        </div>

        <div class="stats-grid" style="margin-bottom: 30px;">
            <div class="stat-card sales"><div class="icon"><i class="fa-solid fa-receipt"></i></div><div class="info"><h3>Total Sales</h3><p>{{ $totalSales }}</p></div></div>
            <div class="stat-card revenue"><div class="icon"><i class="fa-solid fa-dollar-sign"></i></div><div class="info"><h3>Total Revenue</h3><p>${{ number_format($totalRevenue, 2) }}</p></div></div>
        </div>

        <div class="card card-light-bg">
            <div class="card-header"><h3>Revenue by Payment Method</h3></div>
            <table class="styled-table">
                <thead><tr><th>Payment Method</th><th style="text-align: right;">Total Amount</th></tr></thead>
                <tbody>
                    @forelse($paymentBreakdown as $method => $amount)
                        <tr><td>{{ ucfirst($method) }}</td><td style="text-align: right;">${{ number_format($amount, 2) }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="text-center">No payment data for this day.</td></tr>
                    @endforelse
                    <tr style="font-weight: bold; border-top: 2px solid var(--border-color, #dee2e6);"><td>Grand Total</td><td style="text-align: right;">${{ number_format($totalRevenue, 2) }}</td></tr>
                </tbody>
            </table>
        </div>
        
        <div class="card" style="margin-top: 30px;">
            <div class="card-header"><h3>All Transactions for {{ \Carbon\Carbon::parse($reportDate)->format('F j, Y') }}</h3></div>
            <table class="styled-table">
                <thead><tr><th>Time</th><th>Sale ID</th><th>Cashier</th><th>Customer</th><th style="text-align: right;">Total Amount</th></tr></thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr><td>{{ $sale->created_at->format('h:i:s A') }}</td><td>#{{ $sale->id }}</td><td>{{ $sale->user->name ?? 'N/A' }}</td><td>{{ $sale->customer->name ?? 'Walk-in' }}</td><td style="text-align: right;">${{ number_format($sale->total_amount, 2) }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No sales were recorded on this day.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const printLink = document.getElementById('print-report-link');
        if (printLink && document.body.classList.contains('dark-mode')) {
            const url = new URL(printLink.href);
            url.searchParams.set('dark_mode', 'true');
            printLink.href = url.toString();
        }
    });
</script>
@endpush
