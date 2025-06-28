@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-chart-line"></i> Product Performance Report</h1>

<div class="card">
    <div class="card-header">
        <h2>Filter Report</h2>
    </div>
    <div class="card-body">
        <form method="get" action="{{ route('reports.product_performance') }}" class="filter-form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category_id">Filter by Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Apply Filters</button>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3>Performance from {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</h3>
    </div>
    <div class="card-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Rank</th>
                    <th style="width: 40%;">Product</th>
                    <th>Category</th>
                    <th style="text-align: center;">Units Sold</th>
                    <th style="text-align: right;">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $index => $product)
                    <tr>
                        <td style="text-align: center; font-weight: bold; font-size: 1.1em;">#{{ $products->firstItem() + $index }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img class="table-image" src="{{ asset('storage/products/' . $product->product_image) }}" alt="{{ $product->product_name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <div>
                                    <strong>{{ $product->product_name }}</strong><br>
                                    <small>SKU: {{ $product->product_sku }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category_name }}</td>
                        <td style="text-align: center;">{{ $product->total_quantity_sold }}</td>
                        <td style="text-align: right; color: #28a745; font-weight: bold;">${{ number_format($product->total_revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No sales data found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination-container" style="margin-top: 20px;">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
