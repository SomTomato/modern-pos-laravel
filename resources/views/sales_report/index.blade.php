@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-file-invoice-dollar"></i> Sales Report</h1>

<div class="card">
    <div class="card-header">
        <h2>Filter Sales</h2>
    </div>
    <form method="get" action="{{ route('sales.report') }}" class="filter-form">
        <div style="display: flex; gap: 20px; align-items: flex-end;">
            <div class="form-group" style="flex: 1;">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Sales from {{ $startDate }} to {{ $endDate }}</h2>
        <div style="text-align: right;">
            <h3 style="margin: 0;">Total Revenue: <span style="color: var(--success-color);">${{ number_format($totalRevenue, 2) }}</span></h3>
        </div>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Sale ID</th><th>Date & Time</th><th>Cashier</th><th style="text-align: right;">Total Amount</th><th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    {{-- THE FIX: Check if created_at exists before formatting it --}}
                    <td>{{ $sale->created_at ? $sale->created_at->format('Y-m-d h:i A') : 'N/A' }}</td>
                    <td>{{ $sale->user->username ?? 'N/A' }}</td>
                    <td style="text-align: right;">${{ number_format($sale->total_amount, 2) }}</td>
                    <td style="text-align: center;"><a href="{{ route('invoice.show', $sale) }}" class="btn btn-primary" target="_blank">View Invoice</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No sales found for the selected period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container" style="margin-top: 20px;">
        {{ $sales->links() }}
    </div>
</div>
@endsection
