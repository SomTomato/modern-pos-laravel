@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-box-archive"></i> Product Management</h1>

{{-- Removed old alert messages. The layout partial now handles them. --}}

<div class="card">
    <div class="card-header">
        <h2>Filter Products</h2>
    </div>
    <form method="get" action="{{ route('products.index') }}" class="filter-form">
        <div style="display: flex; gap: 20px; align-items: flex-end;">
            <div class="form-group" style="flex: 1;">
                <label for="category_id">Filter by Category</label>
                <select name="category_id" id="category_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                 <label for="limit">Show Per Page</label>
                <select name="limit" id="limit" class="form-control" onchange="this.form.submit()">
                    <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                    <option value="0" {{ $limit == 0 ? 'selected' : '' }}>All</option>
                </select>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Existing Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Product</a>
    </div>
    <table class="styled-table">
        {{-- Table content remains the same --}}
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Barcode (SKU)</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Status</th>
                <th style="width: 220px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr>
                <td><img class="table-image" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}"></td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->quantity }}</td>
                <td>
                    @if ($product->is_active)
                        <span class="status-badge active">Active</span>
                    @else
                        <span class="status-badge disabled">Disabled</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit</a>
                    <form method="POST" action="{{ route('products.toggleStatus', $product) }}" style="display:inline;">
                        @csrf
                        @if ($product->is_active)
                            <button type="submit" class="btn btn-danger">Disable</button>
                        @else
                            <button type="submit" class="btn btn-success">Enable</button>
                        @endif
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No products found matching the criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-container" style="margin-top: 20px;">
        {{ $products->links() }}
    </div>
</div>
@endsection
