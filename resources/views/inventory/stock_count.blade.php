@extends('layouts.app')

{{-- No more print styles are needed in this file --}}

@section('content')
<div class="card no-print">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h1><i class="fa-solid fa-clipboard-list"></i> Stock Count</h1>
        <button class="btn btn-primary" onclick="window.open('{{ route('inventory.stock_count.print') }}', '_blank');">
            <i class="fa-solid fa-print"></i> Print Worksheet
        </button>
    </div>
    <p>This page shows your current stock levels. Use the print button to generate a clean A4 worksheet for a physical stock count.</p>
</div>

{{-- This table is now only for on-screen viewing and is much simpler --}}
<div class="card">
    <div class="card-header">
        <h2>Current Stock Levels (On-Screen View)</h2>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Product</th>
                <th style="text-align: center;">System Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stockLevels as $product)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img class="table-image" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <small>SKU: {{ $product->sku }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center; font-weight: bold; font-size: 1.2em;">
                        {{ $product->quantity }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="2">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
