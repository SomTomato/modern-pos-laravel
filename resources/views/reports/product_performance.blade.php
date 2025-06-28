@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-star"></i> Product Performance Report</h1>

<div class="card">
    <div class="card-header">
        <h2>Filter Report</h2>
    </div>
    <form method="get" action="{{ route('reports.product_performance') }}" class="filter-form">
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
    <div class="card-header">
        <h2>Performance from {{ $startDate }} to {{ $endDate }}</h2>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Product</th>
                <th>Category</th>
                <th style="text-align: center;">Units Sold</th>
                <th style="text-align: right;">Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $index => $product)
                <tr>
                    <td style="font-weight: bold; text-align: center;">#{{ $products->firstItem() + $index }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img class="table-image" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <small>SKU: {{ $product->sku }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td style="text-align: center; font-size: 1.1em;">{{ $product->total_quantity_sold }}</td>
                    <td style="text-align: right; font-weight: bold; color: var(--success-color); font-size: 1.1em;">${{ number_format($product->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No sales data found for the selected period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container" style="margin-top: 20px;">
        {{ $products->links() }}
    </div>
</div>
@endsection
