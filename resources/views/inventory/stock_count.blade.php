@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h1><i class="fa-solid fa-clipboard-list"></i> Stock Count</h1>
        <a href="{{ route('inventory.stock_count.print_view') }}" class="btn btn-primary">
            <i class="fa-solid fa-print"></i> Print Stock Count
        </a>
    </div>
    <div class="card-body">
        <p>This page shows your current stock levels. Use the "View Printable Worksheet" button to generate a clean A4 worksheet for a physical stock count.</p>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h2>Current Stock Levels (On-Screen View)</h2>
    </div>
    <div class="card-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align: center;">System Quantity</th>
                    <th style="text-align: center;">Stock Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockLevels as $product)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img class="table-image" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <div>
                                    <strong>{{ $product->name }}</strong><br>
                                    <small>SKU: {{ $product->sku }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center; font-weight: bold; font-size: 1.2em; vertical-align: middle;">
                            {{ $product->quantity }}
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            @if ($product->quantity > 20)
                                <span class="badge" style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 12px; font-size: 0.9em;">Full Stock</span>
                            @elseif ($product->quantity > 10)
                                <span class="badge" style="background-color: #17a2b8; color: white; padding: 5px 10px; border-radius: 12px; font-size: 0.9em;">Needs Refill</span>
                            @elseif ($product->quantity > 0)
                                <span class="badge" style="background-color: #ffc107; color: #212529; padding: 5px 10px; border-radius: 12px; font-size: 0.9em;">Low Stock</span>
                            @else
                                <span class="badge" style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 12px; font-size: 0.9em;">Out of Stock</span>
                            @endif
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            {{-- THE FIX: Removed the inline style to allow the theme to apply the correct color --}}
                            <a href="{{ route('inventory.stock_adjustment') }}?product_id={{ $product->id }}" 
                               class="btn btn-primary btn-sm">
                               Adjust
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
